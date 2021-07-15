<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\ItemReplaceDetail;
use App\Models\ModuleStock;
use App\Models\RepairItem;
use App\Models\RepairJobVendor;
use App\Models\Ticketing;
use App\Models\WarehouseJobOrder;
use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use DataTables;
use DB;
use Exception;
use File;
use Helper;
use Image;
use URL;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $job_orders = WarehouseJobOrder::select('uuid', 'repair_item_uuid', 'item_status', 'created_at')->where('job_status', 0)->get();

            return Datatables::of($job_orders)
                ->addIndexColumn()
                ->addColumn('ticket_number', function ($job_order) {
                    return  $job_order->repair->ticket->ticket_number;
                })
                ->addColumn('ticket_date', function ($job_order) {
                    return  Carbon::parse($job_order->repair->ticket->created_at)->translatedFormat('l\\, j F Y H:i');
                })
                ->addColumn('ticket_status', function ($job_order) {
                    return Helper::TicketStatus($job_order->repair->ticket->ticket_status);
                })
                ->editColumn('item_status', function ($job_order) {
                    return Helper::ItemStatus($job_order->item_status);
                })
                ->addColumn('urgent_status', function ($job_order) {
                    return Helper::UrgentStatus($job_order->repair->ticket->urgent_status);
                })
                ->editColumn('created_at', function ($job_order) {
                    return Carbon::parse($job_order->created_at)->translatedFormat('l\\, j F Y H:i');
                })
                ->addColumn('action', function ($job_order) {
                    return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('warehouse.show', $job_order->uuid) . '" title="Detail Module" href=""><i class="fal fa-search-plus"></i></a>
                    <a class="btn btn-primary btn-sm btn-icon waves-effect waves-themed" href="' . route('warehouse.edit', $job_order->uuid) . '" title="Progress ticket"><i class="fal fa-wrench"></i></a>';
                })
                ->removeColumn('uuid')
                ->rawColumns(['action', 'ticket_status', 'item_status', 'urgent_status'])
                ->make();
        }
        return view('warehouse.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $detail_item = WarehouseJobOrder::uuid($uuid);
        return view('warehouse.show', compact('detail_item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {
        $accessories = Accessory::all();
        $job_order = WarehouseJobOrder::uuid($uuid);
        $module_stock = ModuleStock::where('module_type_uuid', $job_order->repair->module_type_uuid)->where('available', '>', 0)->get();
        $module_stock_input = ModuleStock::where('module_type_uuid', $job_order->repair->module_type_uuid)->get();
        // dd($job_order);
        return view('warehouse.edit', compact('job_order', 'module_stock', 'module_stock_input', 'accessories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        // dd($request->all());
        $warehouse_job = WarehouseJobOrder::uuid($uuid);
        // get repair item detail
        $repair_item = RepairItem::where('uuid', $warehouse_job->repair_item_uuid)->first();

        // item ready to send to customer
        if ($request->item_status == 9) {
            $rules = [
                'warehouse_notes' => 'required',
                'resi_image' => 'required|mimes:img,png,jpg,jpeg|max:2048'
            ];

            $messages = [
                '*.required' => 'Field tidak boleh kosong !',
                '*.mimes' => 'Format gambar harus img,png,jpg,jpeg',
                '*.max' => 'Ukuran gambar maximal 2MB',
            ];
            $this->validate($request, $rules, $messages);
            DB::beginTransaction();
            try {
                if ($request->hasFile('resi_image')) {
                    $folder = public_path() . '/img' . '/resi' . '/';
                    if (!File::exists($folder)) {
                        File::makeDirectory($folder, 0755, true, true);
                    }
                    // request image files
                    $resi_image = $request->file('resi_image');
                    //upload image file
                    $filename = md5(uniqid(mt_rand(), true)) . '.' . $resi_image->getClientOriginalExtension();
                    $fitImage = Image::make($resi_image);
                    $fitImage->save($folder . $filename);
                    $request['avatar'] = $filename;
                    $warehouse_job->resi_image = $filename;
                }
                // save warehouse job order
                $warehouse_job->item_status = 9; // item sent to customer
                $warehouse_job->job_status = 1; // close warehouse job
                $warehouse_job->notes = $request->warehouse_notes;
                $warehouse_job->edited_by = Auth::user()->uuid;
                $warehouse_job->save();
                // update ticket
                $ticket = Ticketing::where('uuid', $repair_item->ticket->uuid)->first();
                $ticket->ticket_status = 3; // close ticket
                $ticket->item_status = 9; // sent to customer
                $ticket->edited_by = Auth::user()->uuid;
                $ticket->save();
            } catch (Exception $e) {
                // catch error and rollback database update
                DB::rollback();
                toastr()->error($e->getMessage(), 'Error');
                return redirect()->back()->withInput();
            }
            // now is save to commit update and redirect to index
            DB::commit();
            toastr()->success('Ticket No.' . $repair_item->ticket->ticket_number . ' Telah di progress', 'Success');
            return redirect()->route('warehouse.index');
        }
        // Replace for warranty claim
        if ($request->warranty_status == 4) {
            $rules = [
                'warehouse_notes' => 'required',
                'replace_status' => 'required'
            ];

            $messages = [
                '*.required' => 'Field tidak boleh kosong !',
            ];
            $this->validate($request, $rules, $messages);
            // replace from stock
            if ($request->replace_status == 3) {
                $rules = [
                    'module_type' => 'required',
                    'part_number' => 'required',
                    'serial_number' => 'required',
                    'serial_number_msc' => 'required',
                    'accessories' => 'required|array|min:1',
                    'warehouse_notes' => 'required',
                ];

                $messages = [
                    'accessories.required' => 'Pilih minimal 1',
                    '*.required' => 'Field tidak boleh kosong',
                ];
                $this->validate($request, $rules, $messages);
                DB::beginTransaction();
                try {
                    // save module replace data first
                    $module_replace = new ItemReplaceDetail();
                    $module_replace->replace_status = $request->replace_status;
                    $module_replace->item_repair_uuid = $repair_item->uuid;
                    $module_replace->module_type_uuid =  $request->module_type;
                    $module_replace->part_number = $request->part_number;
                    $module_replace->serial_number = $request->serial_number;
                    $module_replace->serial_number_msc = $request->serial_number_msc;
                    $module_replace->accessories = $request['accessories'];
                    $module_replace->created_by = Auth::user()->uuid;
                    $module_replace->save();
                    // substract stock
                    $module_stock = ModuleStock::where('module_type_uuid', $request->module_type)->first();
                    $module_stock->available = $module_stock->available - 1;
                    $module_stock->edited_by = Auth::user()->uuid;
                    $module_stock->save();
                    // update repair item info
                    $repair_item->status = $request->replace_status;
                    $repair_item->edited_by = Auth::user()->uuid;
                    $repair_item->save();
                    // update warehouse job order
                    $warehouse_job->item_status = 6; // replace module
                    $warehouse_job->notes = $request->warehouse_notes;
                    $warehouse_job->edited_by = Auth::user()->uuid;
                    $warehouse_job->save();
                    // update ticket
                    $ticket = Ticketing::where('uuid', $repair_item->ticket->uuid)->first();
                    $ticket->item_status = 6; // replace module
                    $ticket->edited_by = Auth::user()->uuid;
                    $ticket->save();
                } catch (Exception $e) {
                    // catch error and rollback database update
                    DB::rollback();
                    toastr()->error($e->getMessage(), 'Error');
                    return redirect()->back()->withInput();
                }
                // now is save to commit update and redirect to index
                DB::commit();
                toastr()->success('Ticket No.' . $repair_item->ticket->ticket_number . ' Telah di progress', 'Success');
                return redirect()->route('warehouse.index');
            }
            // waiting for claim from vendor
            if ($request->replace_status == 4) {
                $rules = [
                    'warehouse_notes' => 'required',
                ];
                $messages = [
                    '*.required' => 'Field tidak boleh kosong !',
                ];
                $this->validate($request, $rules, $messages);
                DB::beginTransaction();
                try {
                    // update warehouse job order
                    $warehouse_job->item_status = 5; // claim warranty to vendor
                    $warehouse_job->notes = $request->warehouse_notes;
                    $warehouse_job->edited_by = Auth::user()->uuid;
                    $warehouse_job->save();
                    // update ticket
                    $ticket = Ticketing::where('uuid', $repair_item->ticket->uuid)->first();
                    $ticket->item_status = 5; // claim warranty to vendor
                    $ticket->edited_by = Auth::user()->uuid;
                    $ticket->save();
                } catch (Exception $e) {
                    // catch error and rollback database update
                    DB::rollback();
                    toastr()->error($e->getMessage(), 'Error');
                    return redirect()->back()->withInput();
                }
                // now is save to commit update and redirect to index
                DB::commit();
                toastr()->success('Ticket No.' . $repair_item->ticket->ticket_number . ' Telah di progress', 'Success');
                return redirect()->route('warehouse.index');
            }
        }
        // warranty claim from vendor
        if ($request->item_status == 5) {
            $rules = [
                'module_type' => 'required',
                'vendor_name' => 'required',
                'part_number' => 'required',
                'serial_number' => 'required',
                'serial_number_msc' => 'required',
                'accessories' => 'required|array|min:1',
                'warehouse_notes' => 'required',
            ];
            $messages = [
                'accessories.required' => 'Pilih minimal 1',
                '*.required' => 'Field tidak boleh kosong',
            ];
            $this->validate($request, $rules, $messages);
            DB::beginTransaction();
            try {
                // save module replace data first
                $module_replace = new ItemReplaceDetail();
                $module_replace->item_repair_uuid = $repair_item->uuid;
                $module_replace->replace_status = $request->replace_status;
                $module_replace->vendor_name = $request->vendor_name;
                $module_replace->module_type_uuid =  $request->module_type;
                $module_replace->part_number = $request->part_number;
                $module_replace->serial_number = $request->serial_number;
                $module_replace->serial_number_msc = $request->serial_number_msc;
                $module_replace->accessories = $request['accessories'];
                $module_replace->created_by = Auth::user()->uuid;
                $module_replace->save();
                // update repair item info
                $repair_item->status = $request->replace_status;
                $repair_item->edited_by = Auth::user()->uuid;
                $repair_item->save();
                // update warehouse job order
                $warehouse_job->item_status = 6; // replace module
                $warehouse_job->notes = $request->warehouse_notes;
                $warehouse_job->edited_by = Auth::user()->uuid;
                $warehouse_job->save();
                // update ticket
                $ticket = Ticketing::where('uuid', $repair_item->ticket->uuid)->first();
                $ticket->item_status = 6; // replace module
                $ticket->edited_by = Auth::user()->uuid;
                $ticket->save();
            } catch (Exception $e) {
                // catch error and rollback database update
                DB::rollback();
                toastr()->error($e->getMessage(), 'Error');
                return redirect()->back()->withInput();
            }
            // now is save to commit update and redirect to index
            DB::commit();
            toastr()->success('Ticket No.' . $repair_item->ticket->ticket_number . ' Telah di progress', 'Success');
            return redirect()->route('warehouse.index');
        }

        // Progress to vendor for repair
        if ($request->item_status == 7) {
            $rules = [
                'warehouse_notes' => 'required',
            ];
            $messages = [
                '*.required' => 'Field tidak boleh kosong !',
            ];
            $this->validate($request, $rules, $messages);
            DB::beginTransaction();
            try {
                // update warehouse job order
                $warehouse_job->item_status = 7; // progress to vendor
                $warehouse_job->notes = $request->warehouse_notes;
                $warehouse_job->edited_by = Auth::user()->uuid;
                $warehouse_job->save();
                // if not urgent update ticket
                if ($warehouse_job->urgent_status == 0) {
                    $ticket = Ticketing::where('uuid', $repair_item->ticket->uuid)->first();
                    $ticket->item_status = 7; // progress to vendor
                    $ticket->edited_by = Auth::user()->uuid;
                    $ticket->save();
                }
            } catch (Exception $e) {
                // catch error and rollback database update
                DB::rollback();
                toastr()->error($e->getMessage(), 'Error');
                return redirect()->back()->withInput();
            }
            // now is save to commit update and redirect to index
            DB::commit();
            toastr()->success('Ticket No.' . $repair_item->ticket->ticket_number . ' Telah di progress', 'Success');
            return redirect()->route('warehouse.index');
        }
        // progress by vendor is done
        if ($request->item_status == 8) {
            $rules = [
                'vendor_status' => 'required',
                'warehouse_notes' => 'required',
                'vendor_name' => 'required',
            ];
            $messages = [
                '*.required' => 'Field tidak boleh kosong !',
            ];
            $this->validate($request, $rules, $messages);
            // repair by vendor
            if ($request->vendor_status == 1) {
                DB::beginTransaction();
                try {
                    // update warehouse job order
                    $warehouse_job->item_status = 8; // progress to vendor is done
                    if ($warehouse_job->urgent_status == 1) {
                        $warehouse_job->stock_input = 1;
                    }
                    $warehouse_job->notes = $request->warehouse_notes;
                    $warehouse_job->edited_by = Auth::user()->uuid;
                    $warehouse_job->save();
                    // insert repair by vendor detail
                    $repair_vendor = new RepairJobVendor();
                    $repair_vendor->vendor_name = $request->vendor_name;
                    $repair_vendor->repair_item_uuid = $repair_item->uuid;
                    $repair_vendor->repair_notes = $request->repair_notes;
                    $repair_vendor->created_by = Auth::user()->uuid;
                    $repair_vendor->save();
                    if ($warehouse_job->urgent_status == 0) {
                        // update repair item info
                        $repair_item->status = 2; // repair from vendor
                        $repair_item->edited_by = Auth::user()->uuid;
                        $repair_item->save();
                        // update ticket if not urgent
                        $ticket = Ticketing::where('uuid', $repair_item->ticket->uuid)->first();
                        $ticket->item_status = 8; // progress to vendor is done
                        $ticket->edited_by = Auth::user()->uuid;
                        $ticket->save();
                    }
                } catch (Exception $e) {
                    // catch error and rollback database update
                    DB::rollback();
                    toastr()->error($e->getMessage(), 'Error');
                    return redirect()->back()->withInput();
                }
                // now is save to commit update and redirect to index
                DB::commit();
                toastr()->success('Ticket No.' . $repair_item->ticket->ticket_number . ' Telah di progress', 'Success');
                return redirect()->route('warehouse.index');
            }
            // replace by vendor
            if ($request->vendor_status == 2) {
                $rules = [
                    'module_type' => 'required',
                    'vendor_name' => 'required',
                    'part_number' => 'required',
                    'serial_number' => 'required',
                    'serial_number_msc' => 'required',
                    'accessories' => 'required|array|min:1',
                    'warehouse_notes' => 'required',
                ];
                $messages = [
                    'accessories.required' => 'Pilih minimal 1',
                    '*.required' => 'Field tidak boleh kosong',
                ];
                $this->validate($request, $rules, $messages);
                DB::beginTransaction();
                try {
                    // update warehouse job order
                    if ($warehouse_job->urgent_status == 1) {
                        $warehouse_job->stock_input = 1;
                    }
                    $warehouse_job->item_status = 6; // replace module
                    $warehouse_job->notes = $request->warehouse_notes;
                    $warehouse_job->edited_by = Auth::user()->uuid;
                    $warehouse_job->save();

                    // if not urgent update item replace detail
                    // and update ticket
                    if ($warehouse_job->urgent_status == 0) {
                        // save module replace data
                        $module_replace = new ItemReplaceDetail();
                        $module_replace->item_repair_uuid = $repair_item->uuid;
                        $module_replace->replace_status = 4; // replace by vendor
                        $module_replace->vendor_name = $request->vendor_name;
                        $module_replace->module_type_uuid =  $request->module_type;
                        $module_replace->part_number = $request->part_number;
                        $module_replace->serial_number = $request->serial_number;
                        $module_replace->serial_number_msc = $request->serial_number_msc;
                        $module_replace->accessories = $request['accessories'];
                        $module_replace->created_by = Auth::user()->uuid;
                        $module_replace->save();

                        // update repair item info
                        $repair_item->status = 4; // replace from vendor
                        $repair_item->edited_by = Auth::user()->uuid;
                        $repair_item->save();
                        $ticket = Ticketing::where('uuid', $repair_item->ticket->uuid)->first();
                        $ticket->item_status = 6; // replace module
                        $ticket->edited_by = Auth::user()->uuid;
                        $ticket->save();
                    }
                } catch (Exception $e) {
                    // catch error and rollback database update
                    DB::rollback();
                    toastr()->error($e->getMessage(), 'Error');
                    return redirect()->back()->withInput();
                }
                // now is save to commit update and redirect to index
                DB::commit();
                toastr()->success('Ticket No.' . $repair_item->ticket->ticket_number . ' Telah di progress', 'Success');
                return redirect()->route('warehouse.index');
            }
        }
        // replace immediately for urgent
        if ($request->item_status == 10) {
            $rules = [
                'module_type' => 'required',
                'part_number' => 'required',
                'serial_number' => 'required',
                'serial_number_msc' => 'required',
                'accessories' => 'required|array|min:1',
                'warehouse_notes' => 'required',
            ];

            $messages = [
                'accessories.required' => 'Pilih minimal 1',
                '*.required' => 'Field tidak boleh kosong',
            ];
            $this->validate($request, $rules, $messages);
            DB::beginTransaction();
            try {
                // save module replace data first
                $module_replace = new ItemReplaceDetail();
                $module_replace->replace_status = $request->replace_status;
                $module_replace->item_repair_uuid = $repair_item->uuid;
                $module_replace->module_type_uuid =  $request->module_type;
                $module_replace->part_number = $request->part_number;
                $module_replace->serial_number = $request->serial_number;
                $module_replace->serial_number_msc = $request->serial_number_msc;
                $module_replace->accessories = $request['accessories'];
                $module_replace->created_by = Auth::user()->uuid;
                $module_replace->save();
                // substract stock
                $module_stock = ModuleStock::where('module_type_uuid', $request->module_type)->first();
                $module_stock->available = $module_stock->available - 1;
                $module_stock->edited_by = Auth::user()->uuid;
                $module_stock->save();
                // update repair item info
                $repair_item->status = $request->replace_status;
                $repair_item->edited_by = Auth::user()->uuid;
                $repair_item->save();
                // update warehouse job order
                $warehouse_job->item_status = 6; // replace module
                $warehouse_job->notes = $request->warehouse_notes;
                $warehouse_job->edited_by = Auth::user()->uuid;
                $warehouse_job->save();
                // update ticket
                $ticket = Ticketing::where('uuid', $repair_item->ticket->uuid)->first();
                $ticket->item_status = 6; // replace module
                $ticket->edited_by = Auth::user()->uuid;
                $ticket->save();
            } catch (Exception $e) {
                // catch error and rollback database update
                DB::rollback();
                toastr()->error($e->getMessage(), 'Error');
                return redirect()->back()->withInput();
            }
            // now is save to commit update and redirect to index
            DB::commit();
            toastr()->success('Ticket No.' . $repair_item->ticket->ticket_number . ' Telah di progress', 'Success');
            return redirect()->route('warehouse.index');
        }
        // input repaired module to stock
        if ($request->item_status == 11) {
            $rules = [
                'warehouse_notes' => 'required',
            ];

            $messages = [
                '*.required' => 'Field tidak boleh kosong',
            ];
            $this->validate($request, $rules, $messages);
            DB::beginTransaction();
            try {
                // Update stock
                $module_stock = ModuleStock::where('module_type_uuid', $warehouse_job->repair->ModuleType->uuid)->first();
                $module_stock->available = $module_stock->available + 1;
                $module_stock->edited_by = Auth::user()->uuid;
                $module_stock->save();
                // save warehouse job order
                $warehouse_job->item_status = 11; // item input to stock
                $warehouse_job->job_status = 1; // close warehouse job
                $warehouse_job->notes = $request->warehouse_notes;
                $warehouse_job->edited_by = Auth::user()->uuid;
                $warehouse_job->save();
            } catch (Exception $e) {
                // catch error and rollback database update
                DB::rollback();
                toastr()->error($e->getMessage(), 'Error');
                return redirect()->back()->withInput();
            }
            // now is save to commit update and redirect to index
            DB::commit();
            toastr()->success('Data berhasil diinput', 'Success');
            return redirect()->route('warehouse.index');
        }
    }

    public function history()
    {
        if (request()->ajax()) {
            $job_orders = WarehouseJobOrder::select('uuid', 'repair_item_uuid', 'item_status', 'job_status', 'created_at', 'updated_at')->where('job_status', 1)->get();

            return Datatables::of($job_orders)
                ->addIndexColumn()
                ->addColumn('ticket_number', function ($job_order) {
                    return  $job_order->repair->ticket->ticket_number;
                })
                ->addColumn('ticket_date', function ($job_order) {
                    return  Carbon::parse($job_order->repair->ticket->created_at)->translatedFormat('l\\, j M Y H:i');
                })
                ->addColumn('job_status', function ($job_order) {
                    return Helper::JobStatus($job_order->job_status);
                })
                ->editColumn('item_status', function ($job_order) {
                    return Helper::ItemStatus($job_order->item_status);
                })
                ->editColumn('created_at', function ($job_order) {
                    return  Carbon::parse($job_order->created_at)->translatedFormat('l\\, j M Y H:i');
                })
                ->editColumn('updated_at', function ($job_order) {
                    return  Carbon::parse($job_order->updated_at)->translatedFormat('l\\, j M Y H:i');
                })
                ->addColumn('action', function ($job_order) {
                    return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('warehouse.detail', $job_order->uuid) . '" title="Detail Module" href=""><i class="fal fa-search-plus"></i></a>';
                })
                ->removeColumn('uuid')
                ->rawColumns(['action', 'job_status', 'item_status', 'urgent_status'])
                ->make();
        }
        return view('warehouse.history');
    }
    public function detail($uuid)
    {
        $detail_item = WarehouseJobOrder::uuid($uuid);
        // dd($detail_item);
        return view('warehouse.detail', compact('detail_item'));
    }
}

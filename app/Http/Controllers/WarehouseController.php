<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\ItemReplaceStockDetail;
use App\Models\ItemReplaceVendorDetail;
use App\Models\ModuleStock;
use App\Models\RepairItem;
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
                    return  Carbon::parse($job_order->created_at)->translatedFormat('l\\, j F Y H:i');
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
        // dd($job_order);
        $module_stock = ModuleStock::where('module_type_uuid', $job_order->repair->module_type_uuid)->where('available', '>', 0)->get();
        return view('warehouse.edit', compact('job_order', 'module_stock', 'accessories'));
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

        // item repaired by tech and ready to send
        if ($request->item_status == 8) {
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
                $ticket->job_status = 9; // sent to customer
                $ticket->edited_by = Auth::user()->uuid;
                $ticket->save();
            } catch (Exception $e) {
                // catch error and rollback database update
                DB::rollback();
                toastr()->error('Gagal menyimpan data, silahkan coba lagi', 'Error');
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
            if ($request->replace_status == 1) {
                $rules = [
                    'module_type' => 'required',
                    'part_number' => 'required',
                    'serial_number' => 'required',
                    'serial_number_msc' => 'required',
                    'accessories' => 'required|array|min:1',
                ];

                $messages = [
                    'accessories.required' => 'Pilih minimal 1',
                    '*.required' => 'Field tidak boleh kosong',
                ];
                $this->validate($request, $rules, $messages);
                DB::beginTransaction();
                try {
                    // save module replace data first
                    $module_replace = new ItemReplaceStockDetail();
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
                    $repair_item->replace_status = $request->replace_status;
                    $repair_item->item_replace_uuid = $module_replace->uuid;
                    $repair_item->edited_by = Auth::user()->uuid;
                    $repair_item->save();
                    // update warehouse job order
                    $warehouse_job->item_status = 6; // replace module from stock
                    $warehouse_job->notes = $request->warehouse_notes;
                    $warehouse_job->edited_by = Auth::user()->uuid;
                    $warehouse_job->save();
                    // update ticket
                    $ticket = Ticketing::where('uuid', $repair_item->ticket->uuid)->first();
                    $ticket->job_status = 6; // replace module from stock
                    $ticket->edited_by = Auth::user()->uuid;
                    $ticket->save();
                } catch (Exception $e) {
                    // catch error and rollback database update
                    DB::rollback();
                    toastr()->error('Gagal menyimpan data, silahkan coba lagi', 'Error');
                    return redirect()->back()->withInput();
                }
                // now is save to commit update and redirect to index
                DB::commit();
                toastr()->success('Ticket No.' . $repair_item->ticket->ticket_number . ' Telah di progress', 'Success');
                return redirect()->route('warehouse.index');
            }
            // waiting for claim from vendor
            if ($request->replace_status == 2) {
                DB::beginTransaction();
                try {
                    // update warehouse job order
                    $warehouse_job->item_status = 5; // claim warranty to vendor
                    $warehouse_job->notes = $request->warehouse_notes;
                    $warehouse_job->edited_by = Auth::user()->uuid;
                    $warehouse_job->save();
                    // update ticket
                    $ticket = Ticketing::where('uuid', $repair_item->ticket->uuid)->first();
                    $ticket->job_status = 5; // claim warranty to vendor
                    $ticket->edited_by = Auth::user()->uuid;
                    $ticket->save();
                } catch (Exception $e) {
                    // catch error and rollback database update
                    DB::rollback();
                    toastr()->error('Gagal menyimpan data, silahkan coba lagi', 'Error');
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
            ];

            $messages = [
                'accessories.required' => 'Pilih minimal 1',
                '*.required' => 'Field tidak boleh kosong',
            ];
            $this->validate($request, $rules, $messages);
            DB::beginTransaction();
            try {
                // save module replace data first
                $module_replace = new ItemReplaceVendorDetail();
                $module_replace->item_repair_uuid = $repair_item->uuid;
                $module_replace->vendor_name = $request->vendor_name;
                $module_replace->module_type_uuid =  $request->module_type;
                $module_replace->part_number = $request->part_number;
                $module_replace->serial_number = $request->serial_number;
                $module_replace->serial_number_msc = $request->serial_number_msc;
                $module_replace->accessories = $request['accessories'];
                $module_replace->created_by = Auth::user()->uuid;
                $module_replace->save();
                // update repair item info
                $repair_item->replace_status = $request->replace_status;
                $repair_item->item_replace_uuid = $module_replace->uuid;
                $repair_item->edited_by = Auth::user()->uuid;
                $repair_item->save();
                // update warehouse job order
                $warehouse_job->item_status = 6; // replace module from stock
                $warehouse_job->notes = $request->warehouse_notes;
                $warehouse_job->edited_by = Auth::user()->uuid;
                $warehouse_job->save();
                // update ticket
                $ticket = Ticketing::where('uuid', $repair_item->ticket->uuid)->first();
                $ticket->job_status = 6; // replace module from stock
                $ticket->edited_by = Auth::user()->uuid;
                $ticket->save();
            } catch (Exception $e) {
                // catch error and rollback database update
                DB::rollback();
                toastr()->error('Gagal menyimpan data, silahkan coba lagi', 'Error');
                return redirect()->back()->withInput();
            }
            // now is save to commit update and redirect to index
            DB::commit();
            toastr()->success('Ticket No.' . $repair_item->ticket->ticket_number . ' Telah di progress', 'Success');
            return redirect()->route('warehouse.index');
        }
    }

    public function GetModuleStock()
    {
        if (request()->ajax()) {
            $module_stock = ModuleStock::where('module_type_uuid', request('module_type_uuid'))->where('available', '>', 0)->first();
            return response()->json($module_stock);
        }
    }
}

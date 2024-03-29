<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Material;
use App\Models\RepairItem;
use App\Models\Ticketing;
use App\Models\RepairJobOrder;
use App\Models\WarehouseJobOrder;
use App\Traits\Authorizable;
use Carbon\Carbon;
use Auth;
use DataTables;
use DB;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use URL;

class RepairJobController extends Controller
{
    use Authorizable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $user = Auth::user();
            $roles = $user->getRoleNames();
            if ($roles[0] == 'repair') {
                DB::statement(DB::raw('set @rownum=0'));
                $data = RepairJobOrder::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'uuid', 'repair_item_uuid', 'assign_at'])->where('assign_to', $user->uuid)->where('job_status', 0);
                return Datatables::of($data)
                    ->addColumn('ticket_number', function ($row) {
                        return $row->repair->ticket->ticket_number;
                    })
                    ->editColumn('assign_at', function ($row) {
                        return Carbon::parse($row->assign_at)->translatedFormat('l\\, j F Y H:i');
                    })
                    ->addColumn('action', function ($row) {
                        return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('repair-job.show', $row->uuid) . '" title="Detail Barang" href=""><i class="fal fa-search-plus"></i></a>
                    <a class="btn btn-secondary btn-sm btn-icon waves-effect waves-themed" href="' . route('repair-job.edit', $row->uuid) . '" title="Progress"><i class="fal fa-wrench"></i></a>';
                    })
                    ->removeColumn('id')
                    ->removeColumn('uuid')
                    ->removeColumn('repair_item_uuid')
                    ->rawColumns(['action'])
                    ->make();
            }
            DB::statement(DB::raw('set @rownum=0'));
            $data = RepairJobOrder::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'uuid', 'repair_item_uuid', 'assign_at'])->where('job_status', 0);
            return Datatables::of($data)
                ->addColumn('ticket_number', function ($row) {
                    return $row->repair->ticket->ticket_number;
                })
                ->editColumn('assign_at', function ($row) {
                    return Carbon::parse($row->assign_at)->translatedFormat('l\\, j F Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('repair-job.show', $row->uuid) . '" title="Detail Barang" href=""><i class="fal fa-search-plus"></i></a>
                    <a class="btn btn-secondary btn-sm btn-icon waves-effect waves-themed" href="' . route('repair-job.edit', $row->uuid) . '" title="Progress"><i class="fal fa-wrench"></i></a>';
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->removeColumn('repair_item_uuid')
                ->rawColumns(['action'])
                ->make();
        }
        return view('repair-job.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $repair_job = RepairJobOrder::uuid($uuid);
        return view('repair-job.show', compact('repair_job'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {
        $repair_job = RepairJobOrder::uuid($uuid);
        $materials = Material::where('available', '>', 0)->get();
        return view('repair-job.edit', compact('repair_job', 'materials'));
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
        $rules = [
            'repair_status' => 'required',
            'complain' => 'required',
            'repair_notes' => 'required',
        ];
        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
        ];
        $this->validate($request, $rules, $messages);

        // update repair job first
        $repair_job = RepairJobOrder::uuid($uuid);
        $job_created = Carbon::parse($repair_job->assign_at)->toDateTimeString();
        $job_finish = Carbon::parse()->toDateTimeString();
        $time_to_repair = Helper::CountRepairTime($job_created, $job_finish);

        // dd($job_created, $job_finish, $time_to_repair);

        // item can repair
        if ($request->repair_status == 1) {
            $rules = [
                'using_material' => 'required',
            ];
            $messages = [
                '*.required' => 'Field tidak boleh kosong !',
            ];
            $this->validate($request, $rules, $messages);

            // item using material
            if ($request->using_material == 1) {
                $rules = [
                    'material' => 'bail|required|array',
                    'material.*' => 'bail|required|distinct',
                    'material_amount' => 'bail|required|array',
                    'material_amount.*' => 'required|numeric',

                ];
                $messages = [
                    '*.required' => 'Field tidak boleh kosong !',
                    '*.array' => 'Must be an array',
                    'material.*.required' => 'Tidak boleh kosong',
                    'material.*.distinct' => 'Material tidak boleh sama, isi kolom berikut jika menggunakan lebih dari satu',
                    'material_amount.*.required' => 'Tidak boleh kosong',
                    'material_amount.*.numeric' => 'Isian harus angka',
                ];
                $this->validate($request, $rules, $messages);
                DB::beginTransaction();
                try {
                    $material = [];
                    $component_used = array_combine($request->material, $request->material_amount);
                    // manually remaping material and amount array
                    for ($i = 0; $i < count($component_used); $i++) {
                        $component = Material::select('unit_price', 'available')
                            ->where('uuid', array_keys($component_used)[$i])->first();
                        // throw validation messages if used greater than availlble stock
                        if (array_values($component_used)[$i] > $component->available) {
                            throw ValidationException::withMessages(['material_amount.*' => 'Penggunaan tidak bisa melebihi stock']);
                        }
                        $material[$i]['uuid'] = array_keys($component_used)[$i];
                        $material[$i]['unit_price'] = $component->unit_price;
                        $material[$i]['amount_used'] = (int)array_values($component_used)[$i];
                        $material[$i]['total_price'] = $component->unit_price * array_values($component_used)[$i];
                    }
                    // get total cost
                    $total_cost = array_sum(array_map(function ($item) {
                        return $item['total_price'];
                    }, $material));
                    // update repair job
                    $repair_job->item_status = 1; // repair by tech
                    $repair_job->job_status = 1; // close job
                    $repair_job->repair_notes = strtoupper($request->repair_notes);
                    $repair_job->component_used = $material;
                    $repair_job->repair_cost = $total_cost;
                    $repair_job->time_to_repair = $time_to_repair;
                    $repair_job->edited_by = Auth::user()->uuid;
                    $repair_job->save();
                    // substract material stock
                    foreach ($component_used as $key => $value) {
                        $material = Material::uuid($key);
                        $material->available = $material->available - $value;
                        $material->save();
                    }
                    // update repair item status 
                    $repair_item = RepairItem::where('uuid', $repair_job->repair_item_uuid)->first();
                    $repair_item->complain = strtoupper($request->complain);
                    $repair_item->status = 1; // repair by tech
                    $repair_item->edited_by = Auth::user()->uuid;
                    $repair_item->save();
                    // create job order for warehouse
                    $warehouse_job = new WarehouseJobOrder();
                    $warehouse_job->repair_item_uuid = $repair_item->uuid;
                    $warehouse_job->urgent_status = $repair_job->urgent_status;
                    $warehouse_job->item_status = 2; // repaired by tech
                    $warehouse_job->job_status = 0; // open job order for warehouse
                    /**
                     * Detect urgent status first
                     * For warehouse decide item sent to customer or input to stock
                     */
                    if ($repair_job->urgent_status == 1) {
                        $warehouse_job->stock_input = 1;
                    }
                    $warehouse_job->stock_input == 0;
                    $warehouse_job->created_by = Auth::user()->uuid;
                    $warehouse_job->save();
                    // update ticketing if urgent status 0
                    if ($repair_job->urgent_status == 0) {
                        $ticket = Ticketing::where('uuid', $repair_item->ticket_uuid)->first();
                        $ticket->ticket_status = 2; // send to warehouse
                        $ticket->item_status = 2; // repaired by tech
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
                toastr()->success('Ticket No.' . $repair_job->repair->ticket->ticket_number . ' Telah di progress', 'Success');
                return redirect()->route('repair-job.index');
            }
            // not using material
            if ($request->using_material == 0) {
                DB::beginTransaction();
                try {
                    // update repair job
                    $repair_job->item_status = 1; // repair by tech
                    $repair_job->job_status = 1; // close job
                    $repair_job->repair_notes = strtoupper($request->repair_notes);
                    $repair_job->time_to_repair = $time_to_repair;
                    $repair_job->edited_by = Auth::user()->uuid;
                    $repair_job->save();
                    // update repair item status 
                    $repair_item = RepairItem::where('uuid', $repair_job->repair_item_uuid)->first();
                    $repair_item->complain = strtoupper($request->complain);
                    // if not urgent update item status
                    if ($repair_job->urgent_status == 0) {
                        $repair_item->status = 1; // repair by tech
                    }
                    $repair_item->edited_by = Auth::user()->uuid;
                    $repair_item->save();
                    // create job order for warehouse
                    $warehouse_job = new WarehouseJobOrder();
                    $warehouse_job->repair_item_uuid = $repair_item->uuid;
                    $warehouse_job->urgent_status = $repair_job->urgent_status;
                    $warehouse_job->item_status = 2; // repaired by tech
                    $warehouse_job->job_status = 0; // open job order for warehouse
                    /**
                     * Detect urgent status
                     * For warehouse decide item sent to customer or input to stock
                     */
                    if ($repair_job->urgent_status == 1) {
                        $warehouse_job->stock_input = 1;
                    }
                    $warehouse_job->stock_input == 0;
                    $warehouse_job->created_by = Auth::user()->uuid;
                    $warehouse_job->save();
                    // update ticketing if urgent status 0
                    if ($repair_job->urgent_status == 0) {
                        $ticket = Ticketing::where('uuid', $repair_item->ticket_uuid)->first();
                        $ticket->ticket_status = 2; // send to warehouse
                        $ticket->item_status = 2; // repair by tech
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
                toastr()->success('Ticket No.' . $repair_job->repair->ticket->ticket_number . ' Telah di progress', 'Success');
                return redirect()->route('repair-job.index');
            }
        }
        // item not repaired
        if ($request->repair_status == 0) {
            DB::beginTransaction();
            try {
                // update repair job
                $repair_job->item_status = 0; // can't repair by tech
                $repair_job->job_status = 1; // close job
                $repair_job->repair_notes = strtoupper($request->repair_notes);
                $repair_job->time_to_repair = $time_to_repair;
                $repair_job->edited_by = Auth::user()->uuid;
                $repair_job->save();
                // update repair item status 
                $repair_item = RepairItem::where('uuid', $repair_job->repair_item_uuid)->first();
                $repair_item->complain = strtoupper($request->complain);
                if ($repair_job->urgent_status == 0) {
                    $repair_item->status = 0; // item can't repair
                }
                $repair_item->edited_by = Auth::user()->uuid;
                $repair_item->save();
                // create job order for warehouse
                $warehouse_job = new WarehouseJobOrder();
                $warehouse_job->repair_item_uuid = $repair_item->uuid;
                $warehouse_job->urgent_status = $repair_job->urgent_status;
                $warehouse_job->item_status = 3; // can't repaired by tech
                $warehouse_job->job_status = 0; // open job order for warehouse
                $warehouse_job->created_by = Auth::user()->uuid;
                $warehouse_job->save();
                // update ticketing if urgent status 0
                if ($repair_job->urgent_status == 0) {
                    $ticket = Ticketing::where('uuid', $repair_item->ticket_uuid)->first();
                    $ticket->ticket_status = 2; // send to warehouse
                    $ticket->item_status = 3; // can't repaired by tech
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
            toastr()->success('Ticket No.' . $repair_job->repair->ticket->ticket_number . ' Telah di progress', 'Success');
            return redirect()->route('repair-job.index');
        }
    }

    /**
     * Display a listing of tech job order.
     *
     * @return \Illuminate\Http\Response
     */
    public function history()
    {
        if (request()->ajax()) {
            $repair_jobs = RepairJobOrder::select(
                'id',
                'uuid',
                'repair_item_uuid',
                'item_status',
                'job_status',
                'time_to_repair',
                'created_at',
                'updated_at',
            )->where('assign_to', Auth::user()->uuid)->where('job_status', 1)->latest()->get();
            return Datatables::of($repair_jobs)
                ->addIndexColumn()
                ->addColumn('ticket_number', function ($repair_job) {
                    return $repair_job->repair->ticket->ticket_number;
                })
                ->addColumn('repair_status', function ($repair_job) {
                    return Helper::RepairJobItemStatus($repair_job->item_status);
                })
                ->editColumn('job_status', function ($repair_job) {
                    return Helper::JobStatus($repair_job->job_status);
                })
                ->editColumn('time_to_repair', function ($repair_job) {
                    return floor($repair_job->time_to_repair / 60) .
                        ' ' . 'Hours' . ' ' . ($repair_job->time_to_repair % 60) . ' ' . 'Minutes';
                })
                ->editColumn('created_at', function ($repair_job) {
                    return Carbon::parse($repair_job->created_at)->translatedFormat('j M Y H:i');
                })
                ->editColumn('updated_at', function ($repair_job) {
                    return Carbon::parse($repair_job->updated_at)->translatedFormat('j M Y H:i');
                })
                ->addColumn('action', function ($repair_job) {
                    return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('repair-job.detail', $repair_job->uuid) . '" title="Detail Task" href=""><i class="fal fa-search-plus"></i></a>';
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->removeColumn('repair_item_uuid')
                ->rawColumns(['repair_status', 'job_status', 'action'])
                ->make();
        }
        return view('repair-job.history');
    }

    /**
     * Display repair job detail
     *
     * @param string $uuid
     * @return \Illuminate\Http\Response
     */
    public function detail($uuid)
    {
        $materials = [];
        $repair_job = RepairJobOrder::uuid($uuid);
        // check if there's component used
        if (!empty($repair_job->component_used)) {
            // loop component used array to get material name
            for ($i = 0; $i < count($repair_job->component_used); $i++) {
                $component = Material::select('material_type')
                    ->where('uuid', $repair_job->component_used[$i]['uuid'])->first();

                $materials[$i]['uuid'] = $repair_job->component_used[$i]['uuid'];
                $materials[$i]['material_type'] = $component->material_type;
                $materials[$i]['unit_price'] = $repair_job->component_used[$i]['unit_price'];
                $materials[$i]['amount_used'] = $repair_job->component_used[$i]['amount_used'];
                $materials[$i]['total_price'] = $repair_job->component_used[$i]['total_price'];
            }
        }
        return view('repair-job.detail', compact('repair_job', 'materials'));
    }
}

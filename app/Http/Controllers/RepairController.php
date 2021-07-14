<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\RepairJobOrder;
use App\Models\Ticketing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Auth;
use DataTables;
use DB;
use Exception;
use Helper;
use URL;

class RepairController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $repairs = RepairJobOrder::select('uuid', 'repair_item_uuid', 'item_status', 'job_status', 'assign_to', 'assign_at')->where('job_status', 0)->get();

            return Datatables::of($repairs)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->repair->ticket->created_at)->translatedFormat('l\\, j F Y H:i');
                })
                ->editColumn('ticket_number', function ($row) {
                    return $row->repair->ticket->ticket_number;
                })
                ->editColumn('job_status', function ($row) {
                    return Helper::JobStatus($row->job_status);
                })
                ->editColumn('urgent_status', function ($row) {
                    return Helper::UrgentStatus($row->repair->ticket->urgent_status);
                })
                ->addColumn('assign_to', function ($row) {
                    if (!empty($row->assign_to)) {
                        return $row->UserAssign->name;
                    }
                    return '<span class="badge badge-secondary">None</span>';
                })
                ->addColumn('assign_date', function ($row) {
                    if (!empty($row->assign_to)) {
                        return Carbon::parse($row->assign_at)->translatedFormat('l\\, j F Y H:i');
                    }
                    return '<span class="badge badge-secondary">None</span>';
                })
                ->addColumn('action', function ($row) {
                    // assign button only show where none tech were assign
                    if (!empty($row->assign_to)) {
                        return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('repair.show', $row->uuid) . '" title="Detail Module" href=""><i class="fal fa-search-plus"></i></a>';
                    }
                    return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('repair.show', $row->uuid) . '" title="Detail Module" href=""><i class="fal fa-search-plus"></i></a>
                    <a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="' . route('repair.edit', $row->uuid) . '" title="Progress Tiket Ke Teknisi"><i class="fal fa-wrench"></i></a>';
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->rawColumns(['action', 'ticket_number', 'job_status', 'urgent_status', 'assign_to', 'assign_date'])
                ->make();
        }
        return view('repair.index');
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
        return view('repair.show', compact('repair_job'));
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
        $techs = User::role('repair')->pluck('name', 'uuid');
        return view('repair.assign', compact('repair_job', 'techs'));
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
            'tech' => 'required',
        ];
        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
        ];
        $this->validate($request, $rules, $messages);
        // get detail repair item
        $repair_job_order = RepairJobOrder::uuid($uuid);
        // get detail teknisi
        $technician = User::uuid($request->tech);
        DB::beginTransaction();
        try {
            // save repair job order
            $repair_job_order->assign_to = $request->tech;
            $repair_job_order->assign_at = Carbon::parse()->toDateTimeString();
            $repair_job_order->edited_by = Auth::user()->uuid;
            $repair_job_order->save();

            if (isset($repair_job_order->urgent->status) && $repair_job_order->urgent->status == 0) {
                // update ticket status
                $ticket = Ticketing::uuid($repair_job_order->repair->ticket->uuid);
                $ticket->item_status = 1; // job status in tech progress
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
        toastr()->success('Ticket di assign ke ' . $technician->name, 'Success');
        return redirect()->route('repair.index');
    }

    /**
     * Display all job assign
     *
     * @return \Illuminate\Http\Response
     */
    public function AssignHistory()
    {
        if (request()->ajax()) {
            $repairs = RepairJobOrder::select('uuid', 'repair_item_uuid', 'item_status', 'job_status', 'assign_to', 'assign_at')->whereNotNull('assign_to')->get();

            return Datatables::of($repairs)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->repair->ticket->created_at)->translatedFormat('l\\, j F Y H:i');
                })
                ->editColumn('ticket_number', function ($row) {
                    return $row->repair->ticket->ticket_number;
                })
                ->editColumn('job_status', function ($row) {
                    return Helper::JobStatus($row->job_status);
                })
                ->editColumn('urgent_status', function ($row) {
                    return Helper::UrgentStatus($row->repair->ticket->urgent_status);
                })
                ->addColumn('assign_to', function ($row) {
                    if (!empty($row->assign_to)) {
                        return $row->UserAssign->name;
                    }
                    return '<span class="badge badge-secondary">None</span>';
                })
                ->addColumn('assign_date', function ($row) {
                    if (!empty($row->assign_to)) {
                        return Carbon::parse($row->assign_at)->translatedFormat('l\\, j F Y H:i');
                    }
                    return '<span class="badge badge-secondary">None</span>';
                })
                ->addColumn('action', function ($row) {
                    // assign button only show where none tech were assign
                    if (!empty($row->assign_to)) {
                        return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('repair.show', $row->uuid) . '" title="Detail Module" href=""><i class="fal fa-search-plus"></i></a>';
                    }
                    return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('repair.show', $row->uuid) . '" title="Detail Module" href=""><i class="fal fa-search-plus"></i></a>
                    <a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="' . route('repair.edit', $row->uuid) . '" title="Progress Tiket Ke Teknisi"><i class="fal fa-wrench"></i></a>';
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->rawColumns(['action', 'ticket_number', 'job_status', 'urgent_status', 'assign_to', 'assign_date'])
                ->make();
        }
        return view('repair.assign-history');
    }

    /**
     * Display a listing of tech job order history.
     *
     * @return \Illuminate\Http\Response
     */
    public function RepairHistory()
    {
        if (request()->ajax()) {
            $repair_jobs = RepairJobOrder::select(
                'id',
                'uuid',
                'repair_item_uuid',
                'item_status',
                'job_status',
                'time_to_repair',
                'assign_to',
                'assign_at',
                'updated_at',
            )->latest()->where('job_status', 1)->get();
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
                ->editColumn('assign_to', function ($repair_job) {
                    return $repair_job->UserAssign->name;
                })
                ->editColumn('assign_at', function ($repair_job) {
                    return Carbon::parse($repair_job->assign_at)->translatedFormat('j M Y H:i');
                })
                ->editColumn('updated_at', function ($repair_job) {
                    return Carbon::parse($repair_job->updated_at)->translatedFormat('j M Y H:i');
                })
                ->addColumn('action', function ($repair_job) {
                    return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('repair.job-detail', $repair_job->uuid) . '" title="Detail Task" href=""><i class="fal fa-search-plus"></i></a>';
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->removeColumn('repair_item_uuid')
                ->rawColumns(['repair_status', 'job_status', 'action'])
                ->make();
        }
        return view('repair.repair-history');
    }

    /**
     * Display og repair job
     *
     * @param string $uuid
     * @return \Illuminate\Http\Response
     */
    public function RepairDetail($uuid)
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
        return view('repair.repair-detail', compact('repair_job', 'materials'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\RepairItem;
use App\Models\RepairJobOrder;
use App\Models\Ticketing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Auth;
use DataTables;
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
            $tickets = Ticketing::select(
                'id',
                'uuid',
                'uuid_unit',
                'ticket_number',
                'ticket_status',
                'job_status',
                'urgent_status',
                'created_at',
            )->where('ticket_status', 1)->latest()->get();

            return Datatables::of($tickets)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->translatedFormat('l\\, j F Y H:i:s');
                })
                ->editColumn('ticket_status', function ($row) {
                    switch ($row->ticket_status) {
                        case 1:
                            return '<span class="badge badge-primary">Diproses ke bagian repair</span>';
                            break;
                        case 2:
                            return '<span class="badge badge-warning">Diproses ke bagian gudang</span>';
                            break;
                        case 3:
                            return '<span class="badge badge-success">Selesai</span>';
                            break;
                        case 4:
                            return '<span class="badge badge-danger">Cancel</span>';
                            break;
                        default:
                            return '<span class="badge badge-dark">Status Unknown</span>';
                            break;
                    }
                })
                ->editColumn('job_status', function ($row) {
                    switch ($row->job_status) {
                        case 0:
                            return '<span class="badge badge-secondary">None</span>';
                            break;
                        case 1:
                            return '<span class="badge badge-primary">Dalam penanganan oleh teknisi</span>';
                            break;
                        case 2:
                            return '<span class="badge badge-success">Telah diperbaiki oleh teknisi</span>';
                            break;
                        case 3:
                            return '<span class="badge badge-danger">Tidak dapat diperbaiki teknisi</span>';
                            break;
                        case 4:
                            return '<span class="badge badge-warning">Butuh klaim garansi</span>';
                            break;
                        case 5:
                            return '<span class="badge badge-warning">Butuh penggantian barang</span>';
                            break;
                        case 6:
                            return '<span class="badge badge-info">Dalam perbaikan oleh vendor</span>';
                            break;
                        case 7:
                            return '<span class="badge badge-info">Menunggu penggantian dari vendor</span>';
                            break;
                        case 8:
                            return '<span class="badge badge-success">Telah di kirim ke customer</span>';
                            break;
                        case 9:
                            return '<span class="badge badge-danger">Ticket di cancel</span>';
                            break;
                        default:
                            return '<span class="badge badge-dark">None</span>';
                            break;
                    }
                })
                ->editColumn('urgent_status', function ($row) {
                    switch ($row->urgent_status) {
                        case 0:
                            return '<span class="badge badge-success">Not Urgent</span>';
                            break;
                        case 1:
                            return '<span class="badge badge-danger">Urgent</span>';
                            break;
                        default:
                            return '<span class="badge badge-dark">Status Unknown</span>';
                            break;
                    }
                })
                ->addColumn('assign', function ($row) {
                    if (!empty($row->RepairItem->JobOrder->assign_to)) {
                        return $row->RepairItem->JobOrder->UserAssign->name;
                    }
                    return '<span class="badge badge-secondary">None</span>';
                })
                ->addColumn('assign_date', function ($row) {
                    if (!empty($row->RepairItem->JobOrder->assign_to)) {
                        return Carbon::parse($row->RepairItem->JobOrder->created_at)->translatedFormat('l\\, j F Y H:i:s');
                    }
                    return '<span class="badge badge-secondary">None</span>';
                })
                ->addColumn('action', function ($row) {
                    // assign button only show where none tech were assign
                    if (!empty($row->RepairItem->JobOrder->assign_to)) {
                        return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('repair.show', $row->uuid) . '" title="Detail Module" href=""><i class="fal fa-search-plus"></i></a>';
                    }
                    return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('repair.show', $row->uuid) . '" title="Detail Module" href=""><i class="fal fa-search-plus"></i></a>
                    <a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="' . route('repair.edit', $row->uuid) . '" title="Progress Tiket Ke Teknisi"><i class="fal fa-wrench"></i></a>';
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->rawColumns(['action', 'ticket_status', 'job_status', 'urgent_status', 'assign', 'assign_date'])
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
        $repair_item = RepairItem::where('ticket_uuid', $uuid)->first();
        return view('repair.show', compact('repair_item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {
        $repair_item = RepairItem::where('ticket_uuid', $uuid)->first();
        $techs = User::role('repair')->pluck('name', 'uuid');
        return view('repair.assign', compact('repair_item', 'techs'));
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
        // dd($request->all(), $uuid);
        $rules = [
            'tech' => 'required',
        ];
        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
        ];
        $this->validate($request, $rules, $messages);
        // get detail repair item
        $repair_item = RepairItem::uuid($uuid);
        // get detail teknisi
        $technician = User::uuid($request->tech);

        // save repair job order
        $repair_order = new RepairJobOrder();
        $repair_order->repair_item_uuid = $uuid;
        $repair_order->assign_to = $request->tech;
        $repair_order->job_status = 0; // job status is open
        $repair_order->created_by = Auth::user()->uuid;
        $repair_order->save();

        // update ticket status
        $ticket = Ticketing::uuid($repair_item->ticket_uuid);
        $ticket->job_status = 1; // job status in tech progress
        $ticket->edited_by = Auth::user()->uuid;
        $ticket->save();

        toastr()->success('Ticket No.' . $ticket->ticket_number . ' di assign ke ' . $technician->name, 'Success');
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
            $repairs = RepairJobOrder::select(
                'id',
                'uuid',
                'repair_item_uuid',
                'assign_to',
                'created_at'
            )->latest()->get();

            return Datatables::of($repairs)
                ->addIndexColumn()
                ->addColumn('ticket_number', function ($row) {
                    return $row->repair->ticket->ticket_number;
                })
                ->addColumn('ticket_date', function ($row) {
                    return Carbon::parse($row->repair->ticket->created_at)->translatedFormat('l\\, j F Y H:i:s');
                })
                ->addColumn('ticket_status', function ($row) {
                    switch ($row->repair->ticket->ticket_status) {
                        case 1:
                            return '<span class="badge badge-primary">Diproses ke bagian repair</span>';
                            break;
                        case 2:
                            return '<span class="badge badge-warning">Diproses ke bagian gudang</span>';
                            break;
                        case 3:
                            return '<span class="badge badge-success">Selesai</span>';
                            break;
                        case 4:
                            return '<span class="badge badge-danger">Cancel</span>';
                            break;
                        default:
                            return '<span class="badge badge-dark">Status Unknown</span>';
                            break;
                    }
                })
                ->addColumn('job_status', function ($row) {
                    switch ($row->repair->ticket->job_status) {
                        case 0:
                            return '<span class="badge badge-secondary">None</span>';
                            break;
                        case 1:
                            return '<span class="badge badge-primary">Dalam penanganan oleh teknisi</span>';
                            break;
                        case 2:
                            return '<span class="badge badge-success">Telah diperbaiki oleh teknisi</span>';
                            break;
                        case 3:
                            return '<span class="badge badge-danger">Tidak dapat diperbaiki teknisi</span>';
                            break;
                        case 4:
                            return '<span class="badge badge-warning">Butuh klaim garansi</span>';
                            break;
                        case 5:
                            return '<span class="badge badge-warning">Butuh penggantian barang</span>';
                            break;
                        case 6:
                            return '<span class="badge badge-info">Dalam perbaikan oleh vendor</span>';
                            break;
                        case 7:
                            return '<span class="badge badge-info">Menunggu penggantian dari vendor</span>';
                            break;
                        case 8:
                            return '<span class="badge badge-success">Telah di kirim ke customer</span>';
                            break;
                        case 9:
                            return '<span class="badge badge-danger">Ticket di cancel</span>';
                            break;
                        default:
                            return '<span class="badge badge-dark">None</span>';
                            break;
                    }
                })
                ->addColumn('urgent_status', function ($row) {
                    switch ($row->repair->ticket->urgent_status) {
                        case 0:
                            return '<span class="badge badge-success">Not Urgent</span>';
                            break;
                        case 1:
                            return '<span class="badge badge-danger">Urgent</span>';
                            break;
                        default:
                            return '<span class="badge badge-dark">Status Unknown</span>';
                            break;
                    }
                })
                ->addColumn('assign', function ($row) {
                    if (!empty($row->assign_to)) {
                        return $row->UserAssign->name;
                    }
                    return '<span class="badge badge-secondary">None</span>';
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->translatedFormat('l\\, j F Y H:i:s');
                })
                ->addColumn('action', function ($row) {
                    return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('repair.show', $row->repair->ticket_uuid) . '" title="Detail Module" href=""><i class="fal fa-search-plus"></i></a>';
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->rawColumns(['action', 'ticket_status', 'job_status', 'urgent_status', 'assign', 'assign_date'])
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
                'job_status',
                'time_to_repair',
                'assign_to',
                'created_at',
                'updated_at',
            )->latest()->where('job_status', 1)->get();
            return Datatables::of($repair_jobs)
                ->addIndexColumn()
                ->addColumn('ticket_number', function ($repair_job) {
                    return $repair_job->repair->ticket->ticket_number;
                })
                ->addColumn('repair_status', function ($repair_job) {
                    switch ($repair_job->repair->repair_status) {
                        case 0:
                            return '<span class="badge badge-danger">Non Repair</span>';
                            break;
                        case 1;
                            return '<span class="badge badge-success">Repaired</span>';
                        default:
                            return '<span class="badge badge-secondary">Unknown</span>';
                            break;
                    }
                })
                ->editColumn('job_status', function ($repair_job) {
                    switch ($repair_job->job_status) {
                        case 0:
                            return '<span class="badge badge-primary">Dalam proses</span>';
                            break;
                        case 1;
                            return '<span class="badge badge-success">Selesai</span>';
                            break;
                        case 2;
                            return '<span class="badge badge-danger">Ticket cancel</span>';
                            break;
                        default:
                            return '<span class="badge badge-dark">Status Unknown</span>';
                            break;
                    }
                })
                ->editColumn('time_to_repair', function ($repair_job) {
                    return number_format($repair_job->time_to_repair, 2) . ' ' . 'Hours';
                })
                ->editColumn('assign_to', function ($repair_job) {
                    return $repair_job->UserAssign->name;
                })
                ->editColumn('created_at', function ($repair_job) {
                    return Carbon::parse($repair_job->created_at)->translatedFormat('j M Y h:i:s');
                })
                ->editColumn('updated_at', function ($repair_job) {
                    return Carbon::parse($repair_job->updated_at)->translatedFormat('j M Y h:i:s');
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

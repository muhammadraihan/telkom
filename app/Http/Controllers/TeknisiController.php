<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Technician_job_order;
use App\Models\Ticketing;
use App\Models\Repair_item;
use App\Models\Gudang_job_order;

use Carbon\Carbon;
use Auth;
use DataTables;
use DB;
use URL;

class TeknisiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            DB::statement(DB::raw('set @rownum=0'));
            $data = Repair_item::select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'id', 'uuid', 'ticket_uuid', 'can_repair'
            ])
                ->where('status_garansi', '=', '0')
                ->whereHas('ticket', function (Builder $query) {
                    $query->where('ticket_status', '!=', 2);
                });

            return Datatables::of($data)
                ->addColumn('ticket_number', function ($row) {
                    return $row->ticket->ticket_number;
                })
                ->addColumn('ticket_status', function ($row) {
                    switch ($row->ticket->ticket_status) {
                        case '1':
                            return '<span class="badge badge-primary">Diproses ke bagian repair</span>';
                            break;
                        case '2':
                            return '<span class="badge badge-warning">Diproses ke bagian gudang</span>';
                            break;
                        case '3':
                            return '<span class="badge badge-success">Selesai</span>';
                            break;
                        case '4':
                            return '<span class="badge badge-danger">Cancel</span>';
                            break;
                        default:
                            return '<span class="badge badge-dark">Status Unknown</span>';
                            break;
                    }
                })
                ->addColumn('repair_status', function ($row) {
                    switch ($row->can_repair) {
                        case '0':
                            return '<span class="badge badge-danger">Tidak Bisa Diperbaiki</span>';
                            break;
                        case '1':
                            return '<span class="badge badge-success">Bisa Diperbaiki</span>';
                            break;
                        default:
                            return '-';
                            break;
                    }
                })
                ->addColumn('job_status', function ($row) {
                    switch ($row->ticket->job_status) {
                        case '1':
                            return '<span class="badge badge-primary">Dalam penanganan oleh teknisi</span>';
                            break;
                        case '2':
                            return '<span class="badge badge-success">Telah diperbaiki oleh teknisi</span>';
                            break;
                        case '3':
                            return '<span class="badge badge-warning">Butuh klaim garansi</span>';
                            break;
                        case '4':
                            return '<span class="badge badge-warning">Butuh penggantian barang</span>';
                            break;
                        case '5':
                            return '<span class="badge badge-info">Dalam perbaikan oleh vendor</span>';
                            break;
                        case '6':
                            return '<span class="badge badge-info">Menunggu penggantian dari vendor</span>';
                            break;
                        case '7':
                            return '<span class="badge badge-success">Dalam perbaikan oleh Telah di kirim ke customer</span>';
                            break;
                        case '8':
                            return '<span class="badge badge-danger">Ticket di cancel</span>';
                            break;
                        default:
                            return '<span class="badge badge-dark">Status Unknown</span>';
                            break;
                    }
                })
                ->addColumn('action', function ($row) {
                    if ($row->ticket->ticket_status == 1) {
                        return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('teknisi.show', $row->uuid) . '" title="Detail Barang" href=""><i class="fal fa-search-plus"></i></a>
                        <a class="btn btn-secondary btn-sm btn-icon waves-effect waves-themed" href="' . route('teknisi.edit', $row->uuid) . '" title="Progress"><i class="fal fa-wrench"></i></a>';
                    } else {
                        return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('teknisi.show', $row->uuid) . '" title="Detail Barang" href=""><i class="fal fa-search-plus"></i></a>';
                    }
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->removeColumn('ticket_uuid')
                ->rawColumns(['action', 'ticket_status', 'repair_status', 'job_status'])
                ->make();
        }

        return view('teknisi.index');
    }

    /**
     * 
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Repair_item $id)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $repair_item = Repair_item::uuid($uuid);
        return view('teknisi.show', compact('repair_item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {
        $repair_item = Repair_item::uuid($uuid);
        return view('teknisi.edit', compact('repair_item'));
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
        $rules = [
            'item_status' => 'required',
            'keterangan' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
        ];

        $this->validate($request, $rules, $messages);
        // get repair item detail
        $repair_item = Repair_item::uuid($uuid);

        // create job order for technician first
        $teknisi = new Technician_job_order();
        $teknisi->repair_item_uuid = $repair_item->uuid;
        $teknisi->item_status = $request->item_status;
        $teknisi->keterangan = $request->keterangan;
        $teknisi->job_status = 1; // close job order whatever item status optin
        $teknisi->created_by = Auth::user()->uuid;
        $teknisi->save();

        /**
         * Update ticket status and item can repair status based on item status
         * So that gudang can view ticket to create job order
         */
        if ($request->item_status == 0) {
            $ticketing = Ticketing::uuid($repair_item->ticket_uuid);
            $ticketing->ticket_status = 2; // diproses ke gudang
            $ticketing->job_status = 4; // butuh penggantian/perbaikan item
            $ticketing->save();

            $repair_item->can_repair = 0;
            $repair_item->save();
        } elseif ($request->item_status == 1) {
            $ticketing = Ticketing::uuid($repair_item->ticket_uuid);
            $ticketing->ticket_status = 2; // diproses ke gudang
            $ticketing->job_status = 2; // telah diperbaiki oleh teknisi
            $ticketing->save();

            $repair_item->can_repair = 1;
            $repair_item->save();
        }

        toastr()->success('Ticket No.' . $repair_item->ticket->ticket_number . ' Telah di progress', 'Success');
        return redirect()->route('teknisi.index');
    }

    /**
     * Display a listing of tech job order.
     *
     * @return \Illuminate\Http\Response
     */
    public function history()
    {
        if (request()->ajax()) {
            // DB::statement(DB::raw('set @rownum=0'));
            $techJobOrders = Technician_job_order::select(
                'id',
                'uuid',
                'repair_item_uuid',
                'item_status',
                'job_status',
                'keterangan',
                'created_at'
            )->latest()->get();
            return Datatables::of($techJobOrders)
                ->addIndexColumn()
                ->addColumn('ticket_number', function ($techJobOrder) {
                    return $techJobOrder->repair->ticket->ticket_number;
                })
                ->editColumn('item_status', function ($techJobOrder) {
                    switch ($techJobOrder->item_status) {
                        case '0':
                            return '<span class="badge badge-warning">Butuh penanganan vendor</span>';
                            break;
                        case '1';
                            return '<span class="badge badge-success">Telah diperbaiki oleh teknisi</span>';
                            break;
                        case '2';
                            return '<span class="badge badge-danger">Ticket cancel</span>';
                            break;
                        default:
                            return '<span class="badge badge-info">Status Unknown</span>';
                            break;
                    }
                })
                ->editColumn('job_status', function ($techJobOrder) {
                    switch ($techJobOrder->job_status) {
                        case '0':
                            return '<span class="badge badge-primary">Dalam proses</span>';
                            break;
                        case '1';
                            return '<span class="badge badge-success">Selesai</span>';
                            break;
                        case '2';
                            return '<span class="badge badge-danger">Ticket cancel</span>';
                            break;
                        default:
                            return '<span class="badge badge-dark">Status Unknown</span>';
                            break;
                    }
                })
                ->editColumn('created_at', function ($techJobOrder) {
                    return Carbon::parse($techJobOrder->created_at)->translatedFormat('l\\, j F Y H:i:s');
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->removeColumn('repair_item_uuid')
                ->rawColumns(['item_status', 'job_status'])
                ->make();
        }
        return view('teknisi.history');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

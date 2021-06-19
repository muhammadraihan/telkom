<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Technician_job_order;
use App\Models\Ticketing;
use App\Models\Repair_item;
use App\Models\Item_replace;
use App\Models\Gudang_job_order;

use Auth;
use DataTables;
use DB;
use URL;

class GudangController extends Controller
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
                'id', 'uuid', 'ticket_uuid', 'can_repair', 'status_garansi'
            ])
                ->whereHas('ticket', function (Builder $query) {
                    $query->where('ticket_status', '!=', 1);
                });

            return Datatables::of($data)
<<<<<<< HEAD
                    ->addIndexColumn()
                    ->editColumn('created_by',function($row){
                        return $row->userCreate->name ?? null;
                    })
                    ->editColumn('edited_by',function($row){
                        return $row->userEdit->name ?? null;
                    })
                    ->editColumn('repair_item_uuid', function($row){
                        return $row->repairItem->ticket->ticket_number;
                    })
                    ->editColumn('item_status', function($row){
                        if($row->item_status == 1){
                            return 'Butuh perbaikan dari vendor';
                         }elseif($row->item_status == 2){
                            return 'Menunggu perbaikan dari vendor';
                         }elseif($row->item_status == 3){
                             return 'Menunggu penggantian dari vendor';
                         }elseif($row->item_status == 4){
                             return 'Item telah diperbaiki oleh teknisi';
                         }elseif($row->item_status == 5){
                             return 'Item telah diperbaiki oleh vendor';
                         }elseif($row->item_status == 6){
                             return 'Item telah diganti oleh vendor';
                         }
                    })
                    ->editColumn('job_status', function($row){
                        if($row->job_status == 0){
                           return 'Open';
                        }else{
                            return 'Close';
                        }
                    })
                    ->addColumn('action', function($row){
                        return '<a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="'.route('gudang.edit',$row->uuid).'"><i class="fal fa-edit"></i></a>';
                 })
            ->removeColumn('id')
            ->removeColumn('uuid')
            ->rawColumns(['action'])
            ->make(true);
=======
                ->addIndexColumn()
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
                            return '<span class="badge badge-success">Telah diperbaiki</span>';
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
                    return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('gudang.show', $row->uuid) . '" title="Detail Barang" href=""><i class="fal fa-search-plus"></i></a>
                        <a class="btn btn-secondary btn-sm btn-icon waves-effect waves-themed" href="' . route('gudang.edit', $row->uuid) . '" title="Progress"><i class="fal fa-wrench"></i></a>';
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->rawColumns(['action', 'ticket_status', 'repair_status', 'job_status'])
                ->make();
>>>>>>> origin
        }
        return view('gudang.index');
    }

    /**
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $repair_item = Technician_job_order::where('repair_item_uuid', '=', $uuid)->first();
        return view('gudang.show', compact('repair_item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {
<<<<<<< HEAD
        $gudang = Gudang_job_order::uuid($id);
        return view('gudang.edit', compact('gudang'));
=======
        // $item_replace = Item_replace::all()->pluck('item_repair_uuid', 'uuid');
        $tech_repair = Technician_job_order::where('repair_item_uuid', '=', $uuid)->first();
        // dd($tech_repair->repair_item_uuid);
        return view('gudang.edit', compact('tech_repair'));
>>>>>>> origin
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
        dd($request->all());
        $rules = [
            'tindakan' => 'required',
            'keterangan' => 'required',
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
        ];

        $this->validate($request, $rules, $messages);
        // get repair item detail
        $repair_item = Repair_item::uuid($uuid);

        // create job order for gudang first
        $gudang = new Gudang_job_order();
        $gudang->repair_item_uuid = $uuid;
        $gudang->item_status = $request->tindakan;
        $gudang->keterangan  = $request->keterangan;
        // close ticket when customer
        if ($request->tindakan == 7) {
            $gudang->job_status = 1;
        }
<<<<<<< HEAD
        $gudang->keterangan = $request->keterangan;
        $gudang->job_status = $request->job_status;
        if($gudang->job_status == 1){
            $jobstatus = DB::table('ticketings')
                        ->join('repair_items', 'repair_items.ticket_uuid', 'like', 'ticketings.uuid')
                        ->where('repair_items.uuid', '=', $gudang->repair_item_uuid)
                        ->update(['ticketings.job_status' => '6', 'ticketings.ticket_status' => '1']);
        }

        $gudang->save();

        toastr()->success('Gudang Job Order Edited','Success');
=======
        // $gudang = Gudang_job_order::uuid($uuid);
        // $gudang->repair_item_uuid = $gudang->repair_item_uuid;
        // $gudang->item_status = $request->item_status;
        // if ($gudang->item_status == 1) {
        //     $jobstatus = DB::table('ticketings')
        //         ->join('repair_items', 'repair_items.ticket_uuid', 'like', 'ticketings.uuid')
        //         ->where('repair_items.uuid', '=', $gudang->repair_item_uuid)
        //         ->update(['ticketings.job_status' => '1']);
        // } elseif ($gudang->item_status == 2) {
        //     $jobstatus = DB::table('ticketings')
        //         ->join('repair_items', 'repair_items.ticket_uuid', 'like', 'ticketings.uuid')
        //         ->where('repair_items.uuid', '=', $gudang->repair_item_uuid)
        //         ->update(['ticketings.job_status' => '3', 'repair_items.can_repair' => '0']);
        // } elseif ($gudang->item_status == 3) {
        //     $jobstatus = DB::table('ticketings')
        //         ->join('repair_items', 'repair_items.ticket_uuid', 'like', 'ticketings.uuid')
        //         ->where('repair_items.uuid', '=', $gudang->repair_item_uuid)
        //         ->update(['ticketings.job_status' => '4']);
        // } elseif ($gudang->item_status == 5) {
        //     $canrepair = DB::table('ticketings')
        //         ->join('repair_items', 'repair_items.ticket_uuid', 'like', 'ticketings.uuid')
        //         ->where('repair_items.uuid', '=', $gudang->repair_item_uuid)
        //         ->update(['repair_items.can_repair' => '1', 'ticketings.job_status' => '7']);
        // } elseif ($gudang->item_status == 4) {
        //     $jobstatus = DB::table('ticketings')
        //         ->join('repair_items', 'repair_items.ticket_uuid', 'like', 'ticketings.uuid')
        //         ->where('repair_items.uuid', '=', $gudang->repair_item_uuid)
        //         ->update(['ticketings.job_status' => '5']);
        // } elseif ($gudang->item_status == 6) {
        //     $jobstatus = DB::table('ticketings')
        //         ->join('repair_items', 'repair_items.ticket_uuid', 'like', 'ticketings.uuid')
        //         ->where('repair_items.uuid', '=', $gudang->repair_item_uuid)
        //         ->update(['ticketings.job_status' => '7', 'repair_items.can_repair' => '1']);
        // }
        // $gudang->keterangan = $request->keterangan;
        // $gudang->item_replace_uuid = $request->item_replace_uuid;
        // $gudang->job_status = $request->job_status;
        // if ($gudang->job_status == 1) {
        //     $jobstatus = DB::table('ticketings')
        //         ->join('repair_items', 'repair_items.ticket_uuid', 'like', 'ticketings.uuid')
        //         ->where('repair_items.uuid', '=', $gudang->repair_item_uuid)
        //         ->update(['ticketings.job_status' => '6', 'ticketings.ticket_status' => '1']);
        // }

        // $gudang->save();

        toastr()->success('Gudang Job Order Edited', 'Success');
>>>>>>> origin
        return redirect()->route('gudang.index');
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

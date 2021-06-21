<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticketing;
use App\Models\Customer;
use App\Models\Repair_item;
use App\Models\Kelengkapan;
use App\Models\Gudang_job_order;
use App\Models\Customer_type;
use Carbon\Carbon;

use Auth;
use DataTables;
use DB;
use URL;
use Helper;

class TicketingController extends Controller
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
                'uuid_pelanggan',
                'ticket_number',
                'keterangan',
                'ticket_status',
                'job_status',
                'created_by',
                'created_at',
            )->latest()->get();

            return Datatables::of($tickets)
                ->addIndexColumn()
                ->editColumn('created_by', function ($row) {
                    return $row->userCreate->name;
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->translatedFormat('l\\, j F Y H:i:s');
                })
                ->editColumn('uuid_pelanggan', function ($row) {
                    return $row->customer->nomor_pelanggan ?? null;
                })
                ->editColumn('ticket_status', function ($row) {
                    switch ($row->ticket_status) {
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
                ->editColumn('job_status', function ($row) {
                    switch ($row->job_status) {
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
                    return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('ticketing.show', $row->uuid) . '" title="Detail Barang" href=""><i class="fal fa-search-plus"></i></a>
                    <a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="' . route('ticketing.edit', $row->uuid) . '" title="Edit Tiket"><i class="fal fa-edit"></i></a>';
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->rawColumns(['action', 'ticket_status', 'job_status'])
                ->make();
        }

        return view('ticketing.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelengkapan = Kelengkapan::all();
        $pelanggan = Customer::all()->pluck('nomor_pelanggan', 'uuid');
        $customerType = Customer_type::all()->pluck('name', 'uuid');
        return view('ticketing.create', compact('pelanggan', 'kelengkapan', 'customerType'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'uuid_pelanggan' => 'required',
            'item_model' => 'required',
            'item_merk' => 'required',
            'item_type' => 'required',
            'part_number' => 'required',
            'serial_number' => 'required',
            'barcode' => 'required',
            'kerusakan' => 'required',
            'status_garansi' => 'required',
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);
        $randomTicket = Helper::GenerateTicketNumber(13);

        $ticketing = new Ticketing();
        $ticketing->uuid_pelanggan = $request->uuid_pelanggan;
        $ticketing->ticket_number = 'TKT' . '-' . $randomTicket;
        $ticketing->created_by = Auth::user()->uuid;

        $repair_item = new Repair_item();
        $repair_item->item_model = $request->item_model;
        $repair_item->item_merk = $request->item_merk;
        $repair_item->item_type = $request->item_type;
        $repair_item->part_number = $request->part_number;
        $repair_item->serial_number = $request->serial_number;
        $repair_item->barcode = $request->barcode;
        $repair_item->kelengkapan = $request['kelengkapan'];
        $repair_item->kerusakan = $request->kerusakan;
        $repair_item->status_garansi = $request->status_garansi;
<<<<<<< HEAD
        if($repair_item->status_garansi == 0){
            $ticketing->job_status = 2;
        }else{
            $ticketing->job_status = 4;
=======
        /**
         * if item is non warranty send job order to tech for repair
         * if item is warranty send job order to gudang for replace
         */
        if ($repair_item->status_garansi == 0) {
            $ticketing->ticket_status = 1;
            $ticketing->job_status = 1;
            $ticketing->save();

            $repair_item->ticket_uuid = $ticketing->uuid;
            $repair_item->created_by = Auth::user()->uuid;
            $repair_item->save();
>>>>>>> origin
        }

<<<<<<< HEAD
        if($repair_item->status_garansi == 1){
            $gudang = new Gudang_job_order();
            $gudang->repair_item_uuid = $repair_item->uuid;
            $gudang->item_status = 3;
            $gudang->keterangan = $ticketing->keterangan;
            $gudang->item_replace_uuid = $request->item_replace_uuid;
            $gudang->job_status = 0;
            $gudang->created_by = Auth::user()->uuid;
=======
        if ($repair_item->status_garansi == 1) {
            // $gudang = new Gudang_job_order();
            // $gudang->repair_item_uuid = $repair_item->uuid;
            // $gudang->item_status = $ticketing->job_status;
            // $gudang->keterangan = $ticketing->keterangan;
            // $gudang->item_replace_uuid = $request->item_replace_uuid;
            // $gudang->job_status = 0;
            // $gudang->created_by = Auth::user()->uuid;
>>>>>>> origin

            // $gudang->save();
        }

        toastr()->success('New Ticketing Added', 'Success');
        return redirect()->route('ticketing.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $repair_item = Repair_item::where('ticket_uuid', $uuid)->first();
        return view('ticketing.show', compact('repair_item'));
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
        $ticketing = Ticketing::uuid($id);
        $pelanggan = Customer::all()->pluck('jenis_pelanggan', 'jenis_pelanggan');
=======
        $ticketing = Ticketing::uuid($uuid);
        $repair_item = Repair_item::where('ticket_uuid', $ticketing->uuid)->first();
        $pelanggan = Customer::all()->pluck('nomor_pelanggan', 'uuid');
>>>>>>> origin
        $kelengkapan = Kelengkapan::all();
        return view('ticketing.edit', compact('ticketing', 'kelengkapan', 'pelanggan'));
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
            'uuid_pelanggan' => 'required',
            'item_model' => 'required',
            'item_merk' => 'required',
            'item_type' => 'required',
            'part_number' => 'required',
            'serial_number' => 'required',
            'barcode' => 'required',
            'kerusakan' => 'required',
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

<<<<<<< HEAD
        $tickteting = Ticketing::uuid($id);
        $tickteting->uuid_pelanggan = $request->uuid_pelanggan;
        $tickteting->keterangan = $request->keterangan;
        $tickteting->ticket_status = 0;
        $tickteting->job_status = 1;
        $tickteting->edited_by = Auth::user()->uuid;

        $tickteting->save();  
=======
        $ticketing = Ticketing::uuid($uuid);
        $ticketing->uuid_pelanggan = $request->uuid_pelanggan;
        $ticketing->edited_by = Auth::user()->uuid;
        $ticketing->save();

        $repair_item = Repair_item::where('ticket_uuid', '=', $uuid)->first();
        $repair_item->item_model = $request->item_model;
        $repair_item->item_merk = $request->item_merk;
        $repair_item->item_type = $request->item_type;
        $repair_item->part_number = $request->part_number;
        $repair_item->serial_number = $request->serial_number;
        $repair_item->barcode = $request->barcode;
        $repair_item->kelengkapan = $request['kelengkapan'];
        $repair_item->kerusakan = $request->kerusakan;
        $repair_item->edited_by = Auth::user()->uuid;
>>>>>>> origin

        $repair_item->save();


        toastr()->success('Ticketing Edited', 'Success');
        return redirect()->route('ticketing.index');
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

    public function CustomerStore(Request $request)
    {
        $rules = [
            'jenis_pelanggan' => 'required',
            'nomor_pelanggan' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $customer = new Customer();
        $customer->jenis_pelanggan = $request->jenis_pelanggan;
        $customer->nomor_pelanggan = $request->nomor_pelanggan;
        $customer->created_by = Auth::user()->uuid;

        $customer->save();
        toastr()->success('New Customer Added', 'Success');
        return redirect()->back();
    }
}

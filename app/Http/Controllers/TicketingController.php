<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\ModuleCategory;
use App\Models\RepairItem;
use App\Models\Ticketing;
use App\Models\Witel;
use Illuminate\Http\Request;

use Carbon\Carbon;

use Auth;
use DataTables;
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
        $accessories = Accessory::all();
        $witels = Witel::all()->pluck('name', 'uuid');
        $module_category = ModuleCategory::all()->pluck('name', 'uuid');
        return view('ticketing.create', compact('accessories', 'witels', 'module_category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $rules = [
            'unit' => 'required',
            'module_category' => 'required',
            'module_name' => 'required',
            'module_brand' => 'required',
            'module_type' => 'required',
            'part_number' => 'required',
            'serial_number' => 'required',
            'serial_number_msc' => 'required',
            'warranty_status' => 'required',
            'urgent_status' => 'required',
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        // get ticket number
        $generated_ticket_number = Helper::generateTicketNumber();

        // saving ticket
        $ticketing = new Ticketing();
        $ticketing->uuid_unit = $request->unit;
        $ticketing->ticket_number = $generated_ticket_number;
        $ticketing->created_by = Auth::user()->uuid;
        $ticketing->urgent_status = $request->urgent_status;
        // saving repair item detail
        $repair_item = new RepairItem();
        $repair_item->module_category_uuid = $request->module_category;
        $repair_item->module_name_uuid = $request->module_name;
        $repair_item->module_brand_uuid = $request->module_brand;
        $repair_item->module_type_uuid = $request->module_type;
        $repair_item->part_number = $request->part_number;
        $repair_item->serial_number = $request->serial_number;
        $repair_item->serial_number_msc = $request->serial_number_msc;
        $repair_item->accessories = $request['accessories'];
        $repair_item->warranty_status = $request->warranty_status;

        /**
         * if item is non warranty send job order to tech for repair
         * if item is warranty send job order to gudang for replace
         */
        if ($repair_item->warranty_status == 0) {
            $ticketing->ticket_status = 1;
            $ticketing->job_status = 1;
            $ticketing->save();

            $repair_item->ticket_uuid = $ticketing->uuid;
            $repair_item->created_by = Auth::user()->uuid;
            $repair_item->save();
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
        $ticketing = Ticketing::uuid($uuid);
        $repair_item = Repair_item::where('ticket_uuid', $ticketing->uuid)->first();
        $pelanggan = Customer::all()->pluck('nomor_pelanggan', 'uuid');
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticketing;
use App\Models\Customer;
use App\Models\Repair_item;
use App\Models\Kelengkapan;

use Auth;
use DataTables;
use DB;
use File;
use Hash;
use Image;
use Response;
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
        $tickteting = Ticketing::all();
        if (request()->ajax()) {
            DB::statement(DB::raw('set @rownum=0'));
            $data = Ticketing::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id','uuid','uuid_pelanggan','ticket_number','keterangan', 'ticket_status', 'job_status', 'created_by', 'edited_by']);

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('created_by',function($row){
                        return $row->userCreate->name;
                    })
                    ->editColumn('edited_by',function($row){
                        return $row->userEdit->name ?? null;
                    })
                    ->editColumn('uuid_pelanggan',function($row){
                        return $row->customer->jenis_pelanggan ?? null;
                    })
                    ->editColumn('ticket_status', function($row){
                        if($row->ticket_status == 0){
                           return 'Diproses';
                        }else{
                           return 'Selesai';
                        }
                    })
                    ->editColumn('job_status', function($row){
                        if($row->job_status == 1){
                           return 'Butuh perbaikan dari vendor';
                        }elseif($row->job_status == 2){
                           return 'Butuh perbaikan dari teknisi';
                        }elseif($row->job_status == 3){
                            return 'Menunggu perbaikan dari vendor';
                        }elseif($row->job_status == 4){
                            return 'Menunggu penggantian dari vendor';
                        }elseif($row->job_status == 5){
                            return 'Telah diperbaiki oleh teknisi';
                        }elseif($row->job_status == 6){
                            return 'Telah dikirim ke customer';
                        }elseif($row->job_status == 7){
                            return 'Item telah diperbaiki oleh vendor';
                        }
                    })
                    ->addColumn('action', function($row){
                        return '<a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="'.route('ticketing.edit',$row->uuid).'"><i class="fal fa-edit"></i></a>';
                 })
            ->removeColumn('id')
            ->removeColumn('uuid')
            ->rawColumns(['action'])
            ->make(true);
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
        $pelanggan = Customer::all()->pluck('jenis_pelanggan', 'jenis_pelanggan');
        return view('ticketing.create',compact('pelanggan', 'kelengkapan'));
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
            'keterangan' => 'required',
            'item_model' => 'required',
            'item_merk' => 'required',
            'item_type' => 'required',
            'part_number' => 'required',
            'serial_number' => 'required',
            'barcode' => 'required',
            'kelengkapan' => 'required',
            'kerusakan' => 'required',
            'status_garansi' => 'required',
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);
        // dd($request->all());
        $randomTicket = Helper::GenerateTicketNumber(13);

        $ticketing = new Ticketing();
        $ticketing->uuid_pelanggan = $request->uuid_pelanggan;
        $ticketing->ticket_number = 'TKT' . '-' . $randomTicket;
        $ticketing->keterangan = $request->keterangan;
        $ticketing->ticket_status = 0;
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
        if($repair_item->status_garansi == 0){
            $ticketing->job_status = 2;
        }else{
            $ticketing->job_status = 1;
        }
        $ticketing->save();
        $repair_item->ticket_uuid = $ticketing->uuid;
        $repair_item->created_by = Auth::user()->uuid;
        
        $repair_item->save();
        
        toastr()->success('New Ticketing Added','Success');
        return redirect()->route('ticketing.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ticketing = Ticketing::uuid($id);
        $repair_item = Repair_item::uuid($id);
        $pelanggan = Customer::all()->pluck('jenis_pelanggan', 'jenis_pelanggan');
        $kelengkapan = Kelengkapan::all();
        return view('ticketing.edit', compact('ticketing', 'kelengkapan', 'pelanggan', 'repair_item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'uuid_pelanggan' => 'required',
            'keterangan' => 'required',
            'item_model' => 'required',
            'item_merk' => 'required',
            'item_type' => 'required',
            'part_number' => 'required',
            'serial_number' => 'required',
            'barcode' => 'required',
            'kelengkapan' => 'required',
            'kerusakan' => 'required',
            'status_garansi' => 'required',
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);
        // dd($request->all());

        $tickteting = Ticketing::uuid($id);
        $tickteting->uuid_pelanggan = $request->uuid_pelanggan;
        $tickteting->keterangan = $request->keterangan;
        $tickteting->ticket_status = 0;
        $tickteting->job_status = 1;
        $tickteting->edited_by = Auth::user()->uuid;

        $tickteting->save();

        $repair_item = Repair_item::uuid($id);
        $repair_item->item_model = $request->item_model;
        $repair_item->item_merk = $request->item_merk;
        $repair_item->item_type = $request->item_type;
        $repair_item->part_number = $request->part_number;
        $repair_item->serial_number = $request->serial_number;
        $repair_item->barcode = $request->barcode;
        $repair_item->kelengkapan = json_encode($request['kelengkapan']);
        $repair_item->kerusakan = $request->kerusakan;
        $repair_item->status_garansi = $request->status_garansi;
        $repair_item->edited_by = Auth::user()->uuid;
        
        $repair_item->save();      

        
        toastr()->success('Ticketing Edited','Success');
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
}

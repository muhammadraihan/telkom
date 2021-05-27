<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Technician_job_order;
use App\Models\Ticketing;
use App\Models\Repair_item;
use App\Models\Gudang_job_order;

use Auth;
use DataTables;
use DB;
use File;
use Hash;
use Image;
use Response;
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
        $teknisi = Technician_job_order::all();
        if (request()->ajax()) {
            $data = Technician_job_order::latest()->get();

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('created_by',function($row){
                        return $row->userCreate->name;
                    })
                    ->editColumn('edited_by',function($row){
                        return $row->userEdit->name ?? null;
                    })
                    ->editColumn('repair_item_uuid',function($row){
                        return $row->repairItem->barcode ?? null;
                    })
                    ->editColumn('item_status', function($row){
                        if($row->item_status == 0){
                           return 'Butuh perbaikan dari vendor';
                        }else{
                            return 'Item telah diperbaiki oleh teknisi';
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
                        return '<a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="'.route('teknisi.edit',$row->uuid).'"><i class="fal fa-edit"></i></a>';
                 })
            ->removeColumn('id')
            ->removeColumn('uuid')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('teknisi.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $repair_item = Repair_item::all()->pluck('barcode', 'barcode');
        return view('teknisi.create', compact('repair_item'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Repair_item $id)
    {
        $rules = [
            'repair_item_uuid' => 'required',
            'item_status' => 'required',
            'keterangan' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $teknisi = new Technician_job_order();
        $teknisi->repair_item_uuid = $request->repair_item_uuid;
        $teknisi->item_status = $request->item_status;
        if ($teknisi->item_status == 1){
            $repair_item = Repair_item::where('barcode', $teknisi->repair_item_uuid)->update(['can_repair' => '1']);
            $tiketing = Ticketing::where
        }else{
            $repair_item = Repair_item::where('barcode', $teknisi->repair_item_uuid)->update(['can_repair' => '0']);
        }
        $teknisi->keterangan = $request->keterangan;
        $teknisi->job_status = 1;
        $teknisi->created_by = Auth::user()->uuid;

        $teknisi->save();   
        
        $gudang = new Gudang_job_order();
        $gudang->repair_item_uuid = $teknisi->repair_item_uuid;
        if ($repair_item = Repair_item::select('barcode', $teknisi->repair_item_uuid)->where('can_repair' == '1')){
            $gudang->item_status = 4;
        }else ($repair_item = Repair_item::select('barcode', $teknisi->repair_item_uuid)->where('can_repair' == '0')) {
            $gudang->item_status = 1;
        }
        $gudang->keterangan = $teknisi->keterangan;
        $gudang->item_replace_uuid = $request->item_replace_uuid;
        $gudang->job_status = 0;
        $gudang->created_at = Auth::user()->uuid;

        $gudang->save();

        
        toastr()->success('New Technician Job Order Added','Success');
        return redirect()->route('teknisi.index');
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
        $teknisi = Technician_job_order::uuid($id);
        $repair_item = Repair_item::all()->pluck('barcode', 'barcode');
        return view('teknisi.edit', compact('teknisi', 'repair_item'));
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
            'repair_item_uuid' => 'required',
            'item_status' => 'required',
            'keterangan' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $teknisi = Technician_job_order::uuid($id);
        $teknisi->repair_item_uuid = $request->repair_item_uuid;
        $teknisi->item_status = $request->item_status;
        $teknisi->keterangan = $request->keterangan;
        $teknisi->job_status = 0;
        $teknisi->edited_by = Auth::user()->uuid;

        $teknisi->save();        

        
        toastr()->success('Technician Job Order Edited','Success');
        return redirect()->route('teknisi.index');
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Technician_job_order;
use App\Models\Ticketing;
use App\Models\Repair_item;
use App\Models\Item_replace;
use App\Models\Gudang_job_order;

use Auth;
use DataTables;
use DB;
use File;
use Hash;
use Image;
use Response;
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
        $gudang = Gudang_job_order::all();
        if (request()->ajax()) {
            $data = Gudang_job_order::latest()->get();

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('created_by',function($row){
                        return $row->userCreate->name ?? null;
                    })
                    ->editColumn('edited_by',function($row){
                        return $row->userEdit->name ?? null;
                    })
                    ->editColumn('repair_item_uuid',function($row){
                        return $row->repairItem->barcode ?? null;
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
                         }else{
                             return 'Item telah diperbaiki oleh vendor';
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
        $repair_item = Repair_item::all()->pluck('barcode', 'barcode');
        $item_replace = Item_replace::all()->pluck('item_repair_uuid', 'item_repair_uuid');
        $gudang = Gudang_job_order::uuid($id);
        return view('gudang.edit', compact('repair_item', 'item_replace', 'gudang'));
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
            'keterangan' => 'required',
            'job_status' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $gudang = Gudang_job_order::uuid($id);
        $gudang->repair_item_uuid = $request->repair_item_uuid;
        $gudang->item_status = $request->item_status;
        $gudang->keterangan = $request->keterangan;
        $gudang->item_replace_uuid = $request->item_replace_uuid;
        $gudang->job_status = 0;
        $gudang->edited_at = Auth::user()->uuid;

        $gudang->save();

        toastr()->success('Gudang Job Order Edited','Success');
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

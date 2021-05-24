<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Technician_job_order;
use App\Models\Ticketing;
use App\Models\Repair_item;
use App\Models\Technician_job_order;

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
                        return $row->userCreate->name;
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
        //
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
        //
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

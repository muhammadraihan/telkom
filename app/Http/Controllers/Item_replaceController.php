<?php

namespace App\Http\Controllers;
use App\Models\Item_replace;
use App\Models\Gudang_job_order;
use App\Models\Kelengkapan;
use App\Models\Stock_item;
use App\Models\Item_replace_vendor_detail;
use App\Models\Repair_item;

use Illuminate\Http\Request;
use Auth;
use DataTables;
use DB;
use File;
use Hash;
use Image;
use Response;
use URL;
use Helper;

class Item_replaceController extends Controller
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
            
            DB::statement(DB::raw('set @rownum=0'));
            $data = Gudang_job_order::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id','uuid','repair_item_uuid','item_status', 'created_by', 'edited_by'])
            ->where('item_status', '=', '3');
            
            return Datatables::of($data)
                    ->editColumn('item_status', function($row){
                        if($row->item_status == 3){
                            return 'Menunggu Penggantian Dari Vendor';
                        }
                    })
                    ->editColumn('created_by',function($row){
                        return $row->userCreate->name;
                    })
                    ->editColumn('edited_by',function($row){
                        return $row->userEdit->name ?? null;
                    })
                    ->addColumn('action', function($row){
                        return '<a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="'.route('itemreplace.edit',$row->uuid).'"><i class="fal fa-edit"></i></a>';
                 })
            ->removeColumn('id')
            ->removeColumn('uuid')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('item_replace.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $gudang = Gudang_job_order::uuid($id);
        $kelengkapan = Kelengkapan::all();
        $stock = Stock_item::all();
        return view('item_replace.edit', compact('gudang','stock', 'kelengkapan'));
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
            'item_repair_uuid' => 'required',
            'replace_from' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $repair_item = Gudang_job_order::uuid($id);
        
        $item_replace = new Item_replace();
        $item_replace->item_repair_uuid = $repair_item->repair_item_uuid;
        $item_replace->replace_from = $request->replace_from;
        if($item_replace->replace_from == 1){
            $rules = [
                'item_model' => 'required',
                'item_merk' => 'required',
                'item_type' => 'required',
                'part_number' => 'required',
                'serial_number' => 'required',
                'barcode' => 'required',
                'kelengkapan' => 'required',
            ];
    
            $messages = [
                '*.required' => 'Field tidak boleh kosong !',
                '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
            ];
    
            $this->validate($request, $rules, $messages);
            
            $vendor = new Item_replace_vendor_detail();
            $vendor->vendor_name = $request->vendor_name;
            $vendor->item_model = $request->item_model;
            $vendor->item_merk = $request->item_merk;
            $vendor->item_type = $request->item_type;
            $vendor->part_number = $request->part_number;
            $vendor->serial_number = $request->serial_number;
            $vendor->barcode = $request->barcode;
            $vendor->kelengkapan = $request['kelengkapan'];
            $vendor->created_by = Auth::user()->uuid;
    
            $vendor->save(); 
            $item_replace->item_replace_detail_from_vendor = $vendor->uuid;
            
        }elseif($item_replace->replace_from == 3){
            $item_replace->item_replace_detail_from_stock = $request->item_replace_detail_from_stock;
        }
        $item_replace->created_by = Auth::user()->uuid;   
        $item_replace->save();     
        
        toastr()->success('Item Replace Added','Success');
        return redirect()->route('itemreplace.index');
    }

    public function detailStock(Request $request){
        $stock = Stock_item::all();

        return response()->json($stock);
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

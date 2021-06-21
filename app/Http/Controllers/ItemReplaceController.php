<?php

namespace App\Http\Controllers;
use App\Models\ItemReplace;
use App\Models\GudangJobOrder;
use App\Models\Kelengkapan;
use App\Models\StockItem;
use App\Models\ItemReplaceVendorDetail;
use App\Models\RepairItem;
use App\Models\BufferStock;

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

class ItemReplaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gudang = GudangJobOrder::all();
            
        if (request()->ajax()) {
            
            DB::statement(DB::raw('set @rownum=0'));
            $data = GudangJobOrder::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id','uuid','repair_item_uuid','item_status', 'created_by', 'edited_by'])
            ->where('item_status', '=', '3');
            
            return Datatables::of($data)
                    ->editColumn('item_status', function($row){
                        if($row->item_status == 3){
                            return 'Menunggu Penggantian Dari Vendor';
                        }
                    })
                    ->editColumn('repair_item_uuid', function($row){
                        return $row->repairItem->ticket->ticket_number;
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
        $gudang = GudangJobOrder::uuid($id);
        $kelengkapan = Kelengkapan::all();
        $bufferstock = BufferStock::all();
        return view('item_replace.edit', compact('gudang','bufferstock', 'kelengkapan'));
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
            'replace_from' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $gudang = GudangJobOrder::uuid($id);
        
        $item_replace = new ItemReplace();
        
        $item_replace->item_repair_uuid = $gudang->repair_item_uuid;
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
            
            $vendor = new ItemReplaceVendorDetail();
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
            $buffer = BufferStock::uuid($request->bufferstock);
            $buffer->buffer_ammount = $buffer->buffer_ammount -1;
            $buffer->save();

            $item_replace->item_replace_detail_from_stock = $buffer->uuid;
        }elseif($item_replace->replace_from == 2){
            $stock = StockItem::uuid($request->mainstock);
            $stock->amount = $stock->amount -1;
            $stock->save();

            $item_replace->item_replace_detail_from_stock = $stock->uuid;
            $gudang->item_replace_uuid = $item_replace->item_replace_detail_from_stock;
            $gudang->save();
        }
        $item_replace->created_by = Auth::user()->uuid;   
        $item_replace->save();     
        $gudang->item_replace_uuid = $item_replace->uuid;
        $gudang->save();

        
        toastr()->success('Item Replace Added','Success');
        return redirect()->route('itemreplace.index');
    }

    public function detailBufferStock(Request $request){
        $bufferstock = BufferStock::all();
        if (request()->ajax()) {
            DB::statement(DB::raw('set @rownum=0'));
            $data = BufferStock::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id','uuid','stock_item_uuid','buffer_ammount','office_city']);

            return Datatables::of($data)
                    ->editColumn('office_city', function($row){
                        return $row->kota->city_name;
                    })
                    ->addColumn('item_type', function($row){
                        return $row->stockItem->item_type;
                    })
                    ->addColumn('item_merk', function($row){
                        return $row->stockItem->item_merk;
                    })
                    ->addColumn('item_model', function($row){
                        return $row->stockItem->item_model;
                    })
                    ->addColumn('part_number', function($row){
                        return $row->stockItem->part_number;
                    })
                    ->addColumn('serial_number', function($row){
                        return $row->stockItem->serial_number;
                    })
                    ->addColumn('barcode', function($row){
                        return $row->stockItem->barcode;
                    })
                    ->addColumn('kelengkapan', function($row){
                        return $row->stockItem->kelengkapan;
                    })
            ->removeColumn('id')
            ->removeColumn('stock_item_uuid')
            ->make(true);
        }

        return response()->json($bufferstock);
    }

    public function detailStock(Request $request){
        $stock = StockItem::all();
        if (request()->ajax()) {
            DB::statement(DB::raw('set @rownum=0'));
            $data = StockItem::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id','uuid','item_model','item_merk','item_type','part_number','serial_number','barcode','kelengkapan','amount']);

            return Datatables::of($data)
                    
            ->removeColumn('id')
            ->make(true);
        }

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

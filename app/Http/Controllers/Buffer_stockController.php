<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buffer_stock;
use App\Models\Kota;
use App\Models\Stock_item;

use Auth;
use DataTables;
use DB;
use File;
use Hash;
use Image;
use Response;
use URL;

class Buffer_stockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buffer_stock = Buffer_stock::all();
        if (request()->ajax()) {
            $data = Buffer_stock::latest()->get();

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('created_by',function($row){
                        return $row->userCreate->name;
                    })
                    ->editColumn('edited_by',function($row){
                        return $row->userEdit->name ?? null;
                    })
                    ->editColumn('office_city', function($row){
                        return $row->kota->city_name;
                    })
                    ->editColumn('stock_item_uuid', function($row){
                        return $row->stockItem->serial_number;
                    })
                    ->addColumn('action', function($row){
                        return '
                        <a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="'.route('buffer-stock.edit',$row->uuid).'"><i class="fal fa-edit"></i></a>
                        <a class="btn btn-danger btn-sm btn-icon waves-effect waves-themed delete-btn" data-url="'.URL::route('buffer-stock.destroy',$row->uuid).'" data-id="'.$row->uuid.'" data-token="'.csrf_token().'" data-toggle="modal" data-target="#modal-delete"><i class="fal fa-trash-alt"></i></a>';
                 })
            ->removeColumn('id')
            ->removeColumn('uuid')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('buffer_stock.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kota = Kota::all()->pluck('city_name', 'uuid');
        $stock_item = Stock_item::all()->pluck('serial_number', 'uuid');
        return view('buffer_stock.create', compact('kota', 'stock_item'));
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
            'stock_item_uuid' => 'required',
            'buffer_ammount' => 'required',
            'office_city' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $buffer_stock = new Buffer_stock();
        $buffer_stock->stock_item_uuid = $request->stock_item_uuid;
        $buffer_stock->buffer_ammount = $request->buffer_ammount;
        $buffer_stock->office_city = $request->office_city;
        $buffer_stock->created_by = Auth::user()->uuid;

        $buffer_stock->save();        

        
        toastr()->success('New Buffer Stock Added','Success');
        return redirect()->route('buffer-stock.index');
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
        $buffer_stock = Buffer_stock::uuid($id);
        $kota = Kota::all()->pluck('city_name', 'uuid');
        $stock_item = Stock_item::all()->pluck('serial_number', 'uuid');
        return view('buffer_stock.edit', compact('buffer_stock', 'kota', 'stock_item'));
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
            'stock_item_uuid' => 'required',
            'buffer_ammount' => 'required',
            'office_city' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $buffer_stock = Buffer_stock::uuid($id);
        $buffer_stock->stock_item_uuid = $request->stock_item_uuid;
        $buffer_stock->buffer_ammount = $request->buffer_ammount;
        $buffer_stock->office_city = $request->office_city;
        $buffer_stock->edited_by = Auth::user()->uuid;

        $buffer_stock->save();        

        
        toastr()->success('Buffer Stock Edited','Success');
        return redirect()->route('buffer-stock.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $buffer_stock = Buffer_stock::uuid($id);
        $buffer_stock->delete();
        toastr()->success('Buffer Stock Deleted','Success');
        return redirect()->route('buffer-stock.index');
    }
}

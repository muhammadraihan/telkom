<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock_item;
use App\Models\Kelengkapan;

use Auth;
use DataTables;
use DB;
use File;
use Hash;
use Image;
use Response;
use URL;

class Stock_itemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stock = Stock_item::all();
        if (request()->ajax()) {
            $data = Stock_item::latest()->get();

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('created_by',function($row){
                        return $row->userCreate->name;
                    })
                    ->editColumn('edited_by',function($row){
                        return $row->userEdit->name ?? null;
                    })
                    ->addColumn('action', function($row){
                        return '
                        <a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="'.route('stock_item.edit',$row->uuid).'"><i class="fal fa-edit"></i></a>
                        <a class="btn btn-danger btn-sm btn-icon waves-effect waves-themed delete-btn" data-url="'.URL::route('stock_item.destroy',$row->uuid).'" data-id="'.$row->uuid.'" data-token="'.csrf_token().'" data-toggle="modal" data-target="#modal-delete"><i class="fal fa-trash-alt"></i></a>';
                 })
            ->removeColumn('id')
            ->removeColumn('uuid')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('stock_item.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelengkapan = Kelengkapan::all();
        return view('stock_item.create', compact('kelengkapan'));
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
            'item_model' => 'required',
            'item_merk' => 'required',
            'item_type' => 'required',
            'part_number' => 'required',
            'serial_number' => 'required',
            'barcode' => 'required',
            'kelengkapan' => 'required',
            'amount' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);
        // dd($request['kelengkapan']);
        $stock = new Stock_item();
        $stock->item_model = $request->item_model;
        $stock->item_merk = $request->item_merk;
        $stock->item_type = $request->item_type;
        $stock->part_number = $request->part_number;
        $stock->serial_number = $request->serial_number;
        $stock->barcode = $request->barcode;
        $stock->kelengkapan = $request['kelengkapan'];
        $stock->amount = $request->amount;
        $stock->created_by = Auth::user()->uuid;

        $stock->save();      

        
        toastr()->success('New Stock Item Added','Success');
        return redirect()->route('stock_item.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $stock = Stock_item::uuid($id);
        $kelengkapan = Kelengkapan::all();
        // dd($stock->kelengkapan);
        return view('stock_item.edit', compact('stock','kelengkapan'));
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
            'item_model' => 'required',
            'item_merk' => 'required',
            'item_type' => 'required',
            'part_number' => 'required',
            'serial_number' => 'required',
            'barcode' => 'required',
            'kelengkapan' => 'required',
            'amount' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);
        // dd($request['kelengkapan']);
        $stock = Stock_item::uuid($id);
        $stock->item_model = $request->item_model;
        $stock->item_merk = $request->item_merk;
        $stock->item_type = $request->item_type;
        $stock->part_number = $request->part_number;
        $stock->serial_number = $request->serial_number;
        $stock->barcode = $request->barcode;
        $stock->kelengkapan = $request['kelengkapan'];
        $stock->amount = $request->amount;
        $stock->edited_by = Auth::user()->uuid;

        $stock->save();        

        
        toastr()->success('Stock Item Edited','Success');
        return redirect()->route('stock_item.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stock = Stock_item::uuid($id);
        $stock->delete();
        toastr()->success('Stock Item Deleted','Success');
        return redirect()->route('stock_item.index');
    }
}

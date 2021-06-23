<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModuleCategory;
use App\Models\ModuleName;
use App\Models\ModuleBrand;
use App\Models\ModuleType;
use App\Models\ModuleStock;

use Auth;
use DataTables;
use DB;
use File;
use Hash;
use Image;
use Response;
use URL;

class ModuleStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stock = ModuleStock::all();
        if (request()->ajax()) {
            $data = ModuleStock::get();

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('created_by',function($row){
                        return $row->userCreate->name;
                    })
                    ->editColumn('edited_by',function($row){
                        return $row->userEdit->name ?? null;
                    })
                    ->editColumn('module_category_uuid', function($row){
                        return $row->category->name;
                    })
                    ->editColumn('module_name_uuid', function($row){
                        return $row->nameModule->name;
                    })
                    ->editColumn('module_brand_uuid', function($row){
                        return $row->brand->name;
                    })
                    ->editColumn('module_type_uuid', function($row){
                        return $row->type->name;
                    })
                    ->addColumn('action', function($row){
                        return '
                        <a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="'.route('stock.edit',$row->uuid).'"><i class="fal fa-edit"></i></a>
                        <a class="btn btn-danger btn-sm btn-icon waves-effect waves-themed delete-btn" data-url="'.URL::route('stock.destroy',$row->uuid).'" data-id="'.$row->uuid.'" data-token="'.csrf_token().'" data-toggle="modal" data-target="#modal-delete"><i class="fal fa-trash-alt"></i></a>';
                 })
            ->removeColumn('id')
            ->removeColumn('uuid')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('stock.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = ModuleCategory::all()->pluck('name', 'uuid');
        return view('stock.create', compact('category'));
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
            'module_category_uuid' => 'required',
            'module_name' => 'required',
            'module_brand' => 'required',
            'module_type' => 'required',
            'available' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $stock = new ModuleStock();
        $stock->module_category_uuid = $request->module_category_uuid;
        $stock->module_name_uuid = $request->module_name;
        $stock->module_brand_uuid = $request->module_brand;
        $stock->module_type_uuid = $request->module_type;
        $stock->available = $request->available;
        $stock->created_by = Auth::user()->uuid;

        $stock->save();        

        
        toastr()->success('New Stock Added','Success');
        return redirect()->route('stock.index');
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
        $category = ModuleCategory::all()->pluck('name', 'uuid');
        $stock = ModuleStock::uuid($id);
        return view('stock.edit', compact('category','stock'));
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
            'module_category_uuid' => 'required',
            'module_name' => 'required',
            'module_brand' => 'required',
            'module_type' => 'required',
            'available' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $stock = ModuleStock::uuid($id);
        $stock->module_category_uuid = $request->module_category_uuid;
        $stock->module_name_uuid = $request->module_name;
        $stock->module_brand_uuid = $request->module_brand;
        $stock->module_type_uuid = $request->module_type;
        $stock->available = $request->available;
        $stock->edited_by = Auth::user()->uuid;

        $stock->save();        

        
        toastr()->success('Stock Edited','Success');
        return redirect()->route('stock.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stock = ModuleStock::uuid($id);
        $stock->delete();
        toastr()->success('Stock Deleted','Success');
        return redirect()->route('stock.index');
    }
}

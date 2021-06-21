<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModuleName;
use App\Models\ModuleCategory;
use App\Models\ModuleType;
use App\Models\Material;

use Auth;
use DataTables;
use DB;
use File;
use Hash;
use Image;
use Response;
use URL;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $material = Material::all();
            if (request()->ajax()) {
                $data = Material::get();

                return Datatables::of($data)
                        ->addIndexColumn()
                        ->editColumn('module_name_uuid', function($row){
                            return $row->nameModule->name;
                        })
                        ->editColumn('module_category_uuid', function($row){
                            return $row->category->name;
                        })
                        ->editColumn('material_type', function($row){
                            return $row->type->name;
                        })
                        ->editColumn('volume', function($row){
                            switch ($row->volume) {
                                case '1':
                                    return 'Buah';
                                    break;
                                case '2':
                                    return 'Kotak';
                                    break;
                            }
                        })
                        ->editColumn('unit_price',function($row){
                            return $row->unit_price ? 'Rp.'.' '.number_format($row->unit_price,2) : '' ;
                        })
                        ->addColumn('action', function($row){
                            return '
                            <a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="'.route('material.edit',$row->uuid).'"><i class="fal fa-edit"></i></a>
                            <a class="btn btn-danger btn-sm btn-icon waves-effect waves-themed delete-btn" data-url="'.URL::route('material.destroy',$row->uuid).'" data-id="'.$row->uuid.'" data-token="'.csrf_token().'" data-toggle="modal" data-target="#modal-delete"><i class="fal fa-trash-alt"></i></a>';
                    })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->rawColumns(['action'])
                ->make(true);
            }

            return view('material.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = ModuleCategory::all()->pluck('name', 'uuid');
        $name = ModuleName::all()->pluck('name', 'uuid');
        $type = ModuleType::all()->pluck('name', 'uuid');
        return view('material.create', compact('category', 'name', 'type'));
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
            'module_name_uuid' => 'required',
            'module_category_uuid' => 'required',
            'material_type' => 'required',
            'material_description' => 'required',
            'volume' => 'required',
            'available' => 'required', 
            'unit_price' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $unit_price = $request->unit_price;
        $formattedprice = str_replace(',', '', $unit_price);

        $material = new Material();
        $material->module_category_uuid = $request->module_category_uuid;
        $material->module_name_uuid = $request->module_name_uuid;
        $material->material_type = $request->material_type;
        $material->material_description = $request->material_description;
        $material->volume = $request->volume;
        $material->available = $request->available;
        $material->unit_price = $formattedprice;

        $material->save();        

        
        toastr()->success('New Material Added','Success');
        return redirect()->route('material.index');
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
        $name = ModuleName::all()->pluck('name', 'uuid');
        $type = ModuleType::all()->pluck('name', 'uuid');
        $material = Material::uuid($id);
        return view('material.edit', compact('category', 'name', 'type', 'material'));
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
            'module_name_uuid' => 'required',
            'module_category_uuid' => 'required',
            'material_type' => 'required',
            'material_description' => 'required',
            'volume' => 'required',
            'available' => 'required', 
            'unit_price' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $unit_price = $request->unit_price;
        $formattedprice = str_replace(',', '', $unit_price);

        $material = Material::uuid($id);
        $material->module_category_uuid = $request->module_category_uuid;
        $material->module_name_uuid = $request->module_name_uuid;
        $material->material_type = $request->material_type;
        $material->material_description = $request->material_description;
        $material->volume = $request->volume;
        $material->available = $request->available;
        $material->unit_price = $formattedprice;

        $material->save();        

        
        toastr()->success('Material Edited','Success');
        return redirect()->route('material.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $material = Material::uuid($id);
        $material->delete();
        toastr()->success('Material Deleted','Success');
        return redirect()->route('material.index');
    }
}

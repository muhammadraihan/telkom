<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModuleCategory;
use App\Models\ModuleName;
use App\Models\ModuleBrand;
use App\Models\ModuleType;

use Auth;
use DataTables;
use DB;
use File;
use Hash;
use Image;
use Response;
use URL;

class ModuleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $type = ModuleType::all();
        if (request()->ajax()) {
            $data = ModuleType::get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('created_by', function ($row) {
                    return $row->userCreate->name;
                })
                ->editColumn('edited_by', function ($row) {
                    return $row->userEdit->name ?? null;
                })
                ->addColumn('module_category_uuid', function ($row) {
                    return $row->brand->moduleName->category->name;
                })
                ->addColumn('module_name_uuid', function ($row) {
                    return $row->brand->moduleName->name;
                })
                ->editColumn('module_brand_uuid', function ($row) {
                    return $row->brand->name;
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="' . route('type.edit', $row->uuid) . '"><i class="fal fa-edit"></i></a>
                        <a class="btn btn-danger btn-sm btn-icon waves-effect waves-themed delete-btn" data-url="' . URL::route('type.destroy', $row->uuid) . '" data-id="' . $row->uuid . '" data-token="' . csrf_token() . '" data-toggle="modal" data-target="#modal-delete"><i class="fal fa-trash-alt"></i></a>';
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = ModuleCategory::all()->pluck('name', 'uuid');
        return view('type.create', compact('category'));
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
            'name' => 'required',
            'module_brand' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $type = new ModuleType();
        $type->name = $request->name;
        $type->module_brand_uuid = $request->module_brand;
        $type->created_by = Auth::user()->uuid;

        $type->save();


        toastr()->success('New Type Added', 'Success');
        return redirect()->route('type.index');
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
        $type = ModuleType::uuid($id);
        $category = ModuleCategory::all()->pluck('name', 'uuid');
        return view('type.edit', compact('type', 'category'));
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
            'name' => 'required',
            'module_brand' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $type = ModuleType::uuid($id);
        $type->name = $request->name;
        $type->module_brand_uuid = $request->module_brand;
        $type->edited_by = Auth::user()->uuid;

        $type->save();


        toastr()->success('Type Edited', 'Success');
        return redirect()->route('type.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $type = ModuleType::uuid($id);
        $type->delete();
        toastr()->success('Type Deleted', 'Success');
        return redirect()->route('type.index');
    }

    public function GetModuleTypeByBrand()
    {
        if (request()->ajax()) {
            $module_type = ModuleType::where('module_brand_uuid', request('brand_uuid'))->pluck('name', 'uuid')->all();
            return response()->json($module_type);
        }
    }
}

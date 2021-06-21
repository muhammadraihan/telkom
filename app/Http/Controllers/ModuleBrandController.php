<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModuleName;
use App\Models\ModuleBrand;

use Auth;
use DataTables;
use DB;
use File;
use Hash;
use Image;
use Response;
use URL;

class ModuleBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brand = ModuleBrand::all();
            if (request()->ajax()) {
                $data = ModuleBrand::latest()->get();

                return Datatables::of($data)
                        ->addIndexColumn()
                        ->editColumn('created_by',function($row){
                            return $row->userCreate->name;
                        })
                        ->editColumn('edited_by',function($row){
                            return $row->userEdit->name ?? null;
                        })
                        ->editColumn('module_name_uuid', function($row){
                            return $row->nameModule->name;
                        })
                        ->addColumn('action', function($row){
                            return '
                            <a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="'.route('brand.edit',$row->uuid).'"><i class="fal fa-edit"></i></a>
                            <a class="btn btn-danger btn-sm btn-icon waves-effect waves-themed delete-btn" data-url="'.URL::route('brand.destroy',$row->uuid).'" data-id="'.$row->uuid.'" data-token="'.csrf_token().'" data-toggle="modal" data-target="#modal-delete"><i class="fal fa-trash-alt"></i></a>';
                    })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->rawColumns(['action'])
                ->make(true);
            }

            return view('brand.index');
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            $name = ModuleName::all()->pluck('name', 'uuid');
            return view('brand.create', compact('name'));
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
                'module_name_uuid' => 'required'
            ];

            $messages = [
                '*.required' => 'Field tidak boleh kosong !',
                '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
            ];

            $this->validate($request, $rules, $messages);

            $brand = new ModuleBrand();
            $brand->name = $request->name;
            $brand->module_name_uuid = $request->module_name_uuid;
            $brand->created_by = Auth::user()->uuid;

            $brand->save();        

            
            toastr()->success('New Brand Added','Success');
            return redirect()->route('brand.index');
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
            $brand = ModuleBrand::uuid($id);
            $name = ModuleName::all()->pluck('name', 'uuid');
            return view('brand.edit', compact('brand', 'name'));
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
                'module_name_uuid' => 'required'
            ];

            $messages = [
                '*.required' => 'Field tidak boleh kosong !',
                '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
            ];

            $this->validate($request, $rules, $messages);

            $brand = ModuleBrand::uuid($id);
            $brand->name = $request->name;
            $brand->module_name_uuid = $request->module_name_uuid;
            $brand->edited_by = Auth::user()->uuid;

            $brand->save();        

            
            toastr()->success('Brand Edited','Success');
            return redirect()->route('brand.index');
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function destroy($id)
        {
            $brand = ModuleBrand::uuid($id);
            $brand->delete();
            toastr()->success('Brand Deleted','Success');
            return redirect()->route('brand.index');
        }
}

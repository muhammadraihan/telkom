<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Witel;

use Auth;
use DataTables;
use DB;
use File;
use Hash;
use Image;
use Response;
use URL;

class WitelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $witel = Witel::all();
        if (request()->ajax()) {
            $data = Witel::latest()->get();

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
                        <a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="'.route('witel.edit',$row->uuid).'"><i class="fal fa-edit"></i></a>
                        <a class="btn btn-danger btn-sm btn-icon waves-effect waves-themed delete-btn" data-url="'.URL::route('witel.destroy',$row->uuid).'" data-id="'.$row->uuid.'" data-token="'.csrf_token().'" data-toggle="modal" data-target="#modal-delete"><i class="fal fa-trash-alt"></i></a>';
                 })
            ->removeColumn('id')
            ->removeColumn('uuid')
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('witel.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('witel.create');
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
            'name' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $witel = new Witel();
        $witel->name = $request->name;
        $witel->created_by = Auth::user()->uuid;

        $witel->save();

        
        toastr()->success('New Witel Added','Success');
        return redirect()->route('witel.index');
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
        $witel = Witel::uuid($id);
        return view('witel.edit', compact('witel'));
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
            'name' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $witel = Witel::uuid($id);
        $witel->name = $request->name;
        $witel->edited_by = Auth::user()->uuid;

        $witel->save();


        
        toastr()->success('Witel Edited','Success');
        return redirect()->route('witel.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $witel = Witel::uuid($id);
        $witel->delete();
        toastr()->success('Witel Deleted','Success');
        return redirect()->route('witel.index');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Technician_job_order;
use App\Models\Ticketing;
use App\Models\Repair_item;
use App\Models\Gudang_job_order;

use Auth;
use DataTables;
use DB;
use File;
use Hash;
use Image;
use Response;
use URL;

class TeknisiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ticketing = Ticketing::all();
        $repair_item = Repair_item::all();
        
            
        if (request()->ajax()) {
            
            DB::statement(DB::raw('set @rownum=0'));
            $data = Repair_item::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id','uuid','ticket_uuid','item_model','item_merk', 'item_type', 'part_number', 'serial_number', 'kelengkapan', 'kerusakan'])
            ->where('status_garansi', '=', '0')
            ->whereNull('can_repair');
            
            return Datatables::of($data)
                    ->addColumn('ticket_number', function($row){
                        return $row->ticket->ticket_number;
                    })
                    ->addColumn('keterangan', function($row){
                        return $row->ticket->keterangan;
                    })
                    ->addColumn('action', function($row){
                        return '<a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="'.route('teknisi.edit',$row->uuid).'"><i class="fal fa-edit"></i></a>';
                 })
            ->removeColumn('id')
            ->removeColumn('uuid')
            ->removeColumn('ticket_uuid')
            ->rawColumns(['action','kelengkapan'])
            ->make(true);
        }

        return view('teknisi.index');
    }

    /**
     * 
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
    public function store(Request $request, Repair_item $id)
    {
        
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
        // $teknisi = Technician_job_order::uuid($id);
        $repair_item = Repair_item::uuid($id);
        return view('teknisi.edit', compact('repair_item'));
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
            'repair_item_uuid' => 'required',
            'item_status' => 'required',
            'keterangan' => 'required'
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        $teknisi = new Technician_job_order();
        $teknisi->repair_item_uuid = $request->repair_item_uuid;
        $teknisi->item_status = $request->item_status;
        if ($teknisi->item_status == 1){
            $repair_item = DB::table('ticketings')
                            ->join('repair_items', 'repair_items.ticket_uuid', 'like', 'ticketings.uuid')
                            ->where('ticketings.ticket_number', '=', $teknisi->repair_item_uuid)
                            ->update(['repair_items.can_repair' => '1']);
            
            $ticketing = Ticketing::where('ticket_number', $teknisi->repair_item_uuid)->update(['job_status' => '5']);
        }else{
            $repair_item = DB::table('ticketings')
                            ->join('repair_items', 'repair_items.ticket_uuid', 'like', 'ticketings.uuid')
                            ->where('ticketings.ticket_number', '=', $teknisi->repair_item_uuid)
                            ->update(['repair_items.can_repair' => '0']);
            
            $ticketing = Ticketing::where('ticket_number', $teknisi->repair_item_uuid)->update(['job_status' => '1']);
        }
        $teknisi->keterangan = $request->keterangan;
        $teknisi->job_status = 1;
        // $teknisi->created_by = Auth::user()->uuid;

        $teknisi->save();   
        
        $gudang = new Gudang_job_order();
        $gudang->repair_item_uuid = $teknisi->repair_item_uuid;
        if ($teknisi->item_status == 1){
            $gudang->item_status = 4;
        }else{
            $gudang->item_status = 1;
        }
        $gudang->keterangan = $teknisi->keterangan;
        $gudang->item_replace_uuid = $request->item_replace_uuid;
        $gudang->job_status = 0;
        // $gudang->created_at = Auth::user()->uuid;

        $gudang->save();

        
        toastr()->success('Technician Job Order Edited','Success');
        return redirect()->route('teknisi.index');
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

<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\ModuleCategory;
use App\Models\RepairItem;
use App\Models\Ticketing;
use App\Models\WarehouseJobOrder;
use App\Models\Witel;
use Illuminate\Http\Request;

use Carbon\Carbon;

use Auth;
use DataTables;
use DB;
use Exception;
use Helper;
use URL;

class TicketingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $tickets = Ticketing::select(
                'id',
                'uuid',
                'uuid_unit',
                'ticket_number',
                'ticket_status',
                'job_status',
                'urgent_status',
                'note',
                'created_by',
                'created_at',
            )->latest()->get();

            return Datatables::of($tickets)
                ->addIndexColumn()
                ->addColumn('witel', function ($row) {
                    return $row->unit->witel->name;
                })
                ->editColumn('uuid_unit', function ($row) {
                    return $row->unit->name;
                })
                ->editColumn('created_by', function ($row) {
                    return $row->userCreate->name;
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->translatedFormat('l\\, j F Y H:i');
                })
                ->editColumn('ticket_status', function ($row) {
                    return Helper::TicketStatus($row->ticket_status);
                })
                ->editColumn('job_status', function ($row) {
                    return Helper::ItemStatus($row->job_status);
                })
                ->editColumn('urgent_status', function ($row) {
                    return Helper::UrgentStatus($row->urgent_status);
                })
                ->addColumn('action', function ($row) {
                    return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('ticketing.show', $row->uuid) . '" title="Detail Module" href=""><i class="fal fa-search-plus"></i></a>
                    <a class="btn btn-success btn-sm btn-icon waves-effect waves-themed" href="' . route('ticketing.edit', $row->uuid) . '" title="Edit Tiket"><i class="fal fa-edit"></i></a>';
                })
                ->removeColumn('id')
                ->removeColumn('uuid')
                ->rawColumns(['action', 'ticket_status', 'job_status', 'urgent_status'])
                ->make();
        }
        return view('ticketing.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accessories = Accessory::all();
        $witels = Witel::all()->pluck('name', 'uuid');
        $module_category = ModuleCategory::all()->pluck('name', 'uuid');
        return view('ticketing.create', compact('accessories', 'witels', 'module_category'));
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
            'witel' => 'required',
            'unit' => 'required',
            'module_category' => 'required',
            'module_name' => 'required',
            'module_brand' => 'required',
            'module_type' => 'required',
            'part_number' => 'required',
            'serial_number' => 'required',
            'serial_number_msc' => 'required',
            'warranty_status' => 'required',
            'urgent_status' => 'required',
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
            '*.min' => 'Nama tidak boleh kurang dari 2 karakter !',
        ];

        $this->validate($request, $rules, $messages);

        // get ticket number
        $generated_ticket_number = Helper::generateTicketNumber();
        DB::beginTransaction();
        try {
            // saving ticket
            $ticketing = new Ticketing();
            $ticketing->uuid_unit = $request->unit;
            $ticketing->ticket_number = $generated_ticket_number;
            $ticketing->created_by = Auth::user()->uuid;
            $ticketing->urgent_status = $request->urgent_status;
            // saving repair item detail
            $repair_item = new RepairItem();
            $repair_item->module_type_uuid = $request->module_type;
            $repair_item->part_number = $request->part_number;
            $repair_item->serial_number = $request->serial_number;
            $repair_item->serial_number_msc = $request->serial_number_msc;
            $repair_item->accessories = $request['accessories'];

            /**
             * if item is non warranty send job order to tech for repair
             * if item is warranty send job order to gudang for replace
             */
            if ($request->warranty_status == 0) {
                $ticketing->ticket_status = 1; // send to repair
                $ticketing->job_status = 0; // open status
                $ticketing->save();

                $repair_item->warranty_status = $request->warranty_status;
                $repair_item->ticket_uuid = $ticketing->uuid;
                $repair_item->created_by = Auth::user()->uuid;
                $repair_item->save();
            }
            if ($request->warranty_status == 1) {
                $ticketing->ticket_status = 2; // send to warehouse
                $ticketing->job_status = 4; // need warranty claim
                $ticketing->save();

                $repair_item->warranty_status = $request->warranty_status;
                $repair_item->ticket_uuid = $ticketing->uuid;
                $repair_item->created_by = Auth::user()->uuid;
                $repair_item->save();

                $warehouse = new WarehouseJobOrder();
                $warehouse->repair_item_uuid = $repair_item->uuid;
                $warehouse->item_status = 4; // need warranty claim
                $warehouse->job_status = 0; // open warehouse job order
                $warehouse->created_by = Auth::user()->uuid;
                $warehouse->save();
            }
        } catch (Exception $e) {
            // catch error and rollback database update
            DB::rollback();
            toastr()->error('Gagal menyimpan data, silahkan coba lagi', 'Error');
            return redirect()->back()->withInput();
        }
        // now is save to commit update and redirect to index
        DB::commit();
        toastr()->success('New Ticketing Added', 'Success');
        return redirect()->route('ticketing.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $repair_item = RepairItem::where('ticket_uuid', $uuid)->first();
        return view('ticketing.show', compact('repair_item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {
        $ticketing = Ticketing::uuid($uuid);
        $repair_item = RepairItem::where('ticket_uuid', $ticketing->uuid)->first();
        $module_category = ModuleCategory::all()->pluck('name', 'uuid');
        $witels = Witel::all();
        $accessories = Accessory::all();
        return view('ticketing.edit', compact('ticketing', 'repair_item', 'module_category', 'witels', 'accessories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $rules = [
            'witel' => 'required',
            'unit' => 'required',
            'module_category' => 'required',
            'module_name' => 'required',
            'module_brand' => 'required',
            'module_type' => 'required',
            'part_number' => 'required',
            'serial_number' => 'required',
            'serial_number_msc' => 'required',
        ];

        $messages = [
            '*.required' => 'Field tidak boleh kosong !',
        ];

        $this->validate($request, $rules, $messages);

        $ticketing = Ticketing::uuid($uuid);
        $ticketing->uuid_unit = $request->unit;
        $ticketing->edited_by = Auth::user()->uuid;
        $ticketing->save();

        $repair_item = RepairItem::where('ticket_uuid', '=', $uuid)->first();
        $repair_item->module_type_uuid = $request->module_type;
        $repair_item->part_number = $request->part_number;
        $repair_item->serial_number = $request->serial_number;
        $repair_item->serial_number_msc = $request->serial_number_msc;
        $repair_item->accessories = $request['accessories'];
        $repair_item->edited_by = Auth::user()->uuid;

        $repair_item->save();

        toastr()->success('Ticketing Number ' . $ticketing->ticket_number . ' Updated', 'Success');
        return redirect()->route('ticketing.index');
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

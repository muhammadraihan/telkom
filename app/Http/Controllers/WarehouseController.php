<?php

namespace App\Http\Controllers;

use App\Models\RepairItem;
use App\Models\Ticketing;
use App\Models\WarehouseJobOrder;
use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use DataTables;
use DB;
use Exception;
use File;
use Image;
use URL;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $job_orders = WarehouseJobOrder::select('uuid', 'repair_item_uuid', 'item_status', 'created_at')->where('job_status', 0)->latest()->get();

            return Datatables::of($job_orders)
                ->addIndexColumn()
                ->addColumn('ticket_number', function ($job_order) {
                    return  $job_order->repair->ticket->ticket_number;
                })
                ->addColumn('ticket_date', function ($job_order) {
                    return  Carbon::parse($job_order->repair->ticket->created_at)->translatedFormat('l\\, j F Y H:i');
                })
                ->addColumn('ticket_status', function ($job_order) {
                    switch ($job_order->repair->ticket->ticket_status) {
                        case 1:
                            return '<span class="badge badge-primary">Diproses ke bagian repair</span>';
                            break;
                        case 2:
                            return '<span class="badge badge-warning">Diproses ke bagian gudang</span>';
                            break;
                        case 3:
                            return '<span class="badge badge-success">Selesai</span>';
                            break;
                        case 4:
                            return '<span class="badge badge-danger">Cancel</span>';
                            break;
                        default:
                            return '<span class="badge badge-dark">Status Unknown</span>';
                            break;
                    }
                })
                ->editColumn('item_status', function ($job_order) {
                    switch ($job_order->item_status) {
                        case 0:
                            return '<span class="badge badge-secondary">None</span>';
                            break;
                        case 1:
                            return '<span class="badge badge-primary">Dalam penanganan oleh teknisi</span>';
                            break;
                        case 2:
                            return '<span class="badge badge-success">Telah diperbaiki oleh teknisi</span>';
                            break;
                        case 3:
                            return '<span class="badge badge-danger">Tidak dapat diperbaiki teknisi</span>';
                            break;
                        case 4:
                            return '<span class="badge badge-warning">Butuh klaim garansi</span>';
                            break;
                        case 5:
                            return '<span class="badge badge-warning">Butuh penggantian barang</span>';
                            break;
                        case 6:
                            return '<span class="badge badge-info">Dalam perbaikan oleh vendor</span>';
                            break;
                        case 7:
                            return '<span class="badge badge-info">Menunggu penggantian dari vendor</span>';
                            break;
                        case 8:
                            return '<span class="badge badge-success">Telah di kirim ke customer</span>';
                            break;
                        case 9:
                            return '<span class="badge badge-danger">Ticket di cancel</span>';
                            break;
                        default:
                            return '<span class="badge badge-dark">None</span>';
                            break;
                    }
                })
                ->addColumn('urgent_status', function ($job_order) {
                    switch ($job_order->repair->ticket->urgent_status) {
                        case 0:
                            return '<span class="badge badge-success">Not Urgent</span>';
                            break;
                        case 1:
                            return '<span class="badge badge-danger">Urgent</span>';
                            break;
                        default:
                            return '<span class="badge badge-dark">Status Unknown</span>';
                            break;
                    }
                })
                ->editColumn('created_at', function ($job_order) {
                    return  Carbon::parse($job_order->created_at)->translatedFormat('l\\, j F Y H:i');
                })
                ->addColumn('action', function ($job_order) {
                    return '<a class="btn btn-info btn-sm btn-icon waves-effect waves-themed" data-toggle="modal" id="detail-button" data-target="#detail-modal" data-attr="' . URL::route('warehouse.show', $job_order->uuid) . '" title="Detail Module" href=""><i class="fal fa-search-plus"></i></a>
                    <a class="btn btn-primary btn-sm btn-icon waves-effect waves-themed" href="' . route('warehouse.edit', $job_order->uuid) . '" title="Progress ticket"><i class="fal fa-wrench"></i></a>';
                })
                ->removeColumn('uuid')
                ->rawColumns(['action', 'ticket_status', 'item_status', 'urgent_status'])
                ->make();
        }
        return view('warehouse.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $detail_item = WarehouseJobOrder::uuid($uuid);
        return view('warehouse.show', compact('detail_item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {
        $job_order = WarehouseJobOrder::uuid($uuid);
        return view('warehouse.edit', compact('job_order'));
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
        // dd($request->all(), $uuid);
        $warehouse_job = WarehouseJobOrder::uuid($uuid);

        // item repaired by tech and ready to send
        if ($request->item_status == 8) {
            $rules = [
                'warehouse_notes' => 'required',
                'resi_image' => 'required|mimes:img,png,jpg,jpeg|max:2048'
            ];

            $messages = [
                '*.required' => 'Field tidak boleh kosong !',
                '*.mimes' => 'Format gambar harus img,png,jpg,jpeg',
                '*.max' => 'Ukuran gambar maximal 2MB',
            ];
            $this->validate($request, $rules, $messages);
            // get repair item detail
            $repair_item = RepairItem::where('uuid', $warehouse_job->repair_item_uuid)->first();
            DB::beginTransaction();
            try {
                if ($request->hasFile('resi_image')) {
                    $folder = public_path() . '/img' . '/resi' . '/';
                    if (!File::exists($folder)) {
                        File::makeDirectory($folder, 0755, true, true);
                    }
                    // request image files
                    $resi_image = $request->file('resi_image');
                    //upload image file
                    $filename = md5(uniqid(mt_rand(), true)) . '.' . $resi_image->getClientOriginalExtension();
                    $fitImage = Image::make($resi_image);
                    $fitImage->save($folder . $filename);
                    $request['avatar'] = $filename;
                    $warehouse_job->resi_image = $filename;
                }
                // save warehouse job order
                $warehouse_job->item_status = 8; // item sent to customer
                $warehouse_job->job_status = 1; // close warehouse job
                $warehouse_job->notes = $request->warehouse_notes;
                $warehouse_job->edited_by = Auth::user()->uuid;
                $warehouse_job->save();
                // update ticket
                $ticket = Ticketing::where('uuid', $repair_item->ticket->uuid)->first();
                $ticket->ticket_status = 3; // close ticket
                $ticket->job_status = 8; // sent to customer
                $ticket->edited_by = Auth::user()->uuid;
                $ticket->save();
            } catch (Exception $e) {
                // catch error and rollback database update
                DB::rollback();
                toastr()->error('Gagal menyimpan data, silahkan coba lagi', 'Error');
                return redirect()->back()->withInput();
            }
            // now is save to commit update and redirect to index
            DB::commit();
            toastr()->success('Ticket No.' . $repair_item->ticket->ticket_number . ' Telah di progress', 'Success');
            return redirect()->route('warehouse.index');
        }
    }
}

@extends('layouts.page')

@section('title', 'Technician Job Order Management')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/datagrid/datatables/datatables.bundle.css')}}">
@endsection

@section('content')
<div class="subheader">
    <h1 class="subheader-title">
        <i class='subheader-icon fal fa-wrench'></i> Module: <span class='fw-300'>Technician Job Order History</span>
        <small>
            Module for manage Technician Job Order History.
        </small>
    </h1>
</div>
<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Technician Job Order <span class="fw-300"><i>History</i></span>
                </h2>
                <div class="panel-toolbar">
                    <a class="nav-link active" href="{{route('teknisi.index')}}"><i class="fal fa-arrow-alt-left">
                        </i>
                        <span class="nav-link-text">Back</span>
                    </a>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip"
                        data-offset="0,10" data-original-title="Fullscreen"></button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <!-- datatable start -->
                    <table id="datatable" class="table table-bordered table-hover table-striped w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Ticket</th>
                                <th>Item Status</th>
                                <th>Keterangan</th>
                                <th>Job Status</th>
                                <th>Progress At</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/datagrid/datatables/datatables.bundle.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "order": [[ 0, "asc" ]],
            "ajax": {
                url:'{{route('teknisi.history')}}',
                type : "GET",
                dataType: 'json',
                error: function(data){
                    console.log(data);
                }
            },
            "columns": [
                {data: 'DT_RowIndex'},
                {data: 'ticket_number'},
                {data: 'item_status'},
                {data: 'keterangan'},
                {data: 'job_status'},
                {data: 'created_at'},
            ]
        });
    });
</script>
@endsection
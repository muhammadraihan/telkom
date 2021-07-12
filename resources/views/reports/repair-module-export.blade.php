@extends('layouts.page')

@section('title', 'Export')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/datagrid/datatables/datatables.bundle.css')}}">
@endsection

@section('content')
<div class="subheader">
    <h1 class="subheader-title">
        <i class='subheader-icon fal fa-users'></i> Export: <span class='fw-300'>Repair Module Data</span>
        <small>
            Module for export repair module data.
        </small>
    </h1>
</div>
<div class="row">
    <div class="col-lg-12 col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    REPAIR MODULE DATA
                </h2>
            </div>
            <div class="panel-container show">
                {!! Form::open(['route' => 'download.repair-module','method'=>'GET']) !!}
                <div class="panel-content">
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            <button id="cetak" class="btn btn-outline-primary waves-effect waves-themed ml-auto"
                                type="submit" formtarget="_blank"><span class="fal fa-file-excel mr-1"></span>Export
                                Laporan</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/formplugins/select2/select2.bundle.js')}}"></script>
<script>
    $(document).ready(function(){
        $('.select2').select2();
    });
</script>
@endsection
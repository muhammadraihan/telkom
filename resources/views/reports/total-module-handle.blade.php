@extends('layouts.page')

@section('title', 'Export')

@section('css')
<link rel="stylesheet" media="screen, print"
    href="{{asset('css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css')}}">
@endsection

@section('content')
<div class="subheader">
    <h1 class="subheader-title">
        <i class='subheader-icon fal fa-file-excel'></i> Report: <span class='fw-300'>Total Module Handle</span>
    </h1>
</div>
<div class="row">
    <div class="col-lg-12 col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    MODULE HANDLE
                </h2>
            </div>
            <div class="panel-container show">
                {!! Form::open(['route' => 'download.total-module-handle','method'=>'POST']) !!}
                <div class="panel-content">
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('filter','Tahun:',['class' => 'form-label'])}}
                            <div class="input-group">
                                <input type="text" class="form-control" id="year-picker" name="year"
                                    placeholder="Select Year">
                                <div class="input-group-append">
                                    <span class="input-group-text fs-xl">
                                        <i class="fal fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('filter','Bulan:',['class' => 'form-label'])}}
                            <div class="input-group">
                                <input type="text" class="form-control" id="month-picker" name="month"
                                    placeholder="Select Month">
                                <div class="input-group-append">
                                    <span class="input-group-text fs-xl">
                                        <i class="fal fa-calendar"></i>
                                    </span>
                                </div>
                                <div class="text-danger">
                                    <p>Jika menggunakan filter bulan, harap juga memilih tahun agar hasil
                                        laporan lebih spesifik.</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
<script src="{{asset('js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
<script>
    $(document).ready(function(){
        $('.select2').select2();
        $('#year-picker').datepicker({
            format: " yyyy", // Notice the Extra space at the beginning
            viewMode: "years",
            minViewMode: "years",
            todayHighlight: true,
            orientation: "bottom left",
            autoclose: true,
            clearBtn: true,
        });
        $('#month-picker').datepicker({
            format: " mm", // Notice the Extra space at the beginning
            viewMode: "months",
            minViewMode: "months",
            todayHighlight: true,
            orientation: "bottom left",
            autoclose: true,
            clearBtn: true,
        });
    });
</script>
@endsection
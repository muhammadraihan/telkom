@extends('layouts.page')

@section('title', 'Ticketing Create')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/select2/select2.bundle.css')}}">
<link rel="stylesheet" media="screen, print" href="{{asset('css/vendors.bundle.css')}}">
<link rel="stylesheet" media="screen, print" href="{{asset('css/app.bundle.css')}}">

@endsection

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>Add/Search<span class="fw-300"><i>Customer</i></span></h2>
                <div class="panel-toolbar">
                    <a class="nav-link active" href="{{route('ticketing.index')}}"><i class="fal fa-arrow-alt-left">
                        </i>
                        <span class="nav-link-text">Back</span>
                    </a>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip"
                        data-offset="0,10" data-original-title="Fullscreen"></button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="panel-tag">
                        Form with <code>*</code> can not be empty.
                    </div>
                    {!! Form::open(['route' => 'ticketing.store','method' => 'POST','class' =>
                    'needs-validation','novalidate']) !!}
                    <div class="form-group">
                        {{ Form::label('uuid_pelanggan','Nomor Pelanggan',['class' => 'required form-label'])}}
                        <div class="input-group">
                            <div class="input-group-prepend col-md-4">
                                {!! Form::select('uuid_pelanggan', $pelanggan, '', ['id' => 'pelanggan','class' =>
                                'form-control'.($errors->has('uuid_pelanggan') ? 'is-invalid':''), 'required'
                                => '', 'placeholder' => 'Pilih Pelanggan']) !!}
                            </div>
                            <button type="button" class="btn btn-primary waves-effect waves-themed" data-toggle="modal"
                                data-target="#customer-modal">Input Baru</button>
                        </div>
                        @if ($errors->has('uuid_pelanggan'))
                        <div class="help-block text-danger">{{ $errors->first('uuid_pelanggan') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div id="panel-2" class="panel">
            <div class="panel-hdr">
                <h2>Add<span class="fw-300"><i>Item Data</i></span></h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip"
                        data-offset="0,10" data-original-title="Fullscreen"></button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="panel-tag">
                        Form with <code>*</code> can not be empty.
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('item_merk','Item Merk',['class' => 'required form-label'])}}
                            {{ Form::text('item_merk', '',['placeholder' => 'Item Merk','class' => 'form-control '.($errors->has('item_merk') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                            @if ($errors->has('item_merk'))
                            <div class="invalid-feedback">{{ $errors->first('item_merk') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('item_type','Item Type',['class' => 'required form-label'])}}
                            {{ Form::text('item_type', '',['placeholder' => 'Item Type','class' => 'form-control '.($errors->has('item_type') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                            @if ($errors->has('item_type'))
                            <div class="invalid-feedback">{{ $errors->first('item_type') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('item_model','Item Model',['class' => 'required form-label'])}}
                            {{ Form::text('item_model', '',['placeholder' => 'Item Model','class' => 'form-control '.($errors->has('item_model') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                            @if ($errors->has('item_model'))
                            <div class="invalid-feedback">{{ $errors->first('item_model') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('serial_number','Serial Number',['class' => 'required form-label'])}}
                            {{ Form::text('serial_number', '',['placeholder' => 'Serial Number','class' => 'form-control '.($errors->has('serial_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                            @if ($errors->has('serial_number'))
                            <div class="invalid-feedback">{{ $errors->first('serial_number') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('part_number','Part Number',['class' => 'required form-label'])}}
                            {{ Form::text('part_number', '',['placeholder' => 'Part Number','class' => 'form-control '.($errors->has('part_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                            @if ($errors->has('part_number'))
                            <div class="invalid-feedback">{{ $errors->first('part_number') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('barcode','Barcode',['class' => 'required form-label'])}}
                            {{ Form::text('barcode', '',['placeholder' => 'Barcode','class' => 'form-control '.($errors->has('barcode') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                            @if ($errors->has('barcode'))
                            <div class="invalid-feedback">{{ $errors->first('barcode') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-md-12 mb-3">
                        {{ Form::label('kelengkapan','Kelengkapan',['class' => 'form-label'])}}
                        <div class="frame-wrap">
                            @foreach($kelengkapan as $item)
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="{{$item->name}}"
                                    name="kelengkapan[]" value="{{$item->name}}">
                                <label class="custom-control-label" for="{{$item->name}}">{{$item->name}}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('status_garansi','Status Garansi',['class' => 'required form-label'])}}
                        {!! Form::select('status_garansi', array('0' => 'Non Warranty', '1' => 'Warranty'), '',
                        ['id'=>'garansi','class'
                        => 'custom-select'.($errors->has('status_garansi') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select Status garansi ...']) !!}
                        @if ($errors->has('status_garansi'))
                        <div class="help-block text-danger">{{ $errors->first('status_garansi') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('kerusakan','Kerusakan',['class' => 'required form-label'])}}
                        {{ Form::textarea('kerusakan', '',['placeholder' => 'Kerusakan','class' => 'form-control '.($errors->has('kerusakan') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('kerusakan'))
                        <div class="invalid-feedback">{{ $errors->first('kerusakan') }}</div>
                        @endif
                    </div>
                    <div
                        class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                        <button id="submit-ticket" class="btn btn-primary ml-auto" type="submit">Submit</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- customer add modal start -->
    <div class="modal fade" id="customer-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Add New Customer
                        <small class="m-0 text-muted">
                            Form with <code>*</code> can not be empty.
                        </small>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                {!! Form::open(['route' => 'post.customer','method' => 'POST','class' =>
                'needs-validation','novalidate']) !!}
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('jenis_pelanggan','Jenis Pelanggan',['class' => 'required form-label'])}}
                            {!! Form::select('jenis_pelanggan', $customerType, '', ['id' =>
                            'jenis-pelanggan','class' => 'form-control'.($errors->has('jenis_pelanggan') ?
                            'is-invalid':''), 'required'
                            => '', 'placeholder' => 'Jenis pelanggan ...']) !!}
                            @if ($errors->has('jenis_pelanggan'))
                            <div class="help-block text-danger">{{ $errors->first('jenis_pelanggan') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('nomor_pelanggan','Nomor Pelanggan',['class' => 'required form-label'])}}
                            {{ Form::text('nomor_pelanggan',null,['placeholder' => 'Nomor Pelanggan','class' => 'form-control '.($errors->has('nomor_pelanggan') ? 'is-invalid':''),'required'])}}
                            @if ($errors->has('nomor_pelanggan'))
                            <div class="invalid-feedback">{{ $errors->first('nomor_pelanggan') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button id="save-customer" type="submit" class="btn btn-primary">Add New</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- customer add modal end -->
    @endsection
    @section('js')
    <script src="{{asset('js/formplugins/select2/select2.bundle.js')}}"></script>
    <script>
        $(document).ready(function(){
                $('#pelanggan').select2();
                $('#garansi').select2();

                // adding select inside modal
                $('#jenis-pelanggan').select2({
                    dropdownParent: $("#customer-modal"),
                });
                // dismis data when modal close
                $('#customer-modal').on('hidden.bs.modal', function (e) {
                    $(this).find("input").val('').end();
                    $("#jenis-pelanggan").val('').trigger('change')
                });

            });

            @if ($errors->has('jenis_pelanggan') || $errors->has('nomor_pelanggan'))
                $('#customer-modal').modal('show');
            @endif

    </script>
    @endsection
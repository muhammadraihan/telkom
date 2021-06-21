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
                <h2>Add<span class="fw-300"><i>Customer</i></span></h2>
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
                    <div class="form-row">
                        <div class="form-group col-md-3 mb-3">
                            {{ Form::label('witel','Witel',['class' => 'required form-label'])}}
                            {!! Form::select('witel', $witels, '', ['id' => 'witel','class' =>
                            'form-control'.($errors->has('witel') ? 'is-invalid':''), 'required'
                            => '', 'placeholder' => 'Pilih Witel']) !!}
                            @if ($errors->has('witel'))
                            <div class="help-block text-danger">{{ $errors->first('witel') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-3 mb-3">
                            {{ Form::label('unit','Unit',['class' => 'required form-label'])}}
                            <select id="unit" class="form-control select2" name="unit">
                            </select>
                            @if ($errors->has('unit'))
                            <div class="help-block text-danger">{{ $errors->first('unit') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div id="panel-2" class="panel">
            <div class="panel-hdr">
                <h2>Add<span class="fw-300"><i>Module Data</i></span></h2>
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
                        <div class="form-group col-md-3 mb-3">
                            {{ Form::label('module_category','Module Category',['class' => 'required form-label'])}}
                            {!! Form::select('module_category', $module_category, '', ['id' =>
                            'module_category','class' =>
                            'form-control'.($errors->has('module_category') ? 'is-invalid':''), 'required'
                            => '', 'placeholder' => 'Pilih Module Category']) !!} @if ($errors->has('module_category'))
                            <div class="help-block text-danger">{{ $errors->first('module_category') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-3 mb-3">
                            {{ Form::label('module_name','Module Name',['class' => 'required form-label'])}}
                            <select id="module_name" class="form-control select2" name="module_name">
                            </select>
                            @if ($errors->has('module_name'))
                            <div class="help-block text-danger">{{ $errors->first('module_name') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-3 mb-3">
                            {{ Form::label('module_brand','Module Brand',['class' => 'required form-label'])}}
                            <select id="module_brand" class="form-control select2" name="module_brand">
                            </select>
                            @if ($errors->has('module_brand'))
                            <div class="help-block text-danger">{{ $errors->first('module_brand') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-3 mb-3">
                            {{ Form::label('module_type','Module type',['class' => 'required form-label'])}}
                            <select id="module_type" class="form-control select2" name="module_type">
                            </select>
                            @if ($errors->has('module_type'))
                            <div class="help-block text-danger">{{ $errors->first('module_type') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('part_number','Part Number',['class' => 'required form-label'])}}
                            {{ Form::text('part_number', '',['placeholder' => 'Part Number','class' => 'form-control '.($errors->has('part_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                            @if ($errors->has('part_number'))
                            <div class="invalid-feedback">{{ $errors->first('part_number') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('serial_number','Serial Number',['class' => 'required form-label'])}}
                            {{ Form::text('serial_number', '',['placeholder' => 'Serial Number','class' => 'form-control '.($errors->has('serial_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                            @if ($errors->has('serial_number'))
                            <div class="invalid-feedback">{{ $errors->first('serial_number') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('serial_number_msc','Serial Number MSC',['class' => 'required form-label'])}}
                            {{ Form::text('serial_number_msc', '',['placeholder' => 'Serial Number MSC','class' => 'form-control '.($errors->has('serial_number_msc') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                            @if ($errors->has('serial_number_msc'))
                            <div class="invalid-feedback">{{ $errors->first('serial_number_msc') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-md-12 mb-3">
                        {{ Form::label('accessories','Accessories',['class' => 'form-label'])}}
                        <div class="frame-wrap">
                            @foreach($accessories as $item)
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="{{$item->name}}"
                                    name="accessories[]" value="{{$item->name}}">
                                <label class="custom-control-label" for="{{$item->name}}">{{$item->name}}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('warranty_status','Status Garansi',['class' => 'required form-label'])}}
                        {!! Form::select('warranty_status', array('0' => 'Non Warranty', '1' => 'Warranty'), '',
                        ['id'=>'garansi','class'
                        => 'custom-select'.($errors->has('warranty_status') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select Status garansi ...']) !!}
                        @if ($errors->has('warranty_status'))
                        <div class="help-block text-danger">{{ $errors->first('warranty_status') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('urgent_status','Status Garansi',['class' => 'required form-label'])}}
                        {!! Form::select('urgent_status', array('0' => 'Not Urgent', '1' => 'Urgent'), '',
                        ['id'=>'urgent','class'
                        => 'custom-select'.($errors->has('urgent_status') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select Status Urgensi ...']) !!}
                        @if ($errors->has('urgent_status'))
                        <div class="help-block text-danger">{{ $errors->first('urgent_status') }}</div>
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
</div>
@endsection
@section('js')
<script src="{{asset('js/formplugins/select2/select2.bundle.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#witel').select2();
        $('#garansi').select2();
        $('#urgent').select2();
        $('#unit').select2();
        $('#module_category').select2();
        $('#module_name').select2();
        $('#module_brand').select2();
        $('#module_type').select2();

        $("#witel").change(function(){
            var witel_uuid = $(this).val();
            $.ajax({
                url:"{{route('getUnit')}}",
                type: 'GET',
                data: {witel_uuid:witel_uuid},
                success: function(e) {
                    $("#unit").empty();
                    $("#unit").append('<option value="">Pilih Module Unit</option>');
                    $.each(e, function(key, value) {
                        $("#unit").append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        });
        $("#module_category").change(function(){
            var category_uuid = $(this).val();
            $.ajax({
                url:"{{route('getModuleName')}}",
                type: 'GET',
                data: {category_uuid:category_uuid},
                success: function(e) {
                    $("#module_name").empty();
                    $("#module_name").append('<option value="">Pilih Module Name</option>');
                    $.each(e, function(key, value) {
                        $("#module_name").append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        });
        $("#module_name").change(function(){
            var name_uuid = $(this).val();
            $.ajax({
                url:"{{route('getModuleBrand')}}",
                type: 'GET',
                data: {name_uuid:name_uuid},
                success: function(e) {
                    $("#module_brand").empty();
                    $("#module_brand").append('<option value="">Pilih Module Brand</option>');
                    $.each(e, function(key, value) {
                        $("#module_brand").append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        });
        $("#module_brand").change(function(){
            var brand_uuid = $(this).val();
            $.ajax({
                url:"{{route('getModuleType')}}",
                type: 'GET',
                data: {brand_uuid:brand_uuid},
                success: function(e) {
                    $("#module_type").empty();
                    $("#module_type").append('<option value="">Pilih Module Type</option>');
                    $.each(e, function(key, value) {
                        $("#module_type").append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        });
    });
</script>
@endsection
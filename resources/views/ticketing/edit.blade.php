@extends('layouts.page')

@section('title', 'Ticketing Edit')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/select2/select2.bundle.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>Edit Tiket Number - <span class="fw-300"><i>{{$ticketing->ticket_number}}</i></span></h2>
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
                    {!! Form::open(['route' => ['ticketing.update',$ticketing->uuid],'method' => 'PUT','class'
                    =>
                    'needs-validation','novalidate']) !!}
                    <div class="form-row">
                        <div class="form-group col-md-3 mb-3">
                            {{ Form::label('witel','Witel',['class' => 'required form-label'])}}
                            <select id="witel" class="form-control" name="witel">
                                <option value="{{$ticketing->unit->witel->uuid}}">{{$ticketing->unit->witel->name}}
                                </option>
                                @foreach($witels as $witel)
                                <option value="{{$witel->uuid}}">{{$witel->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('witel'))
                            <div class="help-block text-danger">{{ $errors->first('witel') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-3 mb-3">
                            {{ Form::label('unit','Unit',['class' => 'required form-label'])}}
                            <select id="unit" class="form-control" name="unit">
                                <option value="{{$ticketing->unit->uuid}}">{{$ticketing->unit->name}}
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
</div>

<div class="col-xl-6">
    <div id="panel-2" class="panel">
        <div class="panel-hdr">
            <h2>Edit<span class="fw-300"><i>Module Data</i></span></h2>
            <div class="panel-toolbar">
                <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10"
                    data-original-title="Fullscreen"></button>
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
                        {!! Form::select('module_category', $module_category, $repair_item->ModuleCategory->uuid, ['id'
                        =>
                        'module_category','class' =>
                        'form-control'.($errors->has('module_category') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Pilih Module Category']) !!} @if ($errors->has('module_category'))
                        <div class="help-block text-danger">{{ $errors->first('module_category') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-3 mb-3">
                        {{ Form::label('module_name','Module Name',['class' => 'required form-label'])}}
                        <select id="module_name" class="form-control" name="module_name">
                            <option value="{{$repair_item->ModuleName->uuid}}">{{$repair_item->ModuleName->name}}
                        </select>
                        @if ($errors->has('module_name'))
                        <div class="help-block text-danger">{{ $errors->first('module_name') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-3 mb-3">
                        {{ Form::label('module_brand','Module Brand',['class' => 'required form-label'])}}
                        <select id="module_brand" class="form-control" name="module_brand">
                            <option value="{{$repair_item->ModuleBrand->uuid}}">{{$repair_item->ModuleBrand->name}}
                        </select>
                        @if ($errors->has('module_brand'))
                        <div class="help-block text-danger">{{ $errors->first('module_brand') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-3 mb-3">
                        {{ Form::label('module_type','Module type',['class' => 'required form-label'])}}
                        <select id="module_type" class="form-control" name="module_type">
                            <option value="{{$repair_item->ModuleType->uuid}}">{{$repair_item->ModuleType->name}}
                        </select>
                        @if ($errors->has('module_type'))
                        <div class="help-block text-danger">{{ $errors->first('module_type') }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('part_number','Part Number',['class' => 'required form-label'])}}
                        {{ Form::text('part_number', $repair_item->part_number,['class' => 'form-control '.($errors->has('part_number') ? 'is-invalid':''),'required'])}}
                        @if ($errors->has('part_number'))
                        <div class="invalid-feedback">{{ $errors->first('part_number') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('serial_number','Serial Number',['class' => 'required form-label'])}}
                        {{ Form::text('serial_number', $repair_item->serial_number,['class' => 'form-control '.($errors->has('serial_number') ? 'is-invalid':''),'required'])}}
                        @if ($errors->has('serial_number'))
                        <div class="invalid-feedback">{{ $errors->first('serial_number') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('serial_number_msc','Serial Number MSC',['class' => 'required form-label'])}}
                        {{ Form::text('serial_number_msc', $repair_item->serial_number_msc,['class' => 'form-control '.($errors->has('serial_number_msc') ? 'is-invalid':''),'required'])}}
                        @if ($errors->has('serial_number_msc'))
                        <div class="invalid-feedback">{{ $errors->first('serial_number_msc') }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-group col-md-12 mb-3">
                    {{ Form::label('kelengkapan','Kelengkapan',['class' => 'form-label'])}}
                    <div class="frame-wrap">
                        @foreach($accessories as $item)
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" class="custom-control-input" id="{{$item->name}}"
                                name="accessories[]" value="{{$item->name}}" @if (is_array($repair_item->accessories))
                            {{in_array($item->name,$repair_item->accessories) ? 'checked':''}} @endif>
                            <label class="custom-control-label" for="{{$item->name}}">{{$item->name}}</label>
                        </div>
                        @endforeach
                    </div>
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


@endsection

@section('js')
<script src="{{asset('js/formplugins/select2/select2.bundle.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#witel').select2();
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
                    $("#module_brand").empty();
                    $("#module_type").empty();
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
                    $("#module_type").empty();
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
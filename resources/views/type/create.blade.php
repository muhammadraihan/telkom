@extends('layouts.page')

@section('title', 'Module Type Create')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/select2/select2.bundle.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2></h2>
                <div class="panel-toolbar">
                    <a class="nav-link active" href="{{route('type.index')}}"><i class="fal fa-arrow-alt-left">
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
                    {!! Form::open(['route' => 'type.store','method' => 'POST','class' =>
                    'needs-validation','novalidate']) !!}
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('module_category_uuid','Module Category',['class' => 'required form-label'])}}
                            {!! Form::select('module_category_uuid', $category, '', ['id' => 'module_category','class'
                            => 'category
                            form-control'.($errors->has('module_category_uuid') ? 'is-invalid':''), 'required'
                            => '', 'placeholder' => 'Select Module Category ...']) !!}
                            @if ($errors->has('module_category_uuid'))
                            <div class="invalid-feedback">{{ $errors->first('module_category_uuid') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('module_name','Module Name',['class' => 'required form-label'])}}
                            <select id="module_name" class="name form-control select2" name="module_name">
                            </select>
                            @if ($errors->has('module_name'))
                            <div class="help-block text-danger">{{ $errors->first('module_name') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('module_brand','Module Brand',['class' => 'required form-label'])}}
                            <select id="module_brand" class="brand form-control select2" name="module_brand">
                            </select>
                            @if ($errors->has('module_brand'))
                            <div class="help-block text-danger">{{ $errors->first('module_brand') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('name','Module Type',['class' => 'required form-label'])}}
                        {{ Form::text('name',null,['placeholder' => 'Module Type','class' => 'form-control '.($errors->has('name') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('name'))
                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <div
                        class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                        <button class="btn btn-primary ml-auto" type="submit">Submit</button>
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
        $('.brand').select2();
        $('.name').select2();
        $('.category').select2();
        $("#module_category").change(function(){
            var category_uuid = $(this).val();
            $.ajax({
                url:"{{route('getModuleName')}}",
                type: 'GET',
                data: {category_uuid:category_uuid},
                success: function(e) {
                    $("#module_name").empty();
                    $("#module_brand").empty();
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
    });
</script>
@endsection
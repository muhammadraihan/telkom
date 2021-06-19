@extends('layouts.page')

@section('title', 'Module Stock Edit')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/select2/select2.bundle.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
            <h2>Edit <span class="fw-300"><i>{{$stock->nameModule->name}}</i></span></h2>
                <div class="panel-toolbar">
                    <a class="nav-link active" href="{{route('stock.index')}}"><i class="fal fa-arrow-alt-left">
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
                    {!! Form::open(['route' => ['stock.update',$stock->uuid],'method' => 'PUT','class' =>
                    'needs-validation','novalidate']) !!}
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('module_category_uuid','Nama Kategori',['class' => 'required form-label'])}}
                        {!! Form::select('module_category_uuid', $category, $stock->module_category_uuid, ['class' => 'category
                        form-control'.($errors->has('module_category_uuid') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select Nama Kategori ...']) !!}
                        @if ($errors->has('module_category_uuid'))
                        <div class="invalid-feedback">{{ $errors->first('module_category_uuid') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('module_name_uuid','Nama Module',['class' => 'required form-label'])}}
                        {!! Form::select('module_name_uuid', $name, $stock->module_name_uuid, ['class' => 'name
                        form-control'.($errors->has('module_name_uuid') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select Nama Module ...']) !!}
                        @if ($errors->has('module_name_uuid'))
                        <div class="invalid-feedback">{{ $errors->first('module_name_uuid') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('module_brand_uuid','Nama Brand',['class' => 'required form-label'])}}
                        {!! Form::select('module_brand_uuid', $brand, $stock->module_brand_uuid, ['class' => 'brand
                        form-control'.($errors->has('module_brand_uuid') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select Nama Brand ...']) !!}
                        @if ($errors->has('module_brand_uuid'))
                        <div class="invalid-feedback">{{ $errors->first('module_brand_uuid') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('module_type_uuid','Nama Type',['class' => 'required form-label'])}}
                        {!! Form::select('module_type_uuid', $type, $stock->module_type_uuid, ['class' => 'type
                        form-control'.($errors->has('module_type_uuid') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select Nama Type ...']) !!}
                        @if ($errors->has('module_type_uuid'))
                        <div class="invalid-feedback">{{ $errors->first('module_type_uuid') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('available','Available',['class' => 'required form-label'])}}
                        {{ Form::text('available',$stock->available,['placeholder' => 'Available','class' => 'form-control '.($errors->has('available') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('available'))
                        <div class="invalid-feedback">{{ $errors->first('available') }}</div>
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
@endsection

@section('js')
<script src="{{asset('js/formplugins/select2/select2.bundle.js')}}"></script>
<script>
    $(document).ready(function(){
        $('.category').select2();
        $('.brand').select2();
        $('.name').select2();
        $('.type').select2();
        
        // Generate a password string
        function randString(){
            var chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNP123456789";
            var string_length = 8;
            var randomstring = '';
            for (var i = 0; i < string_length; i++) {
                var rnum = Math.floor(Math.random() * chars.length);
                randomstring += chars.substring(rnum, rnum + 1);
            }
            return randomstring;
        }
        
        // Create a new password
        $(".getNewPass").click(function(){
            var field = $('#password').closest('div').find('input[name="password"]');
            field.val(randString(field));
        });

        //Enable input and button change password
        $('#enablePassChange').click(function() {
            if ($(this).is(':checked')) {
                $('#passwordForm').attr('disabled',false); //enable input
                $('#getNewPass').attr('disabled',false); //enable button
            } else {
                    $('#passwordForm').attr('disabled', true); //disable input
                    $('#getNewPass').attr('disabled', true); //disable button
            }
        });
    });
</script>
@endsection
@extends('layouts.page')

@section('title', 'Material Edit')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/select2/select2.bundle.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
            <h2>Edit <span class="fw-300"><i>{{$material->nameModule->name}}</i></span></h2>
                <div class="panel-toolbar">
                    <a class="nav-link active" href="{{route('material.index')}}"><i class="fal fa-arrow-alt-left">
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
                    {!! Form::open(['route' => ['material.update',$material->uuid],'method' => 'PUT','class' =>
                    'needs-validation','novalidate']) !!}
                    <div class="form-row">
                    <div class="form-group col-md-6 mb-3">
                        {{ Form::label('module_category_uuid','Module Category',['class' => 'required form-label'])}}
                        {!! Form::select('module_category_uuid', $category, $material->module_category_uuid, ['id' => 'module_category','class' => 'category
                        form-control'.($errors->has('module_category_uuid') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select Module Category ...']) !!}
                        @if ($errors->has('module_category_uuid'))
                        <div class="invalid-feedback">{{ $errors->first('module_category_uuid') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        {{ Form::label('module_name','Module Name',['class' => 'required form-label'])}}
                        <select id="module_name" class="name form-control select2" name="module_name">
                            <option value="{{$material->nameModule->uuid}}">{{$material->nameModule->name}}
                        </select>
                        @if ($errors->has('module_name'))
                        <div class="help-block text-danger">{{ $errors->first('module_name') }}</div>
                        @endif
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-3 mb-3">
                        {{ Form::label('material_type','Jenis Material',['class' => 'required form-label'])}}
                        {{ Form::text('material_type', $material->material_type,['placeholder' => 'Deskripsi Material','class' => 'form-control '.($errors->has('material_type') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('material_type'))
                        <div class="invalid-feedback">{{ $errors->first('material_type') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-3 mb-3">
                        {{ Form::label('material_description','Deskripsi Material',['class' => 'required form-label'])}}
                        {{ Form::text('material_description', $material->material_description,['placeholder' => 'Deskripsi Material','class' => 'form-control '.($errors->has('material_description') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('material_description'))
                        <div class="invalid-feedback">{{ $errors->first('material_description') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-3 mb-3">
                        {{ Form::label('volume','Volume',['class' => 'required form-label'])}}
                        {!! Form::select('volume', array('1' => 'Buah', '2' => 'Kotak'), $material->volume, ['class' => 'volume form-control'.($errors->has('volume') ?
                        'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select Volume ...']) !!}
                        @if ($errors->has('volume'))
                        <div class="invalid-feedback">{{ $errors->first('volume') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-3 mb-3">
                        {{ Form::label('available','Available',['class' => 'required form-label'])}}
                        {{ Form::text('available', $material->available,['placeholder' => 'Available','class' => 'form-control '.($errors->has('available') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('available'))
                        <div class="invalid-feedback">{{ $errors->first('available') }}</div>
                        @endif
                    </div>
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('unit_price','Harga Satuan',['class' => 'required form-label'])}}
                        <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        Rp.
                                    </span>
                                </div>
                        {{ Form::text('unit_price', $material->unit_price,['placeholder' => '','class' => 'form-control '.($errors->has('unit_price') ? 'is-invalid':''),'required', 'autocomplete' => 'off', 'data-inputmask' => "'alias': 'currency','prefix': ''"])}}
                        @if ($errors->has('unit_price'))
                        <div class="invalid-feedback">{{ $errors->first('unit_price') }}</div>
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
<script src="{{asset('js/formplugins/inputmask/inputmask.bundle.js')}}"></script>
<script>
    $(document).ready(function(){
        $('.category').select2();
        $('.name').select2();
        $('.volume').select2();

        $(':input').inputmask();

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
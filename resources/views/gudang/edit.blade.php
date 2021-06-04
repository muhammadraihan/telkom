@extends('layouts.page')

@section('title', 'Gudang Job Order Edit')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/select2/select2.bundle.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
            <h2>Edit <span class="fw-300"><i>{{$gudang->repair_item_uuid}}</i></span></h2>
                <div class="panel-toolbar">
                    <a class="nav-link active" href="{{route('gudang.index')}}"><i class="fal fa-arrow-alt-left">
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
                    {!! Form::open(['route' => ['gudang.update',$gudang->uuid],'method' => 'PUT','class' =>
                    'needs-validation','novalidate']) !!}
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('repair_item_uuid','Ticket Number',['class' => 'required form-label'])}}
                        {{ Form::text('repair_item_uuid', $gudang->repair_item_uuid,['placeholder' => 'Ticket Number','class' => 'form-control '.($errors->has('repair_item_uuid') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('repair_item_uuid'))
                        <div class="invalid-feedback">{{ $errors->first('repair_item_uuid') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_status','Status Item',['class' => 'required form-label'])}}
                        {!! Form::select('item_status', array('1' => 'Butuh perbaikan dari vendor', '2' => 'Menunggu perbaikan dari vendor', '3' => 'Menunggu penggantian dari vendor',
                        '4' => 'Item telah diperbaiki oleh teknisi', '5' => 'Item telah digantioleh vendor'), $gudang->item_status, ['class' => 'garansi form-control'.($errors->has('item_status') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select Status item ...']) !!}
                        @if ($errors->has('item_status'))
                        <div class="invalid-feedback">{{ $errors->first('item_status') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('keterangan','Keterangan',['class' => 'required form-label'])}}
                        {{ Form::text('keterangan', $gudang->keterangan,['placeholder' => 'Keterangan','class' => 'form-control '.($errors->has('keterangan') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('keterangan'))
                        <div class="invalid-feedback">{{ $errors->first('keterangan') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_replace_uuid','Replace Item',['class' => 'form-label'])}}
                        {!! Form::select('item_replace_uuid', $item_replace, $gudang->item_replace_uuid, ['class' => 'item-replace
                        form-control', 'readonly'.($errors->has('item_replace_uuid') ? 'is-invalid':''), ''
                        => '', 'placeholder' => 'Select Replace Item ...']) !!}
                        @if ($errors->has('item_replace_uuid'))
                        <div class="invalid-feedback">{{ $errors->first('item_replace_uuid') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('job_status','Job Status',['class' => 'required form-label'])}}
                        {!! Form::select('job_status', array('0' => 'Open', '1' => 'Closed'), $gudang->job_status, ['class' => 'garansi form-control'.($errors->has('job_status') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select Status item ...']) !!}
                        @if ($errors->has('job_status'))
                        <div class="invalid-feedback">{{ $errors->first('job_status') }}</div>
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
        $('.pelanggan').select2();
        $('.item-replace').select2();
        $('.job').select2();
        $('.garansi').select2();
        $('.repair').select2();
        
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
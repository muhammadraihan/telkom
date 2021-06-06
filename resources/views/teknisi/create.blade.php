@extends('layouts.page')

@section('title', 'Technician Job Order Create')

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
                <h2>Add New <span class="fw-300"><i>Technician Job Order</i></span></h2>
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
                    <div class="panel-tag">
                        Form with <code>*</code> can not be empty.
                    </div>
                    {!! Form::open(['route' => 'teknisi.store','method' => 'POST','class' =>
                    'needs-validation','novalidate']) !!}
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('repair_item_uuid','Repair Item',['class' => 'required form-label'])}}
                        @foreach($repair_item as $item)
                        <input type="text" class="required form-label" value="{{$item->uuid}}">{{$item->ticket->ticket_number}}
                        @endforeach
                        @if ($errors->has('repair_item_uuid'))
                        <div class="invalid-feedback">{{ $errors->first('repair_item_uuid') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_status','Status Item',['class' => 'required form-label'])}}
                        {!! Form::select('item_status', array('0' => 'Butuh perbaikan dari vendor', '1' => 'Item telah diperbaiki oleh teknisi'), '', ['class' => 'garansi form-control'.($errors->has('item_status') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select Status item ...']) !!}
                        @if ($errors->has('item_status'))
                        <div class="invalid-feedback">{{ $errors->first('item_status') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('keterangan','Keterangan',['class' => 'required form-label'])}}
                        {{ Form::text('keterangan', '',['placeholder' => 'Keterangan','class' => 'form-control '.($errors->has('keterangan') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('keterangan'))
                        <div class="invalid-feedback">{{ $errors->first('keterangan') }}</div>
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
        $('.tiket').select2();
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
    });
</script>
@endsection
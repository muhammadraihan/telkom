@extends('layouts.page')

@section('title', 'Buffer Stock Create')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/select2/select2.bundle.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>Add New <span class="fw-300"><i>Buffer Stock</i></span></h2>
                <div class="panel-toolbar">
                    <a class="nav-link active" href="{{route('buffer-stock.index')}}"><i class="fal fa-arrow-alt-left">
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
                    {!! Form::open(['route' => 'buffer-stock.store','method' => 'POST','class' =>
                    'needs-validation','novalidate']) !!}
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('stock_item_uuid','Serial Number',['class' => 'required form-label'])}}
                        {!! Form::select('stock_item_uuid', $stock_item, '', ['class' => 'stock_item
                        form-control'.($errors->has('stock_item_uuid') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select serial number ...']) !!}
                        @if ($errors->has('stock_item_uuid'))
                        <div class="invalid-feedback">{{ $errors->first('stock_item_uuid') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('buffer_ammount','Buffer Ammount',['class' => 'required form-label'])}}
                        {{ Form::text('buffer_ammount',null,['placeholder' => 'Buffer Amount','class' => 'form-control '.($errors->has('buffer_ammount') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('buffer_ammount'))
                        <div class="invalid-feedback">{{ $errors->first('buffer_ammount') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('office_city','Office City',['class' => 'required form-label'])}}
                        {!! Form::select('office_city', $kota, '', ['class' => 'office_city
                        form-control'.($errors->has('office_city') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select office city ...']) !!}
                        @if ($errors->has('office_city'))
                        <div class="invalid-feedback">{{ $errors->first('office_city') }}</div>
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
        $('.stock_item').select2();
        $('.office_city').select2();
        
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
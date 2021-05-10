@extends('layouts.page')

@section('title', 'Stock Item Edit')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/select2/select2.bundle.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
            <h2>Edit <span class="fw-300"><i>{{$stock->serial_number}}</i></span></h2>
                <div class="panel-toolbar">
                    <a class="nav-link active" href="{{route('stock_item.index')}}"><i class="fal fa-arrow-alt-left">
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
                    {!! Form::open(['route' => ['stock_item.update',$stock->uuid],'method' => 'PUT','class' =>
                    'needs-validation','novalidate']) !!}
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_model','Model Item',['class' => 'required form-label'])}}
                        {{ Form::text('item_model',$stock->item_model,['placeholder' => 'Model Item','class' => 'form-control '.($errors->has('item_model') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('item_model'))
                        <div class="invalid-feedback">{{ $errors->first('item_model') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_merk','Merk Item',['class' => 'required form-label'])}}
                        {{ Form::text('item_merk',$stock->item_merk,['placeholder' => 'Merk Item','class' => 'form-control '.($errors->has('item_merk') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('item_merk'))
                        <div class="invalid-feedback">{{ $errors->first('item_merk') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_type','Tipe Item',['class' => 'required form-label'])}}
                        {{ Form::text('item_type',$stock->item_type,['placeholder' => 'Tipe Item','class' => 'form-control '.($errors->has('item_type') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('item_type'))
                        <div class="invalid-feedback">{{ $errors->first('item_type') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('part_number','Nomor Part',['class' => 'required form-label'])}}
                        {{ Form::text('part_number',$stock->part_number,['placeholder' => 'Nomor Part','class' => 'form-control '.($errors->has('part_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('part_number'))
                        <div class="invalid-feedback">{{ $errors->first('part_number') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('serial_number','Nomor Serial',['class' => 'required form-label'])}}
                        {{ Form::text('serial_number',$stock->serial_number,['placeholder' => 'Nomor Serial','class' => 'form-control '.($errors->has('serial_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('serial_number'))
                        <div class="invalid-feedback">{{ $errors->first('serial_number') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('barcode','Barcode',['class' => 'required form-label'])}}
                        {{ Form::text('barcode',$stock->barcode,['placeholder' => 'Barcode','class' => 'form-control '.($errors->has('barcode') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('barcode'))
                        <div class="invalid-feedback">{{ $errors->first('barcode') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label class="required form-label">Kelengkapan</label><br>
                            @foreach($kelengkapan as $item)
                                <label>
                                    <input type="checkbox" name="kelengkapan[]" value="{{($item->name)}}" {{in_array($item->name,$stock->kelengkapan) ? 'checked':''}}>{{$item->name}}
                                </label>
                            @endforeach
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('amount','Amount',['class' => 'required form-label'])}}
                        {{ Form::text('amount',$stock->amount,['placeholder' => 'Amount','class' => 'form-control '.($errors->has('amount') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('amount'))
                        <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
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
        $('.select2').select2();
        
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
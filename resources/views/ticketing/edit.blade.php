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
            <h2>Edit <span class="fw-300"><i>{{$ticketing->uuid_pelanggan}}</i></span></h2>
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
                    {!! Form::open(['route' => ['ticketing.update',$ticketing->uuid_pelanggan],'method' => 'PUT','class' =>
                    'needs-validation','novalidate']) !!}
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('uuid_pelanggan','Pelanggan',['class' => 'required form-label'])}}
                        {!! Form::select('uuid_pelanggan', $pelanggan, $ticketing->uuid_pelanggan, ['class' => 'pelanggan
                        form-control'.($errors->has('uuid_pelanggan') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select jenis pelanggan ...']) !!}
                        @if ($errors->has('uuid_pelanggan'))
                        <div class="invalid-feedback">{{ $errors->first('uuid_pelanggan') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('keterangan','Keterangan',['class' => 'required form-label'])}}
                        {{ Form::text('keterangan', $ticketing->keterangan,['placeholder' => 'Keterangan','class' => 'form-control '.($errors->has('keterangan') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('keterangan'))
                        <div class="invalid-feedback">{{ $errors->first('keterangan') }}</div>
                        @endif
                    </div>
            </div>
        </div>
    </div>
</div>

    <div class="col-xl-6">
        <div id="panel-2" class="panel">
            <div class="panel-hdr">
                <h2>Add New <span class="fw-300"><i>Repair Item</i></span></h2>
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
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_model','Item Model',['class' => 'required form-label'])}}
                        {{ Form::text('item_model', $repair_item->item_model,['placeholder' => 'Item Model','class' => 'form-control '.($errors->has('item_model') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('item_model'))
                        <div class="invalid-feedback">{{ $errors->first('item_model') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_merk','Item Merk',['class' => 'required form-label'])}}
                        {{ Form::text('item_merk', $repair_item->item_merk,['placeholder' => 'Item Merk','class' => 'form-control '.($errors->has('item_merk') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('item_merk'))
                        <div class="invalid-feedback">{{ $errors->first('item_merk') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_type','Item Type',['class' => 'required form-label'])}}
                        {{ Form::text('item_type', $repair_item->item_type,['placeholder' => 'Item Type','class' => 'form-control '.($errors->has('item_type') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('item_type'))
                        <div class="invalid-feedback">{{ $errors->first('item_type') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('part_number','Part Number',['class' => 'required form-label'])}}
                        {{ Form::text('part_number', $repair_item->part_number,['placeholder' => 'Part Number','class' => 'form-control '.($errors->has('part_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('part_number'))
                        <div class="invalid-feedback">{{ $errors->first('part_number') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('serial_number','Serial Number',['class' => 'required form-label'])}}
                        {{ Form::text('serial_number', $repair_item->serial_number,['placeholder' => 'Serial Number','class' => 'form-control '.($errors->has('serial_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('serial_number'))
                        <div class="invalid-feedback">{{ $errors->first('serial_number') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('barcode','Barcode',['class' => 'required form-label'])}}
                        {{ Form::text('barcode', $repair_item->barcode,['placeholder' => 'Barcode','class' => 'form-control '.($errors->has('barcode') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('barcode'))
                        <div class="invalid-feedback">{{ $errors->first('barcode') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label class="required form-label">Kelengkapan</label><br>
                        @foreach($kelengkapan as $item)
                                <label>
                                    <input type="checkbox" name="kelengkapan[]" value="{{($item->name)}}" {{in_array($item->name,$repair_item->kelengkapan) ? 'checked':''}}>{{$item->name}}
                                </label>
                            @endforeach
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('kerusakan','Kerusakan',['class' => 'required form-label'])}}
                        {{ Form::text('kerusakan', $repair_item->kerusakan,['placeholder' => 'Kerusakan','class' => 'form-control '.($errors->has('kerusakan') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('kerusakan'))
                        <div class="invalid-feedback">{{ $errors->first('kerusakan') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('status_garansi','Status Garansi',['class' => 'required form-label'])}}
                        {!! Form::select('status_garansi', array('0' => 'Non Warranty', '1' => 'Warranty'), $repair_item->status_garansi, ['class' => 'garansi form-control'.($errors->has('status_garansi') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select Status garansi ...']) !!}
                        @if ($errors->has('status_garansi'))
                        <div class="invalid-feedback">{{ $errors->first('status_garansi') }}</div>
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
@endsection

@section('js')
<script src="{{asset('js/formplugins/select2/select2.bundle.js')}}"></script>
<script>
    $(document).ready(function(){
        $('.pelanggan').select2();
        $('.tiket').select2();
        $('.job').select2();
        
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
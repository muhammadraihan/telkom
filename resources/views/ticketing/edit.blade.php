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
                <h2>Edit Tiket - <span class="fw-300"><i>{{$ticketing->ticket_number}}</i></span></h2>
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
                    {!! Form::open(['route' => ['ticketing.update',$ticketing->uuid],'method' => 'PUT','class'
                    =>
                    'needs-validation','novalidate']) !!}
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('uuid_pelanggan','Pelanggan',['class' => 'required form-label'])}}
                        {!! Form::select('uuid_pelanggan', $pelanggan, $ticketing->uuid_pelanggan, ['class' =>
                        'pelanggan
                        form-control'.($errors->has('uuid_pelanggan') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select nomor pelanggan']) !!}
                        @if ($errors->has('uuid_pelanggan'))
                        <div class="invalid-feedback">{{ $errors->first('uuid_pelanggan') }}</div>
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
            <h2>Add<span class="fw-300"><i>Item Data</i></span></h2>
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
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_merk','Item Merk',['class' => 'required form-label'])}}
                        {{ Form::text('item_merk', $repair_item->item_merk,['placeholder' => 'Item Merk','class' => 'form-control '.($errors->has('item_merk') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('item_merk'))
                        <div class="invalid-feedback">{{ $errors->first('item_merk') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_type','Item Type',['class' => 'required form-label'])}}
                        {{ Form::text('item_type',$repair_item->item_type,['placeholder' => 'Item Type','class' => 'form-control '.($errors->has('item_type') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('item_type'))
                        <div class="invalid-feedback">{{ $errors->first('item_type') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_model','Item Model',['class' => 'required form-label'])}}
                        {{ Form::text('item_model',$repair_item->item_merk,['placeholder' => 'Item Model','class' => 'form-control '.($errors->has('item_model') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('item_model'))
                        <div class="invalid-feedback">{{ $errors->first('item_model') }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('serial_number','Serial Number',['class' => 'required form-label'])}}
                        {{ Form::text('serial_number', $repair_item->serial_number,['placeholder' => 'Serial Number','class' => 'form-control '.($errors->has('serial_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('serial_number'))
                        <div class="invalid-feedback">{{ $errors->first('serial_number') }}</div>
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
                        {{ Form::label('barcode','Barcode',['class' => 'required form-label'])}}
                        {{ Form::text('barcode', $repair_item->barcode,['placeholder' => 'Barcode','class' => 'form-control '.($errors->has('barcode') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('barcode'))
                        <div class="invalid-feedback">{{ $errors->first('barcode') }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-group col-md-12 mb-3">
                    {{ Form::label('kelengkapan','Kelengkapan',['class' => 'form-label'])}}
                    <div class="frame-wrap">
                        @foreach($kelengkapan as $item)
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" class="custom-control-input" id="{{$item->name}}"
                                name="kelengkapan[]" value="{{$item->name}}" @if (is_array($repair_item->kelengkapan))
                            {{in_array($item->name,$repair_item->kelengkapan) ? 'checked':''}} @endif>
                            <label class="custom-control-label" for="{{$item->name}}">{{$item->name}}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group col-md-4 mb-3">
                    {{ Form::label('kerusakan','Kerusakan',['class' => 'required form-label'])}}
                    {{ Form::textarea('kerusakan', $repair_item->kerusakan,['placeholder' => 'Kerusakan','class' => 'form-control '.($errors->has('kerusakan') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                    @if ($errors->has('kerusakan'))
                    <div class="invalid-feedback">{{ $errors->first('kerusakan') }}</div>
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


@endsection

@section('js')
<script src="{{asset('js/formplugins/select2/select2.bundle.js')}}"></script>
<script>
    $(document).ready(function(){
        $('.pelanggan').select2();
        $('.tiket').select2();
        $('.job').select2();
    });
</script>
@endsection
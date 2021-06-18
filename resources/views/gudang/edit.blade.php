@extends('layouts.page')

@section('title', 'Gudang Job Order List')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/select2/select2.bundle.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>Progress Tiket <span class="fw-300"><i>{{$tech_repair->repair->ticket->ticket_number}}</i></span>
                </h2>
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
                    {!! Form::open(['route' => ['gudang.update',$tech_repair->repair_item_uuid],'method' =>
                    'PUT','class' =>
                    'needs-validation','novalidate']) !!}
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('repair_item_uuid','Ticket Number',['class' => 'required form-label'])}}
                            {{ Form::text('repair_item_uuid', $tech_repair->repair->ticket->ticket_number,['placeholder' => 'Tiket Number','class' => 'form-control '.($errors->has('repair_item_uuid') ? 'is-invalid':''),'required', 'autocomplete' => 'off', 'disabled'])}}
                            @if ($errors->has('repair_item_uuid'))
                            <div class="invalid-feedback">{{ $errors->first('repair_item_uuid') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2 mb-2">
                            {{ Form::label('item_merk','Item Merk',['class' => 'required form-label'])}}
                            {{ Form::text('item_merk', $tech_repair->repair->item_merk,['class' => 'form-control','required', 'autocomplete' => 'off', 'disabled'])}}
                        </div>
                        <div class="form-group col-md-2 mb-2">
                            {{ Form::label('item_type','Item Type',['class' => 'required form-label'])}}
                            {{ Form::text('utem_type', $tech_repair->repair->item_type,['placeholder' => 'Tiket Number','class' => 'form-control ','required', 'autocomplete' => 'off', 'disabled'])}}
                        </div>
                        <div class="form-group col-md-2 mb-2">
                            {{ Form::label('item_model','Item Model',['class' => 'required form-label'])}}
                            {{ Form::text('item_model', $tech_repair->repair->item_model,['class' => 'form-control','required', 'autocomplete' => 'off', 'disabled'])}}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 mb-2">
                            {{ Form::label('item_model','Status Item',['class' => 'required form-label'])}}
                            @switch($tech_repair->item_status)
                            @case(0)
                            {{ Form::text('item_model', 'Butuh Penggantian',['class' => 'form-control','required', 'autocomplete' => 'off', 'disabled'])}}
                            @break
                            @case(1)
                            {{ Form::text('item_model', 'Telah di perbaiki oleh teknisi',['class' => 'form-control','required', 'autocomplete' => 'off', 'disabled'])}}
                            @break
                            @case(2)
                            {{ Form::text('item_model', 'Ticket Cancel',['class' => 'form-control','required', 'autocomplete' => 'off', 'disabled'])}}
                            @break
                            @default
                            {{ Form::text('item_model', 'Unknown Status',['class' => 'form-control','required', 'autocomplete' => 'off', 'disabled'])}}
                            @endswitch
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 mb-2">
                            {{ Form::label('item_model','Status Item',['class' => 'required form-label'])}}
                            {{ Form::textarea('item_model', $tech_repair->keterangan,['class' => 'form-control','required', 'autocomplete' => 'off', 'disabled'])}}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 mb-2">
                            {{ Form::label('tindakan','Tindakan',['class' => 'required form-label'])}}
                            {!! Form::select('tindakan', array('3' => 'Klaim garansi dari vendor', '4' => 'Penggantian
                            barang dari stok','5'=>'Kirim perbaikan ke vendor','7'=>'Barang dikirim ke customer'),
                            '', ['class' => 'tindakan
                            form-control'.($errors->has('item_status') ?
                            'is-invalid':''), 'required'
                            => '', 'placeholder' => 'Select tindakan ...']) !!}
                            @if ($errors->has('item_status'))
                            <div class="help-block text-danger">{{ $errors->first('item_status') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 mb-2">
                            {{ Form::label('item_model','Keterangan',['class' => 'required form-label'])}}
                            {{ Form::textarea('keterangan','',['class' => 'form-control','required', 'autocomplete' => 'off'])}}
                        </div>
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
        $(".tindakan").select2();
    });
</script>
@endsection
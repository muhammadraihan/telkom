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
                <h2>Progress Ticket <span class="fw-300"><i>{{$job_order->repair->ticket->ticket_number}}</i></span>
                </h2>
                <div class="panel-toolbar">
                    <a class="nav-link active" href="{{route('warehouse.index')}}"><i class="fal fa-arrow-alt-left">
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
                    {!! Form::open(['route' => ['warehouse.update',$job_order->uuid],'method' =>
                    'PUT','class' =>
                    'needs-validation','enctype' => 'multipart/form-data','novalidate']) !!}
                    {{-- Module repaired by tech, module done replace, module done by vendor --}}
                    @if ($job_order->item_status == 2 && $job_order->stock_input == 0|| $job_order->item_status == 6 &&
                    $job_order->stock_input == 0 || $job_order->item_status == 8 && $job_order->stock_input == 0 )
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_status','Progress Action',['class' => 'required form-label'])}}
                        <select name="item_status" class="custom-select select2">
                            <option value=9>Dikirim ke customer</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('resi_image','Resi Image',['class' => 'required form-label'])}}
                        <div class="form-group">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input accept="image/*" name="resi_image" type="file" class="custom-file-input @if ($errors->has('resi_image'))
                                    is-invalid
                                @endif" id="resi-image" aria-describedby="image" required>
                                    <label class="custom-file-label" for="resi_image">Choose file</label>
                                </div>
                            </div>
                            @if ($errors->has('resi_image'))
                            <div class="text-danger">{{ $errors->first('resi_image') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <img id="image-preview" src="{{asset('img/placeholder.png')}}" class="shadow-2 img-thumbnail"
                            alt="">
                    </div>
                    @endif
                    {{-- Module need warranty claim --}}
                    @if ($job_order->item_status == 4)
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('warranty_status','Progress Action',['class' => 'required form-label'])}}
                            <select name="warranty_status" class="custom-select select2">
                                <option value=4>Klaim Garansi</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {!! Form::label('replace_status', 'Replace From', ['class' => 'required form-label']) !!}
                            {!! Form::select('replace_status', [3 => 'From Stock',4 =>'From Vendor'], '', ['id' =>
                            'replace_status','class' => 'custom-select select2 '.($errors->has('replace_status')
                            ?
                            'is-invalid':''), 'required'
                            => '', 'placeholder' => 'Select replace status ...']) !!}
                            @if ($errors->has('replace_status'))
                            <div class="invalid-feedback">{{ $errors->first('replace_status') }}</div>
                            @endif
                        </div>
                    </div>
                    {{-- Module need warranty claim from stock --}}
                    <div id="stock-form" style="display:none">
                        <div class="form-row">
                            <div class="form-group col-md-4 mb-3">
                                {{ Form::label('module_type','Module Type',['class' => 'required form-label'])}}
                                <select id="module_type" class="custom-select select2 module_type @if ($errors->has('module_type'))
                                    is-invalid
                                @endif" name="module_type">
                                    <option value="" disabled selected>Select module type ...</option>
                                    @foreach ($module_stock as $item)
                                    <option value="{{$item->module_type_uuid}}">Module Type: {{$item->type->name}} ,
                                        Stock :
                                        {{$item->available}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('module_type'))
                                <div class="invalid-feedback">{{ $errors->first('module_type') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-2 mb-3">
                                {{ Form::label('part_number','Part Number',['class' => 'required form-label'])}}
                                {{ Form::text('part_number', '',['placeholder' => 'Part Number','class' => 'form-control '.($errors->has('part_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                                @if ($errors->has('part_number'))
                                <div class="invalid-feedback">{{ $errors->first('part_number') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-2 mb-3">
                                {{ Form::label('serial_number','Serial Number',['class' => 'required form-label'])}}
                                {{ Form::text('serial_number', '',['placeholder' => 'Serial Number','class' => 'form-control '.($errors->has('serial_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                                @if ($errors->has('serial_number'))
                                <div class="invalid-feedback">{{ $errors->first('serial_number') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3 mb-3">
                                {{ Form::label('serial_number_msc','Serial Number MSC',['class' => 'required form-label'])}}
                                {{ Form::text('serial_number_msc', '',['placeholder' => 'Serial Number MSC','class' => 'form-control '.($errors->has('serial_number_msc') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                                @if ($errors->has('serial_number_msc'))
                                <div class="invalid-feedback">{{ $errors->first('serial_number_msc') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-12 mb-3">
                                {{ Form::label('accessories','Accessories',['class' => 'form-label required'])}}
                                <div class="frame-wrap">
                                    @foreach($accessories as $item)
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input @if ($errors->has('accessories'))
                                        is-invalid
                                    @endif" id="{{$item->name}}" name="accessories[]" value="{{$item->name}}">
                                        <label class="custom-control-label"
                                            for="{{$item->name}}">{{$item->name}}</label>
                                    </div>
                                    @endforeach
                                    @if ($errors->has('accessories'))
                                    <div class="text-danger">{{ $errors->first('accessories') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    {{-- Warranty claim from vendor --}}
                    @if ($job_order->item_status == 5)
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('item_status','Progress Action',['class' => 'required form-label'])}}
                            <select name="item_status" class="custom-select select2">
                                <option value=5>Warranty replace</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('replace_status','Progress Action',['class' => 'required form-label'])}}
                            <select name="replace_status" class="custom-select select2">
                                <option value=4>From vendor</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('vendor_name','Vendor Name',['class' => 'required form-label'])}}
                            {{ Form::text('vendor_name', '',['placeholder' => 'Vendor Name','class' => 'form-control '.($errors->has('vendor_name') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                            @if ($errors->has('vendor_name'))
                            <div class="invalid-feedback">{{ $errors->first('vendor_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('module_type','Module Type',['class' => 'required form-label'])}}
                            <select id="module_type" class="custom-select select2 module_type @if ($errors->has('module_type'))
                                is-invalid
                            @endif" name="module_type">
                                <option value="" disabled selected>Select module type ...</option>
                                @foreach ($module_stock as $item)
                                <option value="{{$item->module_type_uuid}}">Module Type: {{$item->type->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('module_type'))
                            <div class="invalid-feedback">{{ $errors->first('module_type') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-2 mb-3">
                            {{ Form::label('part_number','Part Number',['class' => 'required form-label'])}}
                            {{ Form::text('part_number', '',['placeholder' => 'Part Number','class' => 'form-control '.($errors->has('part_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                            @if ($errors->has('part_number'))
                            <div class="invalid-feedback">{{ $errors->first('part_number') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-2 mb-3">
                            {{ Form::label('serial_number','Serial Number',['class' => 'required form-label'])}}
                            {{ Form::text('serial_number', '',['placeholder' => 'Serial Number','class' => 'form-control '.($errors->has('serial_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                            @if ($errors->has('serial_number'))
                            <div class="invalid-feedback">{{ $errors->first('serial_number') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-3 mb-3">
                            {{ Form::label('serial_number_msc','Serial Number MSC',['class' => 'required form-label'])}}
                            {{ Form::text('serial_number_msc', '',['placeholder' => 'Serial Number MSC','class' => 'form-control '.($errors->has('serial_number_msc') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                            @if ($errors->has('serial_number_msc'))
                            <div class="invalid-feedback">{{ $errors->first('serial_number_msc') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-12 mb-3">
                            {{ Form::label('accessories','Accessories',['class' => 'form-label required'])}}
                            <div class="frame-wrap">
                                @foreach($accessories as $item)
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input @if ($errors->has('accessories'))
                                    is-invalid
                                @endif" id="{{$item->name}}" name="accessories[]" value="{{$item->name}}">
                                    <label class="custom-control-label" for="{{$item->name}}">{{$item->name}}</label>
                                </div>
                                @endforeach
                                @if ($errors->has('accessories'))
                                <div class="text-danger">{{ $errors->first('accessories') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    {{-- Can't repair by tech --}}
                    @if ($job_order->item_status == 3)
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_status','Progress Action',['class' => 'required form-label'])}}
                        <select name="item_status" class="custom-select select2">
                            <option value=7>Progress ke vendor</option>
                        </select>
                    </div>
                    @endif
                    {{-- In vendor handle --}}
                    @if ($job_order->item_status == 7)
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('item_status','Progress Action',['class' => 'required form-label'])}}
                            <select name="item_status" class="custom-select select2">
                                <option value=8>Selesai progress dari vendor</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {!! Form::label('vendor_status', 'Progress Status', ['class' => 'required form-label']) !!}
                            {!! Form::select('vendor_status', [1 => 'Repair',2 =>'Replace'], '', ['id' =>
                            'vendor_status','class' => 'custom-select select2 '.($errors->has('vendor_status')
                            ?
                            'is-invalid':''), 'required'
                            => '', 'placeholder' => 'Select progress status ...']) !!}
                            @if ($errors->has('vendor_status'))
                            <div class="invalid-feedback">{{ $errors->first('vendor_status') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('vendor_name','Vendor Name',['class' => 'required form-label'])}}
                            {{ Form::text('vendor_name', '',['placeholder' => 'Vendor Name','class' => 'form-control '.($errors->has('vendor_name') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                            @if ($errors->has('vendor_name'))
                            <div class="invalid-feedback">{{ $errors->first('vendor_name') }}</div>
                            @endif
                        </div>
                        <div id="repair-notes" class="form-group col-md-4 mb-3" style="display:none">
                            {{ Form::label('repair_notes','Repair Notes',['class' => 'required form-label'])}}
                            {{ Form::textarea('repair_notes', '',['placeholder' => 'Repair notes','class' => 'form-control '.($errors->has('repair_notes') ? 'is-invalid':''),'required'])}}
                            @if ($errors->has('repair_notes'))
                            <div class=" invalid-feedback">{{ $errors->first('repair_notes') }}</div>
                            @endif
                        </div>
                    </div>
                    <div id="replace-form" style="display:none">
                        <div class="form-row">
                            <div class="form-group col-md-4 mb-3">
                                {{ Form::label('module_type','Module Type',['class' => 'required form-label'])}}
                                <select id="module_type" class="custom-select select2 module_type @if ($errors->has('module_type'))
                                    is-invalid
                                @endif" name="module_type">
                                    <option value="" disabled selected>Select module type ...</option>
                                    @foreach ($module_stock_input as $item)
                                    <option value="{{$item->module_type_uuid}}">Module Type: {{$item->type->name}}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('module_type'))
                                <div class="invalid-feedback">{{ $errors->first('module_type') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-2 mb-3">
                                {{ Form::label('part_number','Part Number',['class' => 'required form-label'])}}
                                {{ Form::text('part_number', '',['placeholder' => 'Part Number','class' => 'form-control '.($errors->has('part_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                                @if ($errors->has('part_number'))
                                <div class="invalid-feedback">{{ $errors->first('part_number') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-2 mb-3">
                                {{ Form::label('serial_number','Serial Number',['class' => 'required form-label'])}}
                                {{ Form::text('serial_number', '',['placeholder' => 'Serial Number','class' => 'form-control '.($errors->has('serial_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                                @if ($errors->has('serial_number'))
                                <div class="invalid-feedback">{{ $errors->first('serial_number') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3 mb-3">
                                {{ Form::label('serial_number_msc','Serial Number MSC',['class' => 'required form-label'])}}
                                {{ Form::text('serial_number_msc', '',['placeholder' => 'Serial Number MSC','class' => 'form-control '.($errors->has('serial_number_msc') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                                @if ($errors->has('serial_number_msc'))
                                <div class="invalid-feedback">{{ $errors->first('serial_number_msc') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-12 mb-3">
                                {{ Form::label('accessories','Accessories',['class' => 'form-label required'])}}
                                <div class="frame-wrap">
                                    @foreach($accessories as $item)
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input @if ($errors->has('accessories'))
                                        is-invalid
                                    @endif" id="{{$item->name}}" name="accessories[]" value="{{$item->name}}">
                                        <label class="custom-control-label"
                                            for="{{$item->name}}">{{$item->name}}</label>
                                    </div>
                                    @endforeach
                                    @if ($errors->has('accessories'))
                                    <div class="text-danger">{{ $errors->first('accessories') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    {{-- Module need replace immediately --}}
                    @if ($job_order->item_status == 10)
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('item_status','Progress Action',['class' => 'required form-label'])}}
                            <select name="item_status" class="custom-select select2">
                                <option value=10>Penggantian Urgent</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {!! Form::label('replace_status', 'Replace From', ['class' => 'required form-label']) !!}
                            <select name="replace_status" class="custom-select select2">
                                <option value=3>From Stock</option>
                            </select>
                        </div>
                    </div>
                    <div id="">
                        <div class=" form-row">
                            <div class="form-group col-md-4 mb-3">
                                {{ Form::label('module_type','Module Type',['class' => 'required form-label'])}}
                                <select id="module_type" class="custom-select select2 module_type @if ($errors->has('module_type'))
                                    is-invalid
                                @endif" name="module_type">
                                    <option value="" disabled selected>Select module type ...</option>
                                    @foreach ($module_stock as $item)
                                    <option value="{{$item->module_type_uuid}}">Module Type: {{$item->type->name}} ,
                                        Stock :
                                        {{$item->available}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('module_type'))
                                <div class="invalid-feedback">{{ $errors->first('module_type') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-2 mb-3">
                                {{ Form::label('part_number','Part Number',['class' => 'required form-label'])}}
                                {{ Form::text('part_number', '',['placeholder' => 'Part Number','class' => 'form-control '.($errors->has('part_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                                @if ($errors->has('part_number'))
                                <div class="invalid-feedback">{{ $errors->first('part_number') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-2 mb-3">
                                {{ Form::label('serial_number','Serial Number',['class' => 'required form-label'])}}
                                {{ Form::text('serial_number', '',['placeholder' => 'Serial Number','class' => 'form-control '.($errors->has('serial_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                                @if ($errors->has('serial_number'))
                                <div class="invalid-feedback">{{ $errors->first('serial_number') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3 mb-3">
                                {{ Form::label('serial_number_msc','Serial Number MSC',['class' => 'required form-label'])}}
                                {{ Form::text('serial_number_msc', '',['placeholder' => 'Serial Number MSC','class' => 'form-control '.($errors->has('serial_number_msc') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                                @if ($errors->has('serial_number_msc'))
                                <div class="invalid-feedback">{{ $errors->first('serial_number_msc') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-12 mb-3">
                                {{ Form::label('accessories','Accessories',['class' => 'form-label required'])}}
                                <div class="frame-wrap">
                                    @foreach($accessories as $item)
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input @if ($errors->has('accessories'))
                                        is-invalid
                                    @endif" id="{{$item->name}}" name="accessories[]" value="{{$item->name}}">
                                        <label class="custom-control-label"
                                            for="{{$item->name}}">{{$item->name}}</label>
                                    </div>
                                    @endforeach
                                    @if ($errors->has('accessories'))
                                    <div class="text-danger">{{ $errors->first('accessories') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    {{-- need to input to stock --}}
                    @if ($job_order->stock_input == 1)
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_status','Progress Action',['class' => 'required form-label'])}}
                        <select name="item_status" class="custom-select select2">
                            <option value=11>Input ke dalam Stock</option>
                        </select>
                    </div>
                    @endif
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('warehouse_notes','Warehouse Notes',['class' => 'required form-label'])}}
                            {{ Form::textarea('warehouse_notes', '',['placeholder' => 'Warehouse notes','class' => 'form-control '.($errors->has('warehouse_notes') ? 'is-invalid':''),'required'])}}
                            @if ($errors->has('warehouse_notes'))
                            <div class="invalid-feedback">{{ $errors->first('warehouse_notes') }}</div>
                            @endif
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
        $('.select2').select2();
        $('#resi-image').change(function(){
            let reader = new FileReader();reader.onload = (e) => { 
                $('#image-preview').attr('src', e.target.result); 
            }
            reader.readAsDataURL(this.files[0]); 
        });
        $('#replace_status').change(function() {
            var status = $(this).val();
            if(status == 3){
                $('#stock-form').show();
            } 
            if(status == 4) {
                $('#stock-form').hide();
            }
        });
        $('#vendor_status').change(function() {
            var status = $(this).val();
            if(status == 1){
                $('#repair-notes').show();
                $('#replace-form').hide();
            }
            if(status == 2){
                $('#replace-form').show();
                $('#repair-notes').hide();
            }
        });
    });
    @if (count($errors) > 0)
    $('#replace_status').val(null);
    $('#vendor_status').val(null);
    @endif
</script>
@endsection
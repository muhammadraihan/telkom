@extends('layouts.page')

@section('title', 'Item Replace Edit')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/select2/select2.bundle.css')}}">
<link rel="stylesheet" media="screen, print" href="{{asset('css/datagrid/datatables/datatables.bundle.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
            <h2>Edit <span class="fw-300"><i>{{$gudang->repairItem->ticket->ticket_number}}</i></span></h2>
                <div class="panel-toolbar">
                    <a class="nav-link active" href="{{route('itemreplace.index')}}"><i class="fal fa-arrow-alt-left">
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
                    {!! Form::open(['route' => ['itemreplace.update',$gudang->uuid],'method' => 'PUT','class' =>
                    'needs-validation','novalidate']) !!}
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_repair_uuid','Ticket Number',['class' => 'required form-label'])}}
                        {{ Form::text('item_repair_uuid', $gudang->repairItem->ticket->ticket_number,['placeholder' => 'Ticket Number','class' => 'form-control '.($errors->has('item_repair_uuid') ? 'is-invalid':''),'required', 'autocomplete' => 'off', 'disabled'])}}
                        @if ($errors->has('repair_item_uuid'))
                        <div class="invalid-feedback">{{ $errors->first('repair_item_uuid') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('replace_from','Replace From',['class' => 'required form-label'])}}
                        {!! Form::select('replace_from', array('1' => 'Vendor', '2' => 'Main Stock', '3' => 'Buffer Stock'), '', ['class' => 'replaceFrom form-control'.($errors->has('replace_from') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select Replace From ...']) !!}
                        @if ($errors->has('replace_from'))
                        <div class="invalid-feedback">{{ $errors->first('replace_from') }}</div>
                        @endif
                    </div>
                    <div id="detailBufferStock" class="form-group col-md-20 mb-3" hidden>
                        <table id="datatable" class="table table-bordered table-hover table-striped w-100">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>No</th>
                                    <th>Item Type</th>
                                    <th>Item Merk</th>
                                    <th>Item Model</th>
                                    <th>Part Number</th>
                                    <th>Serial Number</th>
                                    <th>Barcode</th>
                                    <th>Kelengkapan</th>
                                    <th>Buffer Ammount</th>
                                    <th>Office City</th>
                                </tr>
                            </thead>
                        </table>
                        @if ($errors->has('item_replace_detail_from_stock'))
                        <div class="invalid-feedback">{{ $errors->first('item_detail_from_stock') }}</div>
                        @endif
                    </div>
                    <div id="detailStock" class="form-group col-md-20 mb-3" hidden>
                        <table id="datatable2" class="table table-bordered table-hover table-striped w-100">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>No</th>
                                    <th>Item Type</th>
                                    <th>Item Merk</th>
                                    <th>Item Model</th>
                                    <th>Part Number</th>
                                    <th>Serial Number</th>
                                    <th>Barcode</th>
                                    <th>Kelengkapan</th>
                                    <th>Ammount</th>
                                </tr>
                            </thead>
                        </table>
                        @if ($errors->has('item_replace_detail_from_stock'))
                        <div class="invalid-feedback">{{ $errors->first('item_detail_from_stock') }}</div>
                        @endif
                    </div>
                    <div
                        class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                    <button id="button" class="btn btn-primary ml-auto" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

    <div id="detailVendor" class="col-xl-6" hidden>
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
                        {{ Form::label('vendor_name','Nama Vendor',['class' => 'required form-label'])}}
                        {{ Form::text('vendor_name',null,['placeholder' => 'Nama Vendor','class' => 'form-control '.($errors->has('vendor_name') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('vendor_name'))
                        <div class="invalid-feedback">{{ $errors->first('vendor_name') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_model','Model Item',['class' => 'required form-label'])}}
                        {{ Form::text('item_model',null,['placeholder' => 'Model Item','class' => 'form-control '.($errors->has('item_model') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('item_model'))
                        <div class="invalid-feedback">{{ $errors->first('item_model') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_merk','Merk Item',['class' => 'required form-label'])}}
                        {{ Form::text('item_merk',null,['placeholder' => 'Merk Item','class' => 'form-control '.($errors->has('item_merk') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('item_merk'))
                        <div class="invalid-feedback">{{ $errors->first('item_merk') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_type','Tipe Item',['class' => 'required form-label'])}}
                        {{ Form::text('item_type',null,['placeholder' => 'Tipe Item','class' => 'form-control '.($errors->has('item_type') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('item_type'))
                        <div class="invalid-feedback">{{ $errors->first('item_type') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('part_number','Nomor Part',['class' => 'required form-label'])}}
                        {{ Form::text('part_number',null,['placeholder' => 'Nomor Part','class' => 'form-control '.($errors->has('part_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('part_number'))
                        <div class="invalid-feedback">{{ $errors->first('part_number') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('serial_number','Nomor Serial',['class' => 'required form-label'])}}
                        {{ Form::text('serial_number',null,['placeholder' => 'Nomor Serial','class' => 'form-control '.($errors->has('serial_number') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('serial_number'))
                        <div class="invalid-feedback">{{ $errors->first('serial_number') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('barcode','Barcode',['class' => 'required form-label'])}}
                        {{ Form::text('barcode',null,['placeholder' => 'Barcode','class' => 'form-control '.($errors->has('barcode') ? 'is-invalid':''),'required', 'autocomplete' => 'off'])}}
                        @if ($errors->has('barcode'))
                        <div class="invalid-feedback">{{ $errors->first('barcode') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label class="required form-label">Kelengkapan</label><br>
                        @foreach($kelengkapan as $item)
                            <label>
                                <input type="checkbox" name="kelengkapan[]" value="{{$item->name}}">{{$item->name}}
                            </label>
                        @endforeach
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
<script src="{{asset('js/datagrid/datatables/datatables.bundle.js')}}"></script>
<script>
    $(document).ready(function(){
        $('.replaceFrom').select2();
        $('.detailStock').select2();

        $('.replaceFrom').on('change', function(e){
            var detail = $(this).val();
            $('#detailBufferStock').attr('hidden',true);
            $('#detailVendor').attr('hidden',true);
            if(detail == '3'){
                $('#button').attr('hidden',false);
                $('#detailBufferStock').attr('hidden',false);
                $('#datatable').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "responsive": true,
                        "order": [[ 0, "asc" ]],
                        "columnDefs": [{
                            // orderable : false,
                            // className : 'select-checkbox',
                            // target : 0
                            "targets":0 ,
                            "data":"text",
                            "render":function(data,type,full,meta){
                                
                                // console.log(meta);
                                return `<input type="checkbox" name="bufferstock" value="`+data+`">`;
                            }
                        }],
                        "select" : {
                            style : 'os',
                            selector : 'td:first-child'
                        },
                        "ajax":{
                            url:'{{route('get.detailBufferStock')}}',
                            type : "GET",
                            data : {detail: detail},
                            dataType: 'json',
                            error: function(data){
                                console.log(data);
                                }
                        },
                        "columns": [
                            {data: 'uuid', name: 'uuid'},
                            {data: 'rownum', name: 'rownum'},
                            {data: 'item_type', name: 'item_type'},
                            {data: 'item_merk', name: 'item_merk'},
                            {data: 'item_model', name: 'item_model'},
                            {data: 'part_number', name: 'part_number'},
                            {data: 'serial_number', name: 'serial_number'},
                            {data: 'barcode', name: 'barcode'},
                            {data: 'kelengkapan', name: 'kelengkapan'},
                            {data: 'buffer_ammount', name: 'buffer_ammount'},
                            {data: 'office_city', name: 'office_city'},
                        ]
                    });
            }if(detail == '1'){
                $('#button').attr('hidden',true);
                $('#detailVendor').attr('hidden',false);
            }if(detail == '2'){
                $('#button').attr('hidden',false);
                $('#detailStock').attr('hidden',false);
                $('#datatable2').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "responsive": true,
                        "order": [[ 0, "asc" ]],
                        "columnDefs": [{
                            // orderable : false,
                            // className : 'select-checkbox',
                            // target : 0
                            "targets":0 ,
                            "data":"text",
                            "render":function(data,type,full,meta){
                                
                                // console.log(meta);
                                return `<input type="checkbox" name="mainstock" value="`+data+`">`;
                            }
                        }],
                        "select" : {
                            style : 'os',
                            selector : 'td:first-child'
                        },
                        "ajax":{
                            url:'{{route('get.detailStock')}}',
                            type : "GET",
                            data : {detail: detail},
                            dataType: 'json',
                            error: function(data){
                                console.log(data);
                                }
                        },
                        "columns": [
                            {data: 'uuid', name: 'uuid'},
                            {data: 'rownum', name: 'rownum'},
                            {data: 'item_type', name: 'item_type'},
                            {data: 'item_merk', name: 'item_merk'},
                            {data: 'item_model', name: 'item_model'},
                            {data: 'part_number', name: 'part_number'},
                            {data: 'serial_number', name: 'serial_number'},
                            {data: 'barcode', name: 'barcode'},
                            {data: 'kelengkapan', name: 'kelengkapan'},
                            {data: 'amount', name: 'amount'},
                        ]
                    });
            }
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
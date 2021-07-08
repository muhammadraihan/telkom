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
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    {!! Form::open(['route' => ['warehouse.update',$job_order->uuid],'method' =>
                    'PUT','class' =>
                    'needs-validation','enctype' => 'multipart/form-data','novalidate']) !!}
                    @if ($job_order->item_status == 2)
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('item_status','Progress Action',['class' => 'required form-label'])}}
                        <select name="item_status" class="custom-select select2">
                            <option value="8">Dikirim ke customer</option>
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
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('warehouse_notes','Warehouse Notes',['class' => 'required form-label'])}}
                        {{ Form::textarea('warehouse_notes', '',['placeholder' => 'Warehouse notes','class' => 'form-control '.($errors->has('warehouse_notes') ? 'is-invalid':''),'required'])}}
                        @if ($errors->has('warehouse_notes'))
                        <div class="invalid-feedback">{{ $errors->first('warehouse_notes') }}</div>
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
    });
</script>
@endsection
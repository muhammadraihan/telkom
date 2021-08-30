@extends('layouts.page')

@section('title', 'Repair Assign')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/select2/select2.bundle.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div class="panel">
            <div class="panel-hdr">
                <h2></h2>
                <div class="panel-toolbar">
                    <a class="nav-link active" href="{{route('repair.index')}}"><i class="fal fa-arrow-alt-left">
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
                    {!! Form::open(['route' => ['repair.update',$repair_job->uuid],'method' => 'PUT','class'
                    =>
                    'needs-validation','novalidate']) !!}
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('tech','To Repair Technician',['class' => 'required form-label'])}}
                            {!! Form::select('tech', $techs, '', ['id' => 'tech','class' =>
                            'form-control'.($errors->has('tech') ? 'is-invalid':''), 'required'
                            => '', 'placeholder' => 'Pilih Technician']) !!}
                            @if ($errors->has('tech'))
                            <div class="help-block text-danger">{{ $errors->first('tech') }}</div>
                            @endif
                        </div>
                    </div>
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
        $('#tech').select2();
    });
</script>
@endsection
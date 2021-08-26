@extends('layouts.page')

@section('title', 'Technician Job Order Progress')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/select2/select2.bundle.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>Progress Ticket <span class="fw-300"><i>{{$repair_job->repair->ticket->ticket_number}}</i></span>
                </h2>
                <div class="panel-toolbar">
                    <a class="nav-link active" href="{{route('repair-job.index')}}"><i class="fal fa-arrow-alt-left">
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

                    {!! Form::open(['route' => ['repair-job.update',$repair_job->uuid],'method' => 'PUT','class' =>
                    'needs-validation','novalidate']) !!}
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('repair_status','Repair Status',['class' => 'required form-label'])}}
                            {!! Form::select('repair_status', array('0' => 'Non Repaired', '1' => 'Repaired'), '',
                            ['id' => 'repair_status','class' => 'custom-select select2 '.($errors->has('repair_status')
                            ?
                            'is-invalid':''), 'required'
                            => '', 'placeholder' => 'Select repair status ...']) !!}
                            @if ($errors->has('repair_status'))
                            <div class="invalid-feedback">{{ $errors->first('repair_status') }}</div>
                            @endif
                        </div>
                        <div id="using-material" class="form-group col-md-4 mb-3" style="display: none">
                            {{ Form::label('using_material','Using Material',['class' => 'required form-label'])}}
                            {!! Form::select('using_material', array('0' => 'No', '1' => 'Yes'), '',
                            ['id' => 'using_material','class' => 'custom-select select2
                            '.($errors->has('using_material')
                            ?
                            'is-invalid':''), 'required'
                            => '', 'placeholder' => 'Using Material ...']) !!}
                            @if ($errors->has('using_material'))
                            <div class="invalid-feedback">{{ $errors->first('using_material') }}</div>
                            @endif
                        </div>
                    </div>
                    <div id="material-form" class="form-row" style="display:none">
                        <div id="material-input" class="form-group col-md-4 mb-3 material-input">
                            {{ Form::label('material','Material',['class' => 'required form-label'])}}
                            <div id="material-select" class="material-select mb-2">
                                <select id="material" class="custom-select select2 material @if ($errors->has('material'))
                                    is-invalid
                                @endif" name="material[]">
                                    <option value="" disabled selected>Select material ...</option>
                                    @foreach ($materials as $item)
                                    <option value="{{$item->uuid}}">Material : {{$item->material_type}} , Stock :
                                        {{$item->available}} {{$item->volume}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('material'))
                                <div class="invalid-tooltip">{{ $errors->first('material') }}</div>
                                @endif
                            </div>
                        </div>
                        <div id="amount-input" class="form-group col-md-4 mb-3 amount-input">
                            {{ Form::label('material_amount','Material Amount',['class' => 'required form-label'])}}
                            <div id="material-amount" class="input-group mb-2 material-amount">
                                {{ Form::text('material_amount[]', '',['class' => 'form-control '.($errors->has('material_amount.*') ? 'is-invalid':''),'placeholder' => 'Amount','required'])}}
                                <div class="input-group-append">
                                    <button id="add-material" type="button"
                                        class="btn btn-outline-info waves-effect waves-themed" data-toggle="tooltip"
                                        data-placement="top" title=""
                                        data-original-title="Click for add more material"><i
                                            class="fal fa-plus"></i></button>
                                </div>
                                @if ($errors->has('material_amount.*'))
                                <div class="invalid-tooltip">{{ $errors->first('material_amount.*') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('complain','Complain',['class' => 'required form-label'])}}
                            {{ Form::textarea('complain', '',['placeholder' => 'Complain Notes','class' => 'form-control '.($errors->has('complain') ? 'is-invalid':''),'required'])}}
                            @if ($errors->has('complain'))
                            <div class="invalid-feedback">{{ $errors->first('complain') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('repair_notes','Repair Notes',['class' => 'required form-label'])}}
                            {{ Form::textarea('repair_notes', '',['placeholder' => 'Repair Notes','class' => 'form-control '.($errors->has('repair_notes') ? 'is-invalid':''),'required'])}}
                            @if ($errors->has('repair_notes'))
                            <div class="invalid-feedback">{{ $errors->first('repair_notes') }}</div>
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
        var uuid = {!!json_encode($repair_job->uuid)!!};
        var select = $('.select2').select2();

        $('#repair_status').change(function() {
            var status = $(this).val();
            if(status == 1){
                $('#using-material').show();
            } else {
                $('#using-material').hide();
            }
        });

        $('#using_material').change(function(){
            var status = $(this).val();
            if (status == 1) {
                $('#material-form').show();
            } else {
                $('#material-form').hide();
            }
        });

        $('#add-material').click(function(e){
            e.preventDefault();
            var selectId = $('.material-select').length;
            var amountId = $('.material-amount').length;
            
            select.select2("destroy");
        
            var cloneDiv = $('#material-select').clone(true);
            cloneDiv.appendTo('#material-input').attr('id','material-select' + (selectId+1)).addClass('child-select');
            cloneDiv.children("select").attr('id','material-' + (selectId+1)).select2();
            select.select2();
            
            $('<div/>').attr('id','material-amount' + (amountId+1)).addClass('input-group mb-2 child-amount').html($('<input class="form-control" placeholder="Amount" required="" name="material_amount[]" type="text" value="">').addClass('form-control')).append($('<div/>').addClass('input-group-append').html($('<button type="button"></button>').addClass('btn btn-outline-danger waves-effect waves-themed remove').html($('<i/>').addClass('fal fa-minus')))).insertAfter($('[id^=material-amount]').last());
        });

        $(document).on('click','button.remove', function(e){
            e.preventDefault();
            $(this).closest('div.child-amount').remove();
            $('.child-select').last().remove();
        });

        // error hold
        if ($('#repair_status').val() == 1) {
            $('#using-material').show();
        }
        if ($('#using_material').val() == 1) {
            $('#material-form').show();
        }
    });
</script>
@endsection
@extends('layouts.page')

@section('title', 'User Edit')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/formplugins/select2/select2.bundle.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2></h2>
                <div class="panel-toolbar">
                    <a class="nav-link active" href="{{route('users.index')}}"><i class="fal fa-arrow-alt-left">
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
                    {!! Form::open(['route' => ['users.update',$user->uuid],'method' => 'PUT','class' =>
                    'needs-validation','novalidate']) !!}
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('name','Full Name',['class' => 'required form-label'])}}
                        {{ Form::text('name',$user->name,['placeholder' => 'Full Name','class' => 'form-control '.($errors->has('name') ? 'is-invalid':''),'required'])}}
                        @if ($errors->has('name'))
                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('email','Email Address',['class' => 'required form-label'])}}
                        {{ Form::text('email',$user->email,['placeholder' => 'Email Address','class' => 'form-control '.($errors->has('email') ? 'is-invalid':''),'required'])}}
                        @if ($errors->has('email'))
                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {{ Form::label('password','Password',['class' => 'required form-label'])}}
                        <div id="password" class="input-group">
                            {{ Form::text('password',null,['id' => 'passwordForm','placeholder' => '********','class' => 'form-control '.($errors->has('password') ? 'is-invalid':''),'required','disabled' => 'disabled'])}}
                            <div class="input-group-append">
                                <button id="getNewPass" type="button"
                                    class="btn btn-primary waves-effect waves-themed getNewPass"
                                    disabled="disabled">Generate</button>
                            </div>
                            @if ($errors->has('password'))
                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="enablePassChange">
                            <label class="custom-control-label" for="enablePassChange">Ubah Password ?</label>
                        </div>
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        {!! Form::label('role', 'Role', ['class' => 'required form-label']) !!}
                        {!! Form::select('role', $roles, $user->roles[0]['name'], ['class' => 'select2
                        form-control'.($errors->has('role') ? 'is-invalid':''), 'required'
                        => '', 'placeholder' => 'Select a role ...',$user->roles[0]['name'] == "unit" ? 'disabled':''])
                        !!}
                        @if ($errors->has('role'))
                        <div class="help-block text-danger">{{ $errors->first('role') }}</div>
                        @endif
                    </div>
                    @if ($user->roles[0]['name'] == "unit")
                    <div class="form-row">
                        <div class="form-group col-md-3 mb-3">
                            {{ Form::label('witel','Witel',['class' => 'required form-label'])}}
                            <select id="witel" class="select2 form-control" name="witel">
                                <option value="{{$user->unit->witel->uuid}}">{{$user->unit->witel->name}}
                                </option>
                                @foreach($witels as $witel)
                                <option value="{{$witel->uuid}}">{{$witel->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('witel'))
                            <div class="help-block text-danger">{{ $errors->first('witel') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-md-3 mb-3">
                            {{ Form::label('unit','Unit',['class' => 'required form-label'])}}
                            <select id="unit" class="select2 form-control" name="unit">
                                <option value="{{$user->unit->uuid}}">{{$user->unit->name}}
                            </select>
                            @if ($errors->has('unit'))
                            <div class="help-block text-danger">{{ $errors->first('unit') }}</div>
                            @endif
                        </div>
                    </div>
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

        $("#witel").change(function(){
            var witel_uuid = $(this).val();
            $.ajax({
                url:"{{route('getUnit')}}",
                type: 'GET',
                data: {witel_uuid:witel_uuid},
                success: function(e) {
                    $("#unit").empty();
                    $("#unit").append('<option value="">Pilih Module Unit</option>');
                    $.each(e, function(key, value) {
                        $("#unit").append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        });
    });
</script>
@endsection
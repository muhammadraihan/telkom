@extends('layouts.page')

@section('title','Dashboard')

@section('content')
<div class="subheader">
    <h1 class="subheader-title">
        <i class='fal fa-desktop'></i> Dashboard
    </h1>
</div>
<div class="fs-lg fw-300 p-5 bg-white border-faded rounded mb-g">
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g">
                <div class="">
                    <h3 class="display-4 d-block l-h-n m-0 fw-500">
                        {{$all_ticket}}
                        <small class="m-0 l-h-n">ALL TICKET</small>
                    </h3>
                </div>
                <i class="fal fa-ticket position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                    style="font-size:6rem"></i>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="p-3 bg-info-300 rounded overflow-hidden position-relative text-white mb-g">
                <div class="">
                    <h3 class="display-4 d-block l-h-n m-0 fw-500">
                        {{$closed_ticket}}
                        <small class="m-0 l-h-n">CLOSED TICKET</small>
                    </h3>
                </div>
                <i class="fal fa-dolly-flatbed position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4"
                    style="font-size: 6rem;"></i>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="p-3 bg-danger-200 rounded overflow-hidden position-relative text-white mb-g">
                <div class="">
                    <h3 class="display-4 d-block l-h-n m-0 fw-500">
                        {{$repair_ticket}}
                        <small class="m-0 l-h-n">TICKET PROGRESS AT REPAIR</small>
                    </h3>
                </div>
                <i class="fal fa-cogs position-absolute pos-right pos-bottom opacity-15 mb-n5 mr-n6"
                    style="font-size: 8rem;"></i>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="p-3 bg-warning-300 rounded overflow-hidden position-relative text-white mb-g">
                <div class="">
                    <h3 class="display-4 d-block l-h-n m-0 fw-500">
                        {{$warehouse_ticket}}
                        <small class="m-0 l-h-n">TICKET PROGRESS AT WAREHOUSE</small>
                    </h3>
                </div>
                <i class="fal fa-warehouse position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4"
                    style="font-size: 6rem;"></i>
            </div>
        </div>
    </div>
</div>
@endsection
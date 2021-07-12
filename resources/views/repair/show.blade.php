<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>Tiket Nomor :<span class="fw-300"><i>{{ $repair_job->repair->ticket->ticket_number }}</i></span>
                </h2>
                <a class="nav-link active" href="#"><i class="fal fa-calendar-alt">
                    </i>
                    <span
                        class="nav-link-text">{{ \Carbon\Carbon::parse($repair_job->repair->ticket->created_at)->translatedFormat('j F Y H:i:s') }}</span>
                </a>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('module_category','Categoy :',['class' => 'form-label'])}}
                            {{ $repair_job->repair->ModuleType->brand->moduleName->category->name }}
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            {{ Form::label('module_name','Name :',['class' => 'form-label'])}}
                            {{ $repair_job->repair->ModuleType->brand->moduleName->name }}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('module_brand','Brand :',['class' => 'form-label'])}}
                            {{ $repair_job->repair->ModuleType->brand->name }}
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            {{ Form::label('module_type','Type :',['class' => 'form-label'])}}
                            {{ $repair_job->repair->ModuleType->name }}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('part_number','Part Number :',['class' => 'form-label'])}}
                            {{ $repair_job->repair->part_number }}
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('serial_number','Serial Number :',['class' => 'form-label'])}}
                            {{ $repair_job->repair->serial_number }}
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('msc_number','MSC Number :',['class' => 'form-label'])}}
                            {{ $repair_job->repair->serial_number_msc }}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('accessories','Accesories :',['class' => 'form-label'])}}
                            @isset($repair_job->repair->accessories)
                            <div>
                                <ul class="">
                                    @foreach ($repair_job->repair->accessories as $item)
                                    <li class="">
                                        <span>{{$item}}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endisset
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('warranty_status','Warranty Status :',['class' => 'form-label'])}}
                            {!! Helper::WarrantyStatus($repair_job->repair->warranty_status)!!}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 mb-3">
                            {{ Form::label('complain','Complain:',['class' => 'form-label'])}}
                            <div class="text-justify">
                                {{$repair_job->repair->complain}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
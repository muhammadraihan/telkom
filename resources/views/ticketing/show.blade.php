<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>Ticket Number :<span class="fw-300"><i>{{ $repair_item->ticket->ticket_number }}</i></span></h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('module_category','Categoy :',['class' => 'form-label'])}}
                            {{ $repair_item->ModuleType->brand->moduleName->category->name }}
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            {{ Form::label('module_name','Name :',['class' => 'form-label'])}}
                            {{ $repair_item->ModuleType->brand->moduleName->name }}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('module_brand','Brand :',['class' => 'form-label'])}}
                            {{ $repair_item->ModuleType->brand->name }}
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            {{ Form::label('module_type','Type :',['class' => 'form-label'])}}
                            {{ $repair_item->ModuleType->name }}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('part_number','Part Number :',['class' => 'form-label'])}}
                            {{ $repair_item->part_number }}
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('serial_number','Serial Number :',['class' => 'form-label'])}}
                            {{ $repair_item->serial_number }}
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('msc_number','MSC Number :',['class' => 'form-label'])}}
                            {{ $repair_item->serial_number_msc }}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('accessories','Accesories :',['class' => 'form-label'])}}
                            @isset($repair_item->accessories)
                            <div>
                                <ul class="">
                                    @foreach ($repair_item->accessories as $item)
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
                            {!! Helper::WarrantyStatus($repair_item->warranty_status)!!}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 mb-3">
                            {{ Form::label('complain','Complain:',['class' => 'form-label'])}}
                            <div class="text-justify">
                                {{$repair_item->complain}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
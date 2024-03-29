<div class="row">
    <div class="col-md-12 col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>Ticket</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('unit','Unit :',['class' => 'form-label'])}}
                            {{$detail_item->repair->ticket->unit->name}}
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('witel','Witel :',['class' => 'form-label'])}}
                            {{$detail_item->repair->ticket->unit->witel->name}}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('ticket_number','Ticket Number :',['class' => 'form-label'])}}
                            {{ $detail_item->repair->ticket->ticket_number }}
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('ticket_date','Ticket Date :',['class' => 'form-label'])}}
                            {{ \Carbon\Carbon::parse($detail_item->repair->ticket->created_at)->translatedFormat('j F Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="panel-2" class="panel">
            <div class="panel-hdr">
                <h2>Module</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('module_category','Categoy :',['class' => 'form-label'])}}
                            {{ $detail_item->repair->ModuleType->brand->moduleName->category->name }}
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            {{ Form::label('module_name','Name :',['class' => 'form-label'])}}
                            {{ $detail_item->repair->ModuleType->brand->moduleName->name }}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('module_brand','Brand :',['class' => 'form-label'])}}
                            {{ $detail_item->repair->ModuleType->brand->name }}
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            {{ Form::label('module_type','Type :',['class' => 'form-label'])}}
                            {{ $detail_item->repair->ModuleType->name }}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('part_number','Part Number :',['class' => 'form-label'])}}
                            {{ $detail_item->repair->part_number }}
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('serial_number','Serial Number :',['class' => 'form-label'])}}
                            {{ $detail_item->repair->serial_number }}
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('msc_number','MSC Number :',['class' => 'form-label'])}}
                            {{ $detail_item->repair->serial_number_msc }}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('accessories','Accesories :',['class' => 'form-label'])}}
                            @isset($detail_item->repair->accessories)
                            <div>
                                <ul class="">
                                    @foreach ($detail_item->repair->accessories as $item)
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
                            {!! Helper::WarrantyStatus($detail_item->repair->warranty_status)!!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="panel-3" class="panel">
            <div class="panel-hdr">
                <h2>Repair</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="form-row">
                        @isset($detail_item->repair->repair_status)
                        <div class="form-group col-md-6 mb-3">
                            {{ Form::label('repair_status','Repair Status :',['class' => 'form-label'])}}
                            {!!Helper::RepairStatus($detail_item->repair->repair_status)!!}
                        </div>
                        @endisset
                        <div class="form-group col-md-6 mb-3">
                            {{ Form::label('item_status','Module Status :',['class' => 'form-label'])}}
                            {!! Helper::ItemStatus($detail_item->item_status) !!}
                        </div>
                    </div>
                    <div class="form-row">
                        @isset($detail_item->repair->complain)
                        <div class="form-group col-md-6 mb-3">
                            {{ Form::label('complain','Complain:',['class' => 'form-label'])}}
                            <div class="text-justify">
                                {{$detail_item->repair->complain}}
                            </div>
                        </div>
                        @endisset
                        @isset($detail_item->repair->JobOrder->repair_notes)
                        <div class="form-group col-md-6 mb-3">
                            {{ Form::label('repair_notes','Repair Notes:',['class' => 'form-label'])}}
                            <div class="text-justify">
                                {{$detail_item->repair->JobOrder->repair_notes}}
                            </div>
                        </div>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
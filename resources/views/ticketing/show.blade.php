<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>Tiket Nomor :<span class="fw-300"><i>{{ $repair_item->ticket->ticket_number }}</i></span></h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Module Category :</strong><span class="fw-400"><i>
                                    {{ $repair_item->ModuleCategory->name }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Module Name :</strong><span class="fw-400"><i>
                                    {{ $repair_item->ModuleName->name }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Module Brand :</strong><span class="fw-400"><i>
                                    {{ $repair_item->ModuleBrand->name }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Module Type :</strong><span class="fw-400"><i>
                                    {{ $repair_item->ModuleType->name }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Part Number :</strong><span class="fw-400"><i>
                                    {{ $repair_item->part_number }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Serial Number :</strong><span class="fw-400"><i>
                                    {{ $repair_item->serial_number }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Serial Number MSC :</strong><span class="fw-400"><i>
                                    {{ $repair_item->serial_number_msc }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Warranty Status :</strong><span class="fw-400"><i>
                                    @switch($repair_item->warranty_status)
                                    @case(1)
                                    Warranty
                                    @break
                                    @case(0)
                                    Non Warranty
                                    @break
                                    @default
                                    Status Unknown
                                    @endswitch
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Kelengkapan :</strong><span class="fw-400"><i>
                        </div>
                        <div>
                            <ul class="">
                                @isset($repair_item->accessories)
                                @foreach ($repair_item->accessories as $item)
                                <li class="">
                                    <span>{{$item}}</span>
                                </li>
                                @endforeach
                                @endisset
                            </ul>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Complain :</strong><span class="fw-400"><i>
                                    {{ $repair_item->complain }}</i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
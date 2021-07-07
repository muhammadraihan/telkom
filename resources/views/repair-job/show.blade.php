<div class="row">
    <div class="col-xl-8">
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
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Category :</strong><span class="fw-400"><i>
                                    {{ $repair_job->repair->ModuleType->brand->moduleName->category->name }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Name :</strong><span class="fw-400"><i>
                                    {{ $repair_job->repair->ModuleType->brand->moduleName->name }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Name :</strong><span class="fw-400"><i>
                                    {{ $repair_job->repair->ModuleType->brand->name }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Type :</strong><span class="fw-400"><i>
                                    {{ $repair_job->repair->ModuleType->name }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Part Number :</strong><span class="fw-400"><i>
                                    {{ $repair_job->repair->part_number }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Serial Number :</strong><span class="fw-400"><i>
                                    {{ $repair_job->repair->serial_number }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Serial MSC :</strong><span class="fw-400"><i>
                                    {{ $repair_job->repair->serial_number_msc }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Warranty Status :</strong><span class="fw-400"><i>
                                    @if ($repair_job->repair->warranty_status == 0)
                                    Warranty
                                    @else
                                    Non Warranty
                                    @endif</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Accessories :</strong><span class="fw-400"><i>
                        </div>
                        <div>
                            <ul class="">
                                @isset($repair_job->repair->accessories)
                                @foreach ($repair_job->repair->accessories as $item)
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
                                    {{ $repair_job->repair->complain }}</i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
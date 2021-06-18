{{-- {{dd($repair_item->repair->ticket->ticket_number)}} --}}
<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>Tiket Nomor :<span class="fw-300"><i>{{ $repair_item->repair->ticket->ticket_number }}</i></span>
                </h2>
                <a class="nav-link active" href="#"><i class="fal fa-calendar-alt">
                    </i>
                    <span
                        class="nav-link-text">{{ \Carbon\Carbon::parse($repair_item->repair->ticket->created_at)->translatedFormat('j F Y H:i:s') }}</span>
                </a>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Merk :</strong><span class="fw-400"><i>
                                    {{ $repair_item->repair->item_merk }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Type :</strong><span class="fw-400"><i>
                                    {{ $repair_item->repair->item_type }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Model :</strong><span class="fw-400"><i>
                                    {{ $repair_item->repair->item_model }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Serial Number :</strong><span class="fw-400"><i>
                                    {{ $repair_item->repair->serial_number }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Part Number :</strong><span class="fw-400"><i>
                                    {{ $repair_item->repair->part_number }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Barcode :</strong><span class="fw-400"><i>
                                    {{ $repair_item->repair->barcode }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Status Garansi :</strong><span class="fw-400"><i>
                                    @if ($repair_item->repair->status_garansi == 0)
                                    Masih Garansi
                                    @else
                                    Garansi Habis
                                    @endif</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Kelengkapan :</strong><span class="fw-400"><i>
                        </div>
                        <div>
                            <ul class="">
                                @isset($repair_item->repair->kelengkapan)
                                @foreach ($repair_item->repair->kelengkapan as $item)
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
                            <strong>Kerusakan :</strong><span class="fw-400"><i>
                                    {{ $repair_item->repair->kerusakan }}</i></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="d-flex mb-2">
                            <strong>Ket. Teknisi :</strong><span class="fw-400"><i>
                                    {{ $repair_item->keterangan }}</i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
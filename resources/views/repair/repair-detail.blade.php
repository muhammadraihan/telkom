<div class="row">
    <div class="col-lg-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>Ticket Number :<span class="fw-300"><i>{{ $repair_job->repair->ticket->ticket_number }}</i></span>
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
                            {{ Form::label('repair_status','Repair Status :',['class' => 'form-label'])}}
                            {!!Helper::RepairJobItemStatus($repair_job->item_status)!!}
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('job_status','Job Status :',['class' => 'form-label'])}}
                            {!! Helper::JobStatus($repair_job->job_status)!!}
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('ticket_status','Ticket Status :',['class' => 'form-label'])}}
                            {!! Helper::TicketStatus($repair_job->repair->ticket->ticket_status)!!}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('job_date','Job Date :',['class' => 'form-label'])}}
                            {{\Carbon\Carbon::parse($repair_job->assign_at)->translatedFormat('j M Y h:i:s')}}
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('job_finished','Job Finished :',['class' => 'form-label'])}}
                            {{\Carbon\Carbon::parse($repair_job->updated_at)->translatedFormat('j M Y h:i:s')}}
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('time_to_repair','Time to Repair :',['class' => 'form-label'])}}
                            {{number_format($repair_job->time_to_repair, 2) . ' ' . 'Hours'}}
                        </div>
                    </div>
                    @isset($repair_job->component_used)
                    <div class="form-row">
                        <div class="form-group col-md-3 mb-3">
                            {{ Form::label('component_used','Component Used :',['class' => 'form-label'])}}
                            <div>
                                <ul class="">
                                    @foreach ($materials as $item)
                                    <li class="">
                                        <span>{{$item['material_type']}}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="form-group col-md-2 mb-3">
                            {{ Form::label('component_used','Used Amount:',['class' => 'form-label'])}}
                            <div>
                                <ul class="">
                                    @foreach ($materials as $item)
                                    <li class="">
                                        <span>{{$item['amount_used']}}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="form-group col-md-3 mb-3">
                            {{ Form::label('unit_price','Unit Price:',['class' => 'form-label'])}}
                            <div>
                                <ul class="">
                                    @foreach ($materials as $item)
                                    <li class="">
                                        <span>{{'Rp.'. number_format($item['unit_price'], 2) }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="form-group col-md-3 mb-3">
                            {{ Form::label('total_price','Total Price:',['class' => 'form-label'])}}
                            <div>
                                <ul class="">
                                    @foreach ($materials as $item)
                                    <li class="">
                                        <span>{{'Rp.'. number_format($item['total_price'], 2) }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endisset
                    <div class="form-row">
                        <div class="form-group col-md-4 mb-3">
                            {{ Form::label('repair_cost','Repair Cost:',['class' => 'form-label'])}}
                            {{'Rp.'.''.number_format($repair_job->repair_cost, 2)}}
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 mb-3">
                            {{ Form::label('complain','Complain:',['class' => 'form-label'])}}
                            <div class="text-justify">
                                {{$repair_job->repair->complain}}
                            </div>
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            {{ Form::label('repair_notes','Repair Notes:',['class' => 'form-label'])}}
                            <div class="text-justify">
                                {{$repair_job->repair_notes}}
                            </div>
                        </div>
                    </div>
                    <div class="form-row"></div>
                </div>
            </div>
        </div>
    </div>
</div>
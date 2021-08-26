@extends('layouts.page')

@section('title', 'Technician Job Order Management')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/datagrid/datatables/datatables.bundle.css')}}">
@endsection

@section('content')
<div class="subheader">
    <h1 class="subheader-title">
        <i class='subheader-icon fal fa-wrench'></i> Repair Job
    </h1>
</div>
<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Repair Job <span class="fw-300"><i>List</i></span>
                </h2>
                <div class="panel-toolbar">
                    @role('repair')
                    <a class="nav-link active" href="{{route('repair-job.history')}}"><i class="fal fa-list-alt">
                        </i>
                        <span class="nav-link-text">All Progress</span>
                    </a>
                    @endrole
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip"
                        data-offset="0,10" data-original-title="Fullscreen"></button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <!-- datatable start -->
                    <table id="datatable" class="table table-bordered table-hover table-striped w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Ticket</th>
                                <th>Assigned Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- item detail modal start -->
<div class="modal fade" id="detail-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    MODULE DETAIL
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body" id="detail-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- item detail modal end -->
@endsection

@section('js')
<script src="{{asset('js/datagrid/datatables/datatables.bundle.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "order": [[ 0, "asc" ]],
            "ajax": {
                url:'{{route('repair-job.index')}}',
                type : "GET",
                dataType: 'json',
                error: function(data) {
                    console.log(data);
                }
            },
            "columns": [
                {data: 'rownum',searchable: false},
                {data: 'ticket_number'},
                {data: 'assign_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $('#datatable').on('click', '#detail-button[data-attr]', function (e) {
            e.preventDefault();
            var href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#detail-modal').modal("show");
                    $('#detail-body').html(result).show();
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            });
        });
    });
</script>
@endsection
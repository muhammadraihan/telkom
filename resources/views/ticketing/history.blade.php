@extends('layouts.page')

@section('title', 'Ticketing Management')

@section('css')
<link rel="stylesheet" media="screen, print" href="{{asset('css/datagrid/datatables/datatables.bundle.css')}}">
@endsection

@section('content')
<div class="subheader">
    <h1 class="subheader-title">
        <i class='subheader-icon fal fa-ticket'></i> Module: <span class='fw-300'>Complain Ticket</span>
        <small>
            Module for manage complain ticket
        </small>
    </h1>
</div>
<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Ticket <span class="fw-300"><i>List</i></span>
                </h2>
                <div class="panel-toolbar">
                    <a class="nav-link active" href="{{route('ticketing.index')}}"><i class="fal fa-arrow-alt-left">
                        </i>
                        <span class="nav-link-text">Back</span>
                    </a>
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
                                <th>Witel</th>
                                <th>Unit</th>
                                <th>Ticket Number</th>
                                <th>Ticket Status</th>
                                <th>Item Status</th>
                                <th>Urgent Status</th>
                                <th>Ticket Date</th>
                                <th>Ticket Issuer</th>
                                <th>Note</th>
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
            responsive: true,
            "order": [[ 0, "asc" ]],
            "ajax":{
                url:'{{route('ticketing.history')}}',
                type : "GET",
                dataType: 'json',
                error: function(data){
                    console.log(data);
                    }
            },
            "columns": [
            {data: 'DT_RowIndex',searchable:false},
            {data: 'witel'},
            {data: 'uuid_unit'},
            {data: 'ticket_number'},
            {data: 'ticket_status'},
            {data: 'item_status'},
            {data: 'urgent_status'},
            {data: 'created_at'},
            {data: 'created_by'},
            {data: 'note'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    // Delete Data
    $('#datatable').on('click', '.delete-btn[data-url]', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var url = $(this).attr('data-url');
            var token = $(this).attr('data-token');
            $(".delete-form").attr("action",url);
            $('body').find('.delete-form').append('<input name="_token" type="hidden" value="'+ token +'">');
            $('body').find('.delete-form').append('<input name="_method" type="hidden" value="DELETE">');
            $('body').find('.delete-form').append('<input name="id" type="hidden" value="'+ id +'">');
        });

        // Clear Data When Modal Close
        $('.remove-data-from-delete-form').on('click',function() {
            $('body').find('.delete-form').find("input").remove();
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
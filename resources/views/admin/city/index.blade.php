@extends('layouts.admin')
@section('content')
<div class="page-content">
    <div class="container-fluid">
        @include("partials.alert")
        <div class="row">
            <div class="col-lg-12 mb-3 text-right">
                <a class="btn btn-primary" href="{{ route('cities.create') }}">
                    Add City
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                City List
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover datatable" id="table_DT" style="width:100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
        var table = $('#table_DT');

        oTable = table.dataTable({
            "processing": false,
            "serverSide": true,
            "language": {
                "aria": {
                    "sortAscending": ": click to sort column ascending",
                    "sortDescending": ": click to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No entries found",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "_MENU_ entries",
                "search": "Search:",
                "zeroRecords": "No matching records found",
                "paginate": {
                    "previous": "Prev",
                    "next": "Next",
                    "last": "Last",
                    "first": "First",
                    "page": "Page",
                    "pageOf": "of"
                }
            },
            "columns": [{
                    "title": "ID",
                    "data": "id"
                },
                {
                    "title": "City Name",
                    "data": "name"
                },
                {
                    "title": "State Name",
                    "data": "state_id"
                },
                {
                    "title": "country Name",
                    "data": "country_id"
                },
                {
                    "title": "Latitude",
                    "data": "latitude"
                },
                {
                    "title": "Longitude",
                    "data": "longitude"
                },
                {
                    "title": "Action",
                    "data": "action",
                    searchble: false,
                    sortable: false
                }
            ],
            responsive: false,
            "order": [
                [0, 'asc']
            ],
            "lengthMenu": [
                [10, 20, 50, 100],
                [10, 20, 50, 100]
            ],
            "pageLength": 10,
            "ajax": {
                "url": "{{ route('cities.listing') }}", // ajax source
            },
            "dom": "<'row' <'col-md-12'>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
        });
    });
</script>
@endsection
@extends('layouts.master')
@section('title')
    Report Top Products
@endsection
@section('css')
    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
@endsection
@section('content')
    <x-breadcrumb title="Top Selling Products" pagetitle="Report" />

    <div class="row" id="top-products-list">
        <div class="col-xxl-9">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-end g-2 w-100">
                        <div class="col-12 col-sm-6 col-xxl-2">
                            <input type="text" class="form-control" data-provider="flatpickr"
                                data-date-format="d M, Y" data-range-date="true" id="demo-datepicker"
                                placeholder="Select date">
                        </div>
                        <div class="col-12 col-sm-6 col-xxl-2">
                            <div class="hstack gap-2">
                                <button type="button" class="btn btn-primary w-100" onclick="filterData();">
                                    <i class="bi bi-filter me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tb_data" class="display table table-bordered dt-responsive"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Product</th>
                                <th>Units Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

    <!-- page js -->
    <script src="{{ URL::asset('build/js/backend/report-top-products.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

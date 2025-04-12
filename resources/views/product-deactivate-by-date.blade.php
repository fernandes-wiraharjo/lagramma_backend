@extends('layouts.master')
@section('title')
    Product Deactivate By Date
@endsection
@section('css')
    <!-- extra css -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
@endsection
@section('content')
    <x-breadcrumb title="Deactivate By Date" pagetitle="Master > {{ $product->name }}" />

    <div class="row">
        <div class="col-xxl-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0" id="addLabel">Create</h6>
                </div>
                <div class="card-body">
                    <form autocomplete="off" class="needs-validation createForm" id="createForm" novalidate>
                        <input type="hidden" id="id" name="id" class="form-control" value="">
                        <div class="row">
                            <div class="col-xxl-12 col-lg-12">
                                <div class="mb-3">
                                    <label for="date_range" class="form-label">Date Range <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" data-provider="flatpickr"
                                        data-date-format="d M, Y" data-range-date="true" id="date_range"
                                        name="date_range" placeholder="Enter date range" required>
                                    <div class="invalid-feedback">Please enter date range.</div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="text-end">
                                    <button type="submit" id="addBtn" class="btn btn-success">Add</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xxl-9">
            <div class="card">
                <div class="card-body">
                    <table id="tb_data" class="display table table-bordered dt-responsive"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Action</th>
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
    <script>
        const idProduct = @json($product->id);;
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

    <!-- page js -->
    <script src="{{ URL::asset('build/js/backend/product-deactivate-by-date.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

@extends('layouts.master')
@section('title')
    Product Deactivate By Date
@endsection
@section('css')
    <!-- extra css -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- datatable css -->
    <link rel="stylesheet" href="{{ URL::asset('datatables/css/dataTables.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('datatables/css/responsive.bootstrap.min.css') }}" />
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

    <!-- jQuery -->
    <script src="{{ URL::asset('jquery/jquery-3.6.0.min.js') }}"></script>

    <!-- datatable js -->
    <script src="{{ URL::asset('datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('datatables/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('datatables/js/dataTables.responsive.min.js') }}"></script>

    <!-- page js -->
    <script src="{{ URL::asset('build/js/backend/product-deactivate-by-date.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

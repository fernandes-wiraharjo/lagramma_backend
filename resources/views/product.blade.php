@extends('layouts.master')
@section('title')
    Product
@endsection
@section('css')
    <!-- extra css -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <!-- Sweet Alert css-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <x-breadcrumb title="Product" pagetitle="Master" />

    <div class="row">
        <div class="col-xxl-9">
            <div class="card">
                <div class="card-header d-flex justify-content-end">
                    <button type="button" class="btn btn-primary sync-btn" id="syncBtn">
                        <span id="syncBtnText">Sync</span>
                        <span id="syncBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
                <div class="card-body">
                    <table id="tb_data" class="display table table-bordered dt-responsive"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Modifier</th>
                                <th>Weight (kg)</th>
                                <th>Dimension (L x W x H cm)</th>
                                <th>Active</th>
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

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editProductForm">
            @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_product_id">
                        <div class="mb-3">
                            <label for="edit_weight" class="form-label">Weight (kg)</label>
                            <input type="number" class="form-control" id="edit_weight" name="weight" step="0.50" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_width" class="form-label">Width (cm)</label>
                            <input type="number" class="form-control" id="edit_width" name="width" step="0.50">
                        </div>
                        <div class="mb-3">
                            <label for="edit_height" class="form-label">Height (cm)</label>
                            <input type="number" class="form-control" id="edit_height" name="height" step="0.50">
                        </div>
                        <div class="mb-3">
                            <label for="edit_length" class="form-label">Length (cm)</label>
                            <input type="number" class="form-control" id="edit_length" name="length" step="0.50">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

    <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- page js -->
    <script src="{{ URL::asset('build/js/backend/product.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

@extends('layouts.master')
@section('title')
    Hampers Setting
@endsection
@section('css')
    <!-- extra css -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--select2 css-->
    <link rel="stylesheet" href="{{ URL::asset('select2/css/select2.min.css') }}" />
    <!--datatable css-->
    <link rel="stylesheet" href="{{ URL::asset('datatables/css/dataTables.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('datatables/css/responsive.bootstrap.min.css') }}" />
@endsection
@section('content')
    <x-breadcrumb title="Hampers Setting" pagetitle="Product" />

    <div class="row">
        <div class="col-xxl-9">
            <div class="card">
                <div class="card-header d-flex justify-content-end">
                    <button type="button" class="btn btn-primary add-btn" data-bs-toggle="modal"
                        data-bs-target="#showModal">
                        Add
                    </button>
                </div>
                <div class="card-body">
                    <table id="tb_data" class="display table table-bordered dt-responsive"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Hampers</th>
                                <th>Max Items</th>
                                <th>Items</th>
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

    <!-- modal add/edit -->
    <div class="modal fade showModal" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header px-4 pt-4">
                    <h5 class="modal-title" id="exampleModalLabel">Add</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form class="tablelist-form" novalidate autocomplete="off">
                    <div class="modal-body p-4">
                        <div id="alert-error-msg" class="d-none alert alert-danger py-2"></div>
                        <input type="hidden" id="id" />

                        <!-- Select a hamper product -->
                        <div class="mb-3">
                            <label for="hampers" class="form-label">Hampers</label>
                            <select class="form-select" id="hampers" name="hampers" aria-label="hampers" required>
                                @foreach ($hamperProducts as $index => $hamperProduct)
                                    <option value="{{ $hamperProduct->id }}">
                                        {{ $hamperProduct->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                         <!-- Select allowed items (non-hampers) -->
                        <div class="mb-3">
                            <label for="allowed_items" class="form-label">Allowed Items</label>
                            <select name="item_ids[]" id="allowed_items" class="form-select" multiple required>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Maximum items allowed -->
                        <div class="mb-3">
                            <label for="max_items" class="form-label">Max Items Allowed</label>
                            <input type="number" name="max_items" id="max_items" class="form-control" required min="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-ghost-danger" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- jQuery -->
    <script src="{{ URL::asset('jquery/jquery-3.6.0.min.js') }}"></script>

    <!--select2 js-->
    <link rel="stylesheet" href="{{ URL::asset('select2/js/select2.min.js') }}" />

    <!--datatable js-->
    <script src="{{ URL::asset('datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('datatables/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('datatables/js/dataTables.responsive.min.js') }}"></script>

    <!-- page js -->
    <script src="{{ URL::asset('build/js/backend/hampers-setting.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

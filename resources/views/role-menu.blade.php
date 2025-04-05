@extends('layouts.master')
@section('title')
    Role Menu
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
    <x-breadcrumb title="Role Menu" pagetitle="User Management" />

    <div class="row">
        <div class="col-xxl-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0" id="addLabel">Create Role Menu</h6>
                </div>
                <div class="card-body">
                    <form autocomplete="off" class="needs-validation createRoleMenuForm" id="createRoleMenuForm" novalidate>
                        <input type="hidden" id="role_id" name="role_id" class="form-control" value="">
                        <div class="row">
                            <div class="col-xxl-12 col-lg-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select" id="role" name="role_id" aria-label="role">
                                        @foreach ($roles as $index => $role)
                                            <option value="{{ $role->id }}" {{ $index === 0 ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xxl-12 col-lg-6">
                                <div class="mb-3">
                                    <label for="menu" class="form-label">Menu</label>
                                    <select class="form-select" id="menu" name="menu_ids[]" data-choices
                                        data-choices-removeItem multiple aria-label="menu">
                                        @foreach ($menus as $index => $menu)
                                            <option value="{{ $menu->id }}" {{ $index === 0 ? 'selected' : '' }}>
                                                {{ $menu->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="text-end">
                                    <button type="submit" id="addNew" class="btn btn-success">Add</button>
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
                    <table id="tb_role_menus" class="display table table-bordered dt-responsive"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Menu</th>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

    <!-- page js -->
    <script src="{{ URL::asset('build/js/backend/role-menus.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

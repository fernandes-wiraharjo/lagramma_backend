@extends('layouts.master')
@section('title')
    My Account
@endsection
@section('css')
    <!-- extra css -->
    <!--Swiper slider css-->
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <x-breadcrumb title="My Account" pagetitle="Account" />

    <div class="row">
        <div class="col-xxl-9">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="profile-user-img position-relative">
                                <img src="{{ URL::asset('build/images/users/user-dummy-img.jpg') }}" alt=""
                                    class="rounded object-fit-cover">
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge border border-3 border-white rounded-circle bg-success p-1 mt-1 me-1"><span
                                        class="visually-hidden">unread messages</span></span>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-lg-9">
                            <div class="d-flex border-bottom border-bottom-dashed pb-3 mb-3 mt-4 mt-lg-0">
                                <div class="flex-grow-1">
                                    <h5>{{ $user->name }}</h5>
                                    <p class="text-muted mb-0">{{ $user->role->name }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="account-setting" class="btn btn-success">Profile Settings</a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-sm mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        User ID
                                                    </td>
                                                    <td class="fw-medium">
                                                        {{ $user->id }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        Email
                                                    </td>
                                                    <td class="fw-medium">
                                                        {{ $user->email }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Mobile / Phone No.
                                                    </td>
                                                    <td class="fw-medium">
                                                        {{ $user->phone }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-lg-6">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-sm mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Designation
                                                    </td>
                                                    <td class="fw-medium">
                                                        {{ $user->role->name }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Orders
                                                    </td>
                                                    <td class="fw-medium">
                                                        {{ $user->orders()->count() }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Joining Date
                                                    </td>
                                                    <td class="fw-medium">
                                                        {{ $user->formatted_date }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->

                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
@section('scripts')
    <!--Swiper slider js-->
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>

    <!--Account init js-->
    <script src="{{ URL::asset('build/js/backend/account.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

@extends('layouts.master')
@section('title')
    Settings
@endsection
@section('css')
    <!-- extra css -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <!-- Include jQuery and Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .pac-container {
            z-index: 1055 !important;
        }

        .select2-container {
            z-index: 9999 !important;
        }
        .select2-dropdown {
            z-index: 9999 !important;
            position: absolute !important;
        }
    </style>
@endsection
@section('content')
    <x-breadcrumb title="Settings" pagetitle="Account" />

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('account.update') }}" method="POST">
    @csrf
        <div class="row">
            <div class="col-lg-4">
                <h5 class="fs-16">Personal Information</h5>
                <p class="text-muted mb-lg-0">Personal Information.</p>
            </div>
            <!--end col-->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div>
                                    <label for="firstName" class="form-label">Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="firstName" name="name"
                                        placeholder="Enter your name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="designationInput" class="form-label">Designation</label>
                                    <input type="text" class="form-control" id="designationInput"
                                        placeholder="Designation" value="{{ $user->role->name }}" disabled>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div>
                                    <label for="emailInput" class="form-label">Email Address <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="emailInput" placeholder="Enter your email"
                                        value="{{ old('email', $user->email) }}" name="email">
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div>
                                    <label for="phoneInput" class="form-label">Phone Number <span
                                    class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="phoneInput"
                                        placeholder="Enter phone number" value="{{ old('phone', $user->phone) }}" name="phone">
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4">
                                <div>
                                    <label for="birdthdatInput" class="form-label">Joining Date</label>
                                    <input type="text" class="form-control" data-provider="flatpickr" id="birdthdatInput"
                                        data-date-format="d M, Y" data-deafult-date="24 Nov, 2021" placeholder="Select date"
                                        value="{{ $user->created_at }}"
                                        disabled>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-secondary">Update Profile</button>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--edn row-->
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </form>
    <!--end form-->

    <!-- for setting address and geolocation -->
    <div class="row">
        <div class="col-lg-4">
            <h5 class="fs-16">Address</h5>
            <p class="text-muted mb-lg-0">Manage address and geolocation.</p>
        </div>
        <!--end col-->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3" id="address-list">
                        @foreach(auth()->user()->addresses as $address)
                            <div class="col-lg-6">
                                <div>
                                    <div class="form-check card-radio">
                                        <input id="shippingAddress{{ $address->id }}" name="shippingAddress" type="radio"
                                            class="form-check-input" value="{{ $address->id }}">
                                        <label class="form-check-label" for="shippingAddress{{ $address->id }}">
                                            <span class="fs-14 mb-2 fw-semibold d-block">{{ $address->label ?? 'Address' }}</span>
                                            <span class="text-muted fw-normal text-wrap mb-1 d-block">
                                                {{ $address->address }}
                                            </span>
                                            <span class="mt-3 text-muted fw-normal d-block text-wrap">
                                                {{ $address->region_label }}
                                            </span>
                                        </label>
                                    </div>

                                    <div class="d-flex flex-wrap p-2 py-1 bg-light rounded-bottom border mt-n1 fs-13">
                                        <div>
                                            <a href="#" class="d-block text-body p-1 px-2" data-bs-toggle="modal"
                                                data-bs-target="#addAddressModal"  data-edit="true"
                                                data-id="{{ $address->id }}">
                                                <i class="ri-pencil-fill text-muted align-bottom me-1"></i> Edit</a>
                                        </div>
                                        <div>
                                            <a href="#" class="d-block text-body p-1 px-2" data-bs-toggle="modal"
                                                data-bs-target="#removeAddressModal"><i
                                                    class="ri-delete-bin-fill text-muted align-bottom me-1"></i> Remove</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!--end row-->
                    <div class="row mt-4">
                        <div class="col-lg-6">
                            <div class="text-center p-4 rounded-3 border border-2 border-dashed">
                                <div class="avatar-md mx-auto mb-4">
                                    <div class="avatar-title bg-success-subtle text-success rounded-circle display-6">
                                        <i class="bi bi-house-add"></i>
                                    </div>
                                </div>
                                <h5 class="fs-16 mb-3">Add New Address</h5>
                                <button type="button"
                                    class="btn btn-success btn-sm w-xs stretched-link addAddress-modal"
                                    data-bs-toggle="modal" data-bs-target="#addAddressModal">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <!-- Modal add address -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addAddressModalLabel">Add New Address</h1>
                    <button type="button" id="addAddress-close" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form autocomplete="off" class="needs-validation createAddress-form" id="createAddress-form"
                        novalidate>
                        <input type="hidden" id="addressid-input" class="form-control" value="">
                        <div>
                            <div class="mb-3">
                                <label for="addaddress-Name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="addaddress-Name" placeholder="Enter name"
                                    required>
                                <div class="invalid-feedback">Please enter a name.</div>
                            </div>

                            <div class="mb-3">
                                <label for="search-address" class="form-label">Search Address</label>
                                <input type="text" id="search-address" class="form-control" placeholder="Search location...">
                            </div>
                            <div id="map" style="height: 300px; width: 100%; border-radius: 8px;"></div>

                            <div class="mb-3">
                                <label for="addaddress-lat" class="form-label">Latitude</label>
                                <input type="text" class="form-control" id="latitude" required readonly>
                                <div class="invalid-feedback">Please enter a latitude</div>
                            </div>

                            <div class="mb-3">
                                <label for="addaddress-lng" class="form-label">Longitude</label>
                                <input type="text" class="form-control" id="longitude" required readonly>
                                <div class="invalid-feedback">Please enter a longitude</div>
                            </div>

                            <div class="mb-3">
                                <label for="region-select" class="form-label">Select Region <span class="text-muted small">(search by city/district/subdistrict/postal Code)</span></label>
                                <select id="region-select" class="form-control" style="width: 100%;" required></select>
                                <input type="hidden" id="region-label" name="region_label">
                                <input type="hidden" id="region-id" name="region_id">
                            </div>

                            <div class="mb-3">
                                <label for="addaddress-textarea" class="form-label">Address</label>
                                <textarea class="form-control" id="addaddress-textarea" placeholder="Enter address" rows="2" required></textarea>
                                <div class="invalid-feedback">Please enter address.</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="addNewAddress" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- remove address Modal -->
    <div id="removeAddressModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" id="close-removeAddressModal" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you sure ?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure You want to remove this address ?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="remove-address" class="btn w-sm btn-danger">Yes, Delete It!</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
@section('scripts')
    <script>
        const komerceApiKey = @json(config('app.komerce_api_key'));
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- page js-->
    <script src="{{ URL::asset('build/js/backend/account-setting.init.js') }}"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap&libraries=places&v=weekly"></script>

@endsection

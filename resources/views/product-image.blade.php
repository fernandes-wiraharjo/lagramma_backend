@extends('layouts.master')
@section('title')
    Product Image
@endsection
@section('css')
    <!-- extra css -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Sweet Alert css-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <x-breadcrumb title="Image" pagetitle="Master > {{ $product->name }}" />
    <form id="addEditProductImage" autocomplete="off" class="needs-validation" novalidate>
        <div class="row">
            <div>
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title rounded-circle bg-light text-primary fs-20">
                                        <i class="bi bi-images"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">Product Image</h5>
                                <p class="text-muted mb-0">You can add up to 8 product images. (Max 2MB per file)</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dropzone my-dropzone" id="productDropzone">
                            <div class="dz-message">
                                <div class="mb-3">
                                    <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
                                </div>

                                <h5>Drop files here or click to upload.</h5>
                            </div>
                        </div>
                        <div class="error-msg mt-1">Please add a product images.</div>

                        <div class="row mt-4" id="product-image-list">
                            @foreach($product->images as $image)
                                <div class="col-md-3 mb-3" id="image-{{ $image->id }}">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/'.$image->image_path) }}" class="img-fluid rounded" />
                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 delete-image" data-id="{{ $image->id }}">
                                            &times;
                                        </button>

                                        @if(!$image->is_main)
                                            <button type="button" class="btn btn-sm btn-outline-primary mt-2 set-main-image" data-id="{{ $image->id }}">
                                                Set as Main
                                            </button>
                                        @else
                                            <span class="badge bg-success mt-2">Main Image</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- end card -->
                <!-- <div class="text-end mb-3">
                    <button type="submit" class="btn btn-success w-sm">Submit</button>
                </div> -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </form>
@endsection
@section('scripts')
    <script>
        const idProduct = @json($product->id);;
    </script>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- dropzone js -->
    <script src="{{ URL::asset('build/libs/dropzone/dropzone-min.js') }}"></script>

    <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- page js -->
    <script src="{{ URL::asset('build/js/backend/product-image.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

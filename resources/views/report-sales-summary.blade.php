@extends('layouts.master')
@section('title')
    Report Sales Summary
@endsection
@section('content')
    <x-breadcrumb title="Sales Summary" pagetitle="Report" />

    <div class="row" id="sales-summary-list">
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
                    <div class="row row-cols-xxl-5 row-cols-lg-4 row-cols-md-2 row-cols-1">
                        <div class="col">
                            <div class="card shadow-sm border-0 overflow-hidden card-animate">
                                <div class="position-absolute end-0 start-0 top-0 z-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="400" height="250" preserveAspectRatio="none" viewBox="0 0 400 250">
                                        <g mask="url(&quot;#SvgjsMask1530&quot;)" fill="none">
                                            <path d="M209 112L130 191" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M324 10L149 185" stroke-width="8" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M333 35L508 -140" stroke-width="10" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M282 58L131 209" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M290 16L410 -104" stroke-width="6" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M216 186L328 74" stroke-width="6" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M255 53L176 132" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M339 191L519 11" stroke-width="8" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M95 151L185 61" stroke-width="6" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M249 16L342 -77" stroke-width="6" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M129 230L286 73" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M80 216L3 293" stroke-width="6" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                        </g>
                                        <defs>
                                            <mask id="SvgjsMask1530">
                                                <rect width="400" height="250" fill="#ffffff"></rect>
                                            </mask>
                                            <linearGradient x1="100%" y1="0%" x2="0%" y2="100%"
                                                id="SvgjsLinearGradient1531">
                                                <stop stop-color="rgba(var(--tb-primary-rgb), 0)" offset="0"></stop>
                                                <stop stop-color="rgba(var(--tb-primary-rgb), 0.1)" offset="1"></stop>
                                            </linearGradient>
                                            <linearGradient x1="0%" y1="100%" x2="100%" y2="0%"
                                                id="SvgjsLinearGradient1532">
                                                <stop stop-color="rgba(var(--tb-primary-rgb), 0)" offset="0"></stop>
                                                <stop stop-color="rgba(var(--tb-primary-rgb), 0.1)" offset="1"></stop>
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </div>
                                <div class="card-body p-4 z-1 position-relative">
                                    <div class="d-flex align-items-center gap-3">
                                        <div>
                                            <h4 class="fs-22 fw-semibold mb-1"><span class="counter-value" id="total-revenue" data-target="0"></span>
                                            </h4>
                                            <p class="mb-0 fw-medium text-uppercase fs-14">Total Revenue</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card shadow-sm border-0 overflow-hidden card-animate">
                                <div class="position-absolute end-0 start-0 top-0 z-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="400" height="250" preserveAspectRatio="none" viewBox="0 0 400 250">
                                        <g mask="url(&quot;#SvgjsMask1530&quot;)" fill="none">
                                            <path d="M209 112L130 191" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M324 10L149 185" stroke-width="8" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M333 35L508 -140" stroke-width="10" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M282 58L131 209" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M290 16L410 -104" stroke-width="6" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M216 186L328 74" stroke-width="6" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M255 53L176 132" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M339 191L519 11" stroke-width="8" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M95 151L185 61" stroke-width="6" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M249 16L342 -77" stroke-width="6" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M129 230L286 73" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M80 216L3 293" stroke-width="6" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                        </g>
                                        <defs>
                                            <mask id="SvgjsMask1530">
                                                <rect width="400" height="250" fill="#ffffff"></rect>
                                            </mask>
                                            <linearGradient x1="100%" y1="0%" x2="0%" y2="100%"
                                                id="SvgjsLinearGradient1531">
                                                <stop stop-color="rgba(var(--tb-primary-rgb), 0)" offset="0"></stop>
                                                <stop stop-color="rgba(var(--tb-primary-rgb), 0.1)" offset="1"></stop>
                                            </linearGradient>
                                            <linearGradient x1="0%" y1="100%" x2="100%" y2="0%"
                                                id="SvgjsLinearGradient1532">
                                                <stop stop-color="rgba(var(--tb-primary-rgb), 0)" offset="0"></stop>
                                                <stop stop-color="rgba(var(--tb-primary-rgb), 0.1)" offset="1"></stop>
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </div>
                                <div class="card-body p-4 z-1 position-relative">
                                    <div class="d-flex align-items-center gap-3">
                                        <div>
                                            <h4 class="fs-22 fw-semibold mb-1"><span class="counter-value" id="total-orders" data-target="0"></span>
                                            </h4>
                                            <p class="mb-0 fw-medium text-uppercase fs-14">Total Orders</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card shadow-sm border-0 overflow-hidden card-animate">
                                <div class="position-absolute end-0 start-0 top-0 z-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="400" height="250" preserveAspectRatio="none" viewBox="0 0 400 250">
                                        <g mask="url(&quot;#SvgjsMask1530&quot;)" fill="none">
                                            <path d="M209 112L130 191" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M324 10L149 185" stroke-width="8" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M333 35L508 -140" stroke-width="10" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M282 58L131 209" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M290 16L410 -104" stroke-width="6" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M216 186L328 74" stroke-width="6" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M255 53L176 132" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M339 191L519 11" stroke-width="8" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M95 151L185 61" stroke-width="6" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M249 16L342 -77" stroke-width="6" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M129 230L286 73" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M80 216L3 293" stroke-width="6" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                        </g>
                                        <defs>
                                            <mask id="SvgjsMask1530">
                                                <rect width="400" height="250" fill="#ffffff"></rect>
                                            </mask>
                                            <linearGradient x1="100%" y1="0%" x2="0%" y2="100%"
                                                id="SvgjsLinearGradient1531">
                                                <stop stop-color="rgba(var(--tb-primary-rgb), 0)" offset="0"></stop>
                                                <stop stop-color="rgba(var(--tb-primary-rgb), 0.1)" offset="1"></stop>
                                            </linearGradient>
                                            <linearGradient x1="0%" y1="100%" x2="100%" y2="0%"
                                                id="SvgjsLinearGradient1532">
                                                <stop stop-color="rgba(var(--tb-primary-rgb), 0)" offset="0"></stop>
                                                <stop stop-color="rgba(var(--tb-primary-rgb), 0.1)" offset="1"></stop>
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </div>
                                <div class="card-body p-4 z-1 position-relative">
                                    <div class="d-flex align-items-center gap-3">
                                        <div>
                                            <h4 class="fs-22 fw-semibold mb-1"><span class="counter-value" id="total-items" data-target="0"></span>
                                            </h4>
                                            <p class="mb-0 fw-medium text-uppercase fs-14">Total Items</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card shadow-sm border-0 overflow-hidden card-animate">
                                <div class="position-absolute end-0 start-0 top-0 z-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="400" height="250" preserveAspectRatio="none" viewBox="0 0 400 250">
                                        <g mask="url(&quot;#SvgjsMask1530&quot;)" fill="none">
                                            <path d="M209 112L130 191" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M324 10L149 185" stroke-width="8" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M333 35L508 -140" stroke-width="10" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M282 58L131 209" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M290 16L410 -104" stroke-width="6" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M216 186L328 74" stroke-width="6" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M255 53L176 132" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M339 191L519 11" stroke-width="8" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M95 151L185 61" stroke-width="6" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M249 16L342 -77" stroke-width="6" stroke="url(#SvgjsLinearGradient1532)"
                                                stroke-linecap="round" class="TopRight"></path>
                                            <path d="M129 230L286 73" stroke-width="10" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                            <path d="M80 216L3 293" stroke-width="6" stroke="url(#SvgjsLinearGradient1531)"
                                                stroke-linecap="round" class="BottomLeft"></path>
                                        </g>
                                        <defs>
                                            <mask id="SvgjsMask1530">
                                                <rect width="400" height="250" fill="#ffffff"></rect>
                                            </mask>
                                            <linearGradient x1="100%" y1="0%" x2="0%" y2="100%"
                                                id="SvgjsLinearGradient1531">
                                                <stop stop-color="rgba(var(--tb-primary-rgb), 0)" offset="0"></stop>
                                                <stop stop-color="rgba(var(--tb-primary-rgb), 0.1)" offset="1"></stop>
                                            </linearGradient>
                                            <linearGradient x1="0%" y1="100%" x2="100%" y2="0%"
                                                id="SvgjsLinearGradient1532">
                                                <stop stop-color="rgba(var(--tb-primary-rgb), 0)" offset="0"></stop>
                                                <stop stop-color="rgba(var(--tb-primary-rgb), 0.1)" offset="1"></stop>
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </div>
                                <div class="card-body p-4 z-1 position-relative">
                                    <div class="d-flex align-items-center gap-3">
                                        <div>
                                            <h4 class="fs-22 fw-semibold mb-1"><span class="counter-value" id="avg-order-value" data-target="0"></span>
                                            </h4>
                                            <p class="mb-0 fw-medium text-uppercase fs-14">Avg. Order Value</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

    <!-- page js -->
    <script src="{{ URL::asset('build/js/backend/report-sales-summary.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

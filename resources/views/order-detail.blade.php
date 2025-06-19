@extends('layouts.master')
@section('title')
    Order Overview
@endsection
@section('css')
    <style>
        .timeline {
            list-style: none;
            padding: 0;
            position: relative;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            top: 6px;
            left: 10px;
            width: 10px;
            height: 10px;
            background-color: #0d6efd;
            border-radius: 50%;
        }
    </style>
@endsection
@section('content')
    <x-breadcrumb title="Overview" pagetitle="Orders" />

    @if (session('error'))
        <div class="alert alert-danger mt-2">
            {{ session('error') }}
        </div>
    @endif

    <div class="row mb-4 align-items-center">
        <div class="col">
            <h6 class="fs-18 mb-0">Order ID: #{{ $order->id }}</h6>
            <!-- <h6 class="fs-18 mb-0">Invoce No: #{{ $order->invoice_number }}</h6> -->
        </div>
        <!-- <div class="col text-end">
            <button type="button" class="btn btn-secondary"><i class="ph-download-simple me-1 align-middle"></i>
                Invoice</button>
        </div> -->
    </div>

    <div class="row">
        @if (auth()->user()->role->name !== 'customer')
        <div class="col-xxl-3 col-lg-6">
            <div class="card bg-success bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex gap-3">
                        <div class="flex-grow-1">
                            <h6 class="fs-18 mb-3">Customer Info</h6>
                            <p class="mb-0 fw-medium">{{ $order->user->name }}</p>
                            <p class="mb-1">{{ $order->user->email }}</p>
                            <p class="mb-0">{{ $order->user->phone }}</p>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-success-subtle text-success rounded fs-3">
                                <i class="ph-user-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="col-xxl-3 col-lg-6">
            <div class="card bg-primary bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex gap-3">
                        <div class="flex-grow-1">
                            <h6 class="fs-18 mb-3">Shipping Address</h6>
                            <p class="mb-0">{{ $order->delivery->address->address }}</p>
                            <!-- <p class="mb-1">Tashkent, Uzbekistan</p> -->
                             @if (auth()->user()->role->name === 'customer')
                                <p class="mb-0">{{ $order->user->phone }}</p>
                            @endif
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-primary-subtle text-primary rounded fs-3">
                                <i class="ph-map-pin"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-9">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table align-middle table-nowrap mb-0">
                            <thead class="text-muted table-light">
                                <tr>
                                    <!-- <th scope="col">Product ID</th> -->
                                    <th scope="col">Product Name</th>
                                    <!-- <th scope="col">Amount</th> -->
                                    <th scope="col">Quantity</th>
                                    <th scope="col" class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $subtotal = $order->details->sum(function($detail) {
                                        return $detail->total_price;
                                    });
                                @endphp
                                @foreach ($order->details as $detail)
                                    <tr>
                                        <!-- <td>
                                            #{{ $detail->product_id }}
                                        </td> -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <img src="{{ $detail->product->mainImage ? asset('storage/' . $detail->product->mainImage->image_path) : asset('images/no_image.jpg') }}" alt=""
                                                        class="avatar-xs rounded-circle">
                                                </div>
                                                <div class="flex-grow-1">
                                                    {{ $detail->product_name }}{{ !empty($detail->product_variant_name) ? ' - ' . $detail->product_variant_name : '' }}

                                                    {{-- Show Modifiers if available --}}
                                                    @if (!empty($detail->modifiers))
                                                    <div class="mt-2">
                                                        <!-- <h6 class="fs-13 fw-semibold text-muted mb-1">Topping:</h6> -->
                                                        <ul class="mb-2 ps-3">
                                                            @foreach ($detail->modifiers as $modifier)
                                                                <li>
                                                                    {{ $modifier->modifier_name }}: {{ $modifier->modifier_option_name }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- <td>
                                            <span class="text-secondary">$658.00</span>
                                        </td> -->

                                        <td>{{ $detail->quantity }} PCS</td>
                                        <td class="text-end">IDR {{ number_format($detail->total_price, 0, ',', '.') }}</td>
                                    </tr><!-- end tr -->
                                @endforeach
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2" class="p-0">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>Sub Total:</td>
                                                    <td class="text-end">IDR {{ number_format($subtotal, 0, ',', '.') }}</td>
                                                </tr>
                                                <!-- <tr>
                                                    <td>Estimated Tax (12.5%):</td>
                                                    <td class="text-end">$200.00</td>
                                                </tr> -->
                                                <tr>
                                                    <td>Shipping Charge:</td>
                                                    <td class="text-end">IDR {{ number_format($order->delivery->shipping_cost, 0, ',', '.') }}</td>
                                                </tr>
                                                <!-- <tr>
                                                    <td>Discount (TONER42):</td>
                                                    <td class="text-end">$97.00</td>
                                                </tr> -->
                                                <tr class="border-top">
                                                    <th>Total (IDR) :</th>
                                                    <th class="text-end">IDR {{ number_format($order->order_price, 0, ',', '.') }}</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
        <div class="col-xxl-3">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center flex-wrap gap-2">
                            <h5 class="card-title flex-grow-1 mb-0">Logistics Details</h5>
                            @if (in_array($order->status, ['request picked up', 'picked up', 'delivered'])
                                && auth()->user()->role->name !== 'customer')
                                <div class="flex-shrink-0">
                                    <a href="{{ route('orders.print-label', $order->delivery->order_delivery_no) }}" target="_blank"
                                        class="btn btn-sm btn-primary print-label-btn">
                                        Print Label
                                    </a>
                                </div>

                                @if ($order->delivery && $order->delivery->is_send_to_other)
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('print.greeting', $order->id) }}"
                                            class="btn btn-sm btn-success"
                                            target="_blank"
                                        >
                                            Print Greeting
                                        </a>
                                    </div>
                                @endif
                            @endif
                            @if ($order->delivery->order_delivery_no)
                                <div class="flex-shrink-0">
                                    <a href="#track-order" class="btn btn-sm btn-warning">Track Order</a>
                                </div>
                             @endif
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <i class="bi bi-truck fs-1"></i>
                                <h5 class="fs-18">{{ $order->delivery->shipping_name }}</h5>
                                <p class="mb-0">ID: {{ $order->delivery->order_delivery_no }}</p>
                                 <p class="mb-0">Resi: {{ $order->delivery->receipt_number }}</p>
                                <p class="mb-0">Shipping Type : {{ $order->delivery->shipping_type }}</p>
                                <p class="mb-0">Status : {{ $order->delivery->status }}</p>
                            </div>
                        </div>
                    </div>
                    <!--end card-->
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title flex-grow-1 mb-0">Payment Detail:</h5>
                            @if ($order->status === 'waiting for payment')
                                <div class="flex-shrink-0">
                                    <a href="{{ $order->payment->invoice_url }}" class="btn btn-sm btn-success">Pay</a>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless align-middle description-table mb-0">
                                    <tr>
                                        <td>Transactions:</td>
                                        <td><span class="fw-medium">#{{ $order->payment->vendor_invoice_id }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Payment Method</td>
                                        <td><span class="fw-medium">{{ $order->payment->payment_method ?? '-' }}</span></td>
                                    </tr>
                                    <!-- <tr>
                                        <td>Card Number:</td>
                                        <td><span class="fw-medium">XXXX XXXX XXXX 3028</span></td>
                                    </tr>
                                    <tr>
                                        <td>Card Holder Name</td>
                                        <td><span class="fw-medium">Daniel Gonzalez</span></td>
                                    </tr> -->
                                    <tr>
                                        <td>Status</td>
                                        <td><span class="fw-medium">{{ $order->payment->status }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Total Amount</td>
                                        <td><span class="fw-medium">IDR {{ number_format($order->order_price, 0, ',', '.') }}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card" id="track-order">
                <div class="card-header d-flex  align-items-center gap-3">
                    <h5 class="card-title flex-grow-1 mb-0">Delivery Status</h5>
                    <!-- <div class="flex-shrink-0">
                        <button type="button" class="btn btn-soft-primary btn-sm mt-2 mt-sm-0"><i
                                class="ri-map-pin-line align-bottom me-1"></i> Change Address</button>
                        <button type="button" class="btn btn-soft-danger btn-sm mt-2 mt-sm-0"><i
                                class="mdi mdi-archive-remove-outline align-bottom me-1"></i> Cancel Order</button>
                    </div> -->
                </div>
                <div class="card-body">
                    <div class="row justify-content-between">
                        @if(!$trackingFounded)
                            <div class="col-12">
                                <div class="alert alert-warning mb-0">No tracking info available.</div>
                            </div>
                        @else
                            <div class="col-12">
                                <p><strong>Last Status:</strong> {{ $trackingData['last_status'] }}</p>

                                <ul class="timeline">
                                    @foreach($trackingData['history'] as $history)
                                        <li class="timeline-item mb-4">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span><strong>{{ $history['desc'] }}</strong></span>
                                                <span class="text-muted">{{ \Carbon\Carbon::parse($history['date'])->format('d M Y H:i') }}</span>
                                            </div>
                                            <div class="text-muted small">{{ $history['status'] }} ({{ $history['code'] }})</div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <!--end row-->
                </div>
            </div>
        </div>
        <!--end container-->
    </div>
@endsection
@section('scripts')
    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

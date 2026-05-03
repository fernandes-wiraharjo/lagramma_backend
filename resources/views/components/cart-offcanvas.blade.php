@php
    $isShowCartQty = false;

    $total = calculateTotalOnCart($cart);
@endphp

<!--cart -->
<div class="offcanvas offcanvas-end product-list" tabindex="-1" id="ecommerceCart"
    aria-labelledby="ecommerceCartLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="ecommerceCartLabel">List Cart
            @if ($isShowCartQty && $cartCount > 0)
                <span class="badge bg-danger align-middle ms-1 lg-cartitem-badge">{{ $cartCount }}</span>
            @endif
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body px-0">
        <div data-simplebar class="h-100">
            <ul class="list-group list-group-flush cartlist">
                <!-- <li class="list-group-item product">
                    <div class="d-flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-md lg-cart-avatar">
                                <div class="avatar-title bg-warning-subtle rounded-3">
                                    <img src="{{ URL::asset('build/images/products/img-4.png') }}" alt="" class="avatar-sm">
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <a href="#!">
                                <h5 class="fs-15">Borosil Paper Cup</h5>
                            </a>
                            <div class="d-flex mb-3 gap-2">
                                <div class="text-muted fw-medium mb-0">$<span class="product-price">24.00</span></div>
                                <div class="vr"></div>
                                <span class="text-success fw-medium">In Stock</span>
                            </div>
                            <div class="input-step">
                                <button type="button" class="minus">–</button>
                                <input type="number" class="product-quantity" value="2" min="0" max="100" readonly>
                                <button type="button" class="plus">+</button>
                            </div>
                        </div>
                        <div class="flex-shrink-0 d-flex flex-column justify-content-between align-items-end">
                            <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn" data-bs-toggle="modal" data-bs-target="#removeItemModal"><i class="ri-close-fill fs-16"></i></button>
                            <div class="fw-medium mb-0 fs-16">$<span class="product-line-price">48.00</span></div>
                        </div>
                    </div>
                </li> -->
                @foreach ($cart as $key => $item)
                    <li class="list-group-item product">
                        <div class="d-flex gap-4">
                            <div style="width: 200px;">
                                <div class="ratio ratio-1x1">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['product_name'] }}" class="w-100 h-100">
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex jutify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <a href="#!">
                                            <h5 class="fs-15">
                                                <span style="font-weight: 700; font-size: 1.25rem;">{{ $item['product_name'] }}</span><br />
                                                <span>{{ !empty($item['product_variant_name']) ? $item['product_variant_name'] : '' }}</span>
                                            </h5>
                                        </a>
                                        <div class="d-flex mb-3 gap-2">
                                            <div class="mb-0 fst-italic text-muted" style="font-size: 1rem; font-weight: 400; ">Rp {{ number_format($item['price'], 0, ',', '.') }}
                                            </div>
                                            <div class="vr"></div>
                                        </div>

                                        {{-- Show Modifiers if available --}}
                                        @if (!empty($item['modifiers']))
                                            <div class="mt-2">
                                                <!-- <h6 class="fs-13 fw-semibold text-muted mb-1">Topping:</h6> -->
                                                <ul class="mb-2 ps-3">
                                                    @foreach ($item['modifiers'] as $modifier)
                                                        <li>
                                                            {{ $modifier['modifier_name'] }}:
                                                            {{ $modifier['modifier_option_name'] }}
                                                            <span class="text-muted">(+Rp
                                                                {{ number_format($modifier['price'], 0, ',', '.') }})</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        {{-- For Hampers: Show item details --}}
                                        @if ($item['type'] === 'hampers' && !empty($item['items']))
                                            <div class="mt-2">
                                                <h6 class="fs-13 fw-semibold text-muted mb-1">Items:</h6>
                                                <ul class="mb-2 ps-3">
                                                    @foreach ($item['items'] as $subItem)
                                                        <li>
                                                            {{ $subItem['product_name'] }}{{ !empty($subItem['name']) ? ' - ' . $subItem['name'] : '' }}
                                                            x {{ $subItem['quantity'] }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="d-flex align-items-center">
                                            <div class="input-step-product">
                                                <button type="button" class="cart-header-minus"
                                                    data-key="{{ $key }}">–</button>
                                                <input type="number" class="product-quantity" data-key="{{ $key }}"
                                                    value="{{ $item['quantity'] }}" min="1" max="100" readonly>
                                                <button type="button" class="cart-header-plus"
                                                    data-key="{{ $key }}">+</button>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"
                                        data-key="{{ $key }}">
                                        <i class="ri-close-fill fs-16"></i>
                                    </button>
                                </div>

                                <div class="flex-shrink-0 d-flex justify-content-between align-items-end mt-3 py-2" style="border-top: 2px solid #D9D9D9; font-size: 1.125rem; font-weight: 300;">
                                    <div class="flex-grow-1 text-center">
                                        <span class="cart-item-count" data-key="{{ $key }}">{{ $item['quantity'] }}</span> Item(s):
                                    </div>
                                    <div class="mb-0">
                                        Rp <span class="product-line-price" data-key="{{ $key }}"
                                            data-price="{{ ($item['price'] ?? 0) + (!empty($item['modifiers']) ? array_sum(array_column($item['modifiers'], 'price')) : 0) }}">
                                            {{ number_format($item['total_price'], 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            @if (false)
            <div class="table-responsive mx-2 border-top border-top-dashed">
                <table class="table table-borderless mb-0 fs-14 fw-semibold">
                    <tbody>
                        <tr>
                            <td>Sub Total :</td>
                            <td class="text-end cart-lg-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <!-- <tr>
                            <td>Discount <span class="text-muted">(Toner15)</span>:</td>
                            <td class="text-end cart-discount">- $177.54</td>
                        </tr> -->
                        <tr>
                            <td>Shipping Charge :</td>
                            <td class="text-end cart-shipping">-</td>
                        </tr>
                        <!-- <tr>
                            <td>Estimated Tax (12.5%) : </td>
                            <td class="text-end cart-tax">$147.95</td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    <div class="offcanvas-footer border-top p-3 text-center border-2">
        <div class="d-flex justify-content-between align-items-center mb-3 lagramma-green-font">
            <h6 class="m-0" style="font-size: 1rem;">Total <span class="px-4">:</span></h6>
            <div class="px-2" style="font-size: 1rem;">
                <h6 class="m-0 lagramma-green-font" style="font-size: 1rem;">Rp {{ number_format($total, 0, ',', '.') }}</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-3 text-start text-danger fst-italic" style="font-size: 1rem;">
                (Not including shipping costs)
            </div>
        </div>
        <div class="row g-2">
            <div class="col-6">
                <a href="{{ config('app.frontend_url') . '/view-cart' }}" id="reset-layout">
                    <button class="rounded-5 lagramma-button-outline solid-border py-3 w-100" style="background-color: white;">View Cart</button>
                </a>
                <!-- <button type="button" class="btn btn-light w-100" id="reset-layout">View Cart</button> -->
            </div>
            <div class="col-6">
                <button type="button" id="lg-continue-to-co-btn" class="rounded-5 lagramma-button-solid py-3 w-100"
                    @if ($cartCount == 0) disabled @endif>
                    Checkout
                </button>
                <!-- <a href="#!" target="_blank" class="btn btn-info w-100">Continue to Checkout</a> -->
            </div>
        </div>
    </div>
</div>
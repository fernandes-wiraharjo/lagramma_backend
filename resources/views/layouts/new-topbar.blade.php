<nav class="navbar navbar-expand-lg ecommerce-navbar flex-column" id="navbar" style="border-bottom: 3px solid #0C3E3C;">
    <div class="container container-1440 w-100 pb-2 pt-2 lg-topbar-main-row">
        <div class="row w-100 g-0 align-items-center">
            <!-- Mobile Toggle -->
            <div class="col-auto d-lg-none pe-2">
                <button type="button" class="btn p-0 border-0 bg-transparent position-relative lg-topbar-action"
                    data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-list lg-topbar-icon"></i>
                </button>
            </div>

            <!-- Search - Left -->
            <div class="col-lg-4 d-none d-lg-flex align-items-center justify-content-start">
                <x-search-input-alt max-width="300px" />
            </div>

            <!-- Logo - Center -->
            <div class="col col-lg-4 d-flex align-items-center justify-content-start justify-content-center">
                <a class="navbar-brand me-0 text-start text-lg-center" href="/">
                    <div class="logo-dark">
                        <!-- LA GRAMMA -->
                        <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt=""
                            class="lg-topbar-logo lg-topbar-logo-dark" style="width: 360px;">
                    </div>
                    <div class="logo-light">
                        <!-- LA GRAMMA -->
                        <img src="{{ URL::asset('build/images/logo-light.png') }}" alt=""
                            class="lg-topbar-logo">
                    </div>
                </a>
            </div>

            <!-- Icons - Right -->
            <div class="col-auto col-lg-4 d-flex align-items-center justify-content-end lg-topbar-icons">
                {{-- <button type="button" class="btn btn-icon btn-topbar btn-ghost-dark rounded-circle text-muted"
                    data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="bx bx-search fs-22"></i>
                </button> --}}
                <div class="topbar-head-dropdown ms-1 header-item d-none d-sm-flex">
                    @php
                        $cart = session('shopping_cart', []);
                        $cartCount = count($cart);
                        $subtotal = collect($cart)->sum('total_price');
                    @endphp
                    <button type="button" class="btn p-0 border-0 bg-transparent position-relative lg-topbar-action"
                        data-bs-toggle="offcanvas" data-bs-target="#ecommerceCart" aria-controls="ecommerceCart">
                        <i class="bi bi-cart3 lg-topbar-icon"></i>
                        @if ($cartCount > 0)
                            <span
                                class="position-absolute topbar-badge lg-cartitem-badge fs-10 translate-middle badge rounded-pill bg-danger">{{ $cartCount }}</span>
                        @endif
                    </button>
                </div>

                @if (false)
                    <div class="dropdown topbar-head-dropdown ms-2 header-item dropdown-hover-end">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-dark rounded-circle text-muted"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bi bi-sun align-middle fs-20"></i>
                        </button>
                        <div class="dropdown-menu p-2 dropdown-menu-end" id="light-dark-mode">
                            <a href="#!" class="dropdown-item" data-mode="light"><i
                                    class="bi bi-sun align-middle me-2"></i> Default (light mode)</a>
                            <a href="#!" class="dropdown-item" data-mode="dark"><i
                                    class="bi bi-moon align-middle me-2"></i> Dark</a>
                            <a href="#!" class="dropdown-item" data-mode="auto"><i
                                    class="bi bi-moon-stars align-middle me-2"></i> Auto (system default)</a>
                        </div>
                    </div>
                @endif

                <div class="dropdown header-item dropdown-hover-end">
                    <button type="button" class="btn p-0 border-0 bg-transparent lg-topbar-action"
                        id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="bi bi-person lg-topbar-icon"></i>
                    </button>
                    {{-- <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                        id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="ph-user-circle fs-22"></i>
                    </button> --}}
                    <div class="dropdown-menu dropdown-menu-end" id="userDropdownContent">
                        <!-- item-->
                        {{--
                        <h6 class="dropdown-header">Welcome {{ @Auth::user()->name }}!</h6>
                        <a class="dropdown-item" href="account"><i class="bi bi-person-circle text-muted fs-15 align-middle me-1"></i> <span class="align-middle">Profile</span></a>
                        <a class="dropdown-item" href="order-history"><i class="bi bi-cart4 text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Order History</span></a>
                        <a class="dropdown-item" href="track-order"><i class="bi bi-truck text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Track Orders</span></a>
                        <a class="dropdown-item" href="javascript:void(0)"><i class="bi bi-speedometer2 text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Dashboard</span></a>
                        <a class="dropdown-item" href="ecommerce-faq"><i class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Help</span></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="account"><i class="bi bi-coin text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Balance : <b>$8451.36</b></span></a>
                        <a class="dropdown-item" href="account">
                            <span class="badge bg-success-subtle text-success mt-1 float-end">New</span>
                            <i class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i>
                            <span class="align-middle">Settings</span>
                        </a>
                        <a class="dropdown-item" href="{{ config('app.backend_url') }}/logout"><i class="bi bi-box-arrow-right text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">{{ __('t-logout') }}</span></a>
                        --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Search Section --}}
    <div class="w-100 d-block d-lg-none lg-navbar-menus lg-navbar-menus-mobile">
        <div class="container container-1440 h-100 position-relative d-flex justify-content-center align-items-center">
            <x-search-input-alt max-width="560px" />
        </div>
    </div>

    {{-- Navbar Menus --}}
    <div class="w-100 d-none d-lg-block lg-navbar-menus lg-navbar-menus-desktop">
        <div class="container container-1440 h-100 position-relative">
            <div class="d-flex h-100 justify-content-center align-items-center">
                <a href="/catalogue"
                    class="text-decoration-none text-black px-3 lg-navbar-menu-link {{ request()->is('catalogue') ? 'lg-navbar-menu-link-active' : '' }}">
                    SHOP NOW
                </a>
                <a href="#!"
                    class="text-decoration-none text-black px-3 lg-navbar-menu-link {{ request()->is('story-of-love') ? 'lg-navbar-menu-link-active' : '' }}">
                    A STORY OF
                    LOVE</a>
                <a href="#!"
                    class="text-decoration-none text-black px-3 lg-navbar-menu-link {{ request()->is('location') ? 'lg-navbar-menu-link-active' : '' }}">
                    LOCATION
                </a>
            </div>

            {{-- WhatsApp Order Now --}}
            <div class="position-absolute top-50 end-0 translate-middle-y pe-3">
                <a href="#!"
                    class="text-decoration-none text-black lg-navbar-menu-link {{ request()->is('order-now') ? 'lg-navbar-menu-link-active' : '' }}">
                    Order Now
                </a>
            </div>
        </div>
    </div>

    <!-- Navbar Menu - Mobile -->
    <div class="collapse navbar-collapse d-lg-none" id="navbarSupportedContent">
        <ul class="navbar-nav mb-0" id="navigation-menu">
            <li class="nav-item d-block d-lg-none">
                <a class="d-block p-5 h-auto text-center" href="/">
                    <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="25"
                        class="card-logo-dark mx-auto">
                    <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="25"
                        class="card-logo-light mx-auto">
                </a>
            </li>
            <li class="nav-item d-lg-none">
                <a class="nav-link" href="/catalogue">SHOP NOW</a>
            </li>
            <li class="nav-item d-lg-none">
                <a class="nav-link" href="#!">A STORY OF LOVE</a>
            </li>
            <li class="nav-item d-lg-none">
                <a class="nav-link" href="#!">LOCATION</a>
            </li>
        </ul>
    </div>

    <div class="bg-overlay navbar-overlay" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent.show">
    </div>
</nav>

<!--cart -->
<div class="offcanvas offcanvas-end product-list" tabindex="-1" id="ecommerceCart"
    aria-labelledby="ecommerceCartLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="ecommerceCartLabel">My Cart
            @if ($cartCount > 0)
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
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-md lg-cart-avatar">
                                    <div class="avatar-title bg-warning-subtle rounded-3">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['product_name'] }}"
                                            class="avatar-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <a href="#!">
                                    <h5 class="fs-15">
                                        {{ $item['product_name'] }}{{ !empty($item['product_variant_name']) ? ' - ' . $item['product_variant_name'] : '' }}
                                    </h5>
                                </a>
                                <div class="d-flex mb-3 gap-2">
                                    <div class="text-muted fw-medium mb-0">IDR<span
                                            class="product-price">{{ number_format($item['price'], 0, ',', '.') }}</span>
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
                                                    <span class="text-muted">(+IDR
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

                                <div class="input-step">
                                    <button type="button" class="cart-header-minus"
                                        data-key="{{ $key }}">–</button>
                                    <input type="number" class="product-quantity" data-key="{{ $key }}"
                                        value="{{ $item['quantity'] }}" min="1" max="100" readonly>
                                    <button type="button" class="cart-header-plus"
                                        data-key="{{ $key }}">+</button>
                                </div>
                            </div>

                            <div class="flex-shrink-0 d-flex flex-column justify-content-between align-items-end">
                                <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"
                                    data-key="{{ $key }}">
                                    <i class="ri-close-fill fs-16"></i>
                                </button>
                                <div class="fw-medium mb-0 fs-16">
                                    IDR<span class="product-line-price" data-key="{{ $key }}"
                                        data-price="{{ ($item['price'] ?? 0) + (!empty($item['modifiers']) ? array_sum(array_column($item['modifiers'], 'price')) : 0) }}">
                                        {{ number_format($item['total_price'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="table-responsive mx-2 border-top border-top-dashed">
                <table class="table table-borderless mb-0 fs-14 fw-semibold">
                    <tbody>
                        <tr>
                            <td>Sub Total :</td>
                            <td class="text-end cart-lg-subtotal">IDR{{ number_format($subtotal, 0, ',', '.') }}</td>
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
        </div>
    </div>
    <div class="offcanvas-footer border-top p-3 text-center">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="m-0 fs-16 text-muted">Total:</h6>
            <div class="px-2">
                <h6 class="m-0 fs-16 cart-total">-</h6>
            </div>
        </div>
        <div class="row g-2">
            <div class="col-6">
                <a href="/" class="btn btn-light w-100" id="reset-layout">View Cart</a>
                <!-- <button type="button" class="btn btn-light w-100" id="reset-layout">View Cart</button> -->
            </div>
            <div class="col-6">
                <button type="button" id="lg-continue-to-co-btn" class="btn btn-info w-100"
                    @if ($cartCount == 0) disabled @endif>
                    Continue to Checkout
                </button>
                <!-- <a href="#!" target="_blank" class="btn btn-info w-100">Continue to Checkout</a> -->
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded">
            <div class="modal-header p-3">
                <div class="position-relative w-100">
                    <input type="text" class="form-control form-control-lg border-2"
                        placeholder="Search for Toner..." autocomplete="off" id="search-options" value="">
                    <span class="bi bi-search search-widget-icon fs-17"></span>
                    <a href="javascript:void(0);"
                        class="search-widget-icon fs-14 link-secondary text-decoration-underline search-widget-icon-close d-none"
                        id="search-close-options">Clear</a>
                </div>
            </div>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 overflow-hidden" id="search-dropdown">

                <div class="dropdown-head rounded-top">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0 fs-14 text-muted fw-semibold"> RECENT SEARCHES </h6>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown-item bg-transparent text-wrap">
                        <a href="index" class="btn btn-soft-secondary btn-sm btn-rounded">how to setup <i
                                class="mdi mdi-magnify ms-1 align-middle"></i></a>
                        <a href="index" class="btn btn-soft-secondary btn-sm btn-rounded">buttons <i
                                class="mdi mdi-magnify ms-1 align-middle"></i></a>
                    </div>
                </div>

                <div data-simplebar class="pe-2 ps-3 my-3 lg-search-dropdown-scroll">
                    <div class="list-group list-group-flush border-dashed">
                        <div class="notification-group-list">
                            <h5 class="text-overflow text-muted fs-13 mb-2 mt-3 text-uppercase notification-title">Apps
                                Pages</h5>
                            <a href="javascript:void(0);" class="list-group-item dropdown-item notify-item"><i
                                    class="bi bi-speedometer2 me-2"></i> <span>Analytics Dashboard</span></a>
                            <a href="javascript:void(0);" class="list-group-item dropdown-item notify-item"><i
                                    class="bi bi-filetype-psd me-2"></i> <span>Toner.psd</span></a>
                            <a href="javascript:void(0);" class="list-group-item dropdown-item notify-item"><i
                                    class="bi bi-ticket-detailed me-2"></i> <span>Support Tickets</span></a>
                            <a href="javascript:void(0);" class="list-group-item dropdown-item notify-item"><i
                                    class="bi bi-file-earmark-zip me-2"></i> <span>Toner.zip</span></a>
                        </div>

                        <div class="notification-group-list">
                            <h5 class="text-overflow text-muted fs-13 mb-2 mt-3 text-uppercase notification-title">
                                Links</h5>
                            <a href="javascript:void(0);" class="list-group-item dropdown-item notify-item"><i
                                    class="bi bi-link-45deg me-2 align-middle"></i>
                                <span>www.themesbrand.com</span></a>
                        </div>

                        <div class="notification-group-list">
                            <h5 class="text-overflow text-muted fs-13 mb-2 mt-3 text-uppercase notification-title">
                                People</h5>
                            <a href="javascript:void(0);" class="list-group-item dropdown-item notify-item">
                                <div class="d-flex align-items-center">
                                    <img src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" alt=""
                                        class="avatar-xs rounded-circle flex-shrink-0 me-2">
                                    <div>
                                        <h6 class="mb-0">Ayaan Bowen</h6>
                                        <span class="fs-12 text-muted">React Developer</span>
                                    </div>
                                </div>
                            </a>
                            <a href="javascript:void(0);" class="list-group-item dropdown-item notify-item">
                                <div class="d-flex align-items-center">
                                    <img src="{{ URL::asset('build/images/users/avatar-7.jpg') }}" alt=""
                                        class="avatar-xs rounded-circle flex-shrink-0 me-2">
                                    <div>
                                        <h6 class="mb-0">Alexander Kristi</h6>
                                        <span class="fs-12 text-muted">React Developer</span>
                                    </div>
                                </div>
                            </a>
                            <a href="javascript:void(0);" class="list-group-item dropdown-item notify-item">
                                <div class="d-flex align-items-center">
                                    <img src="{{ URL::asset('build/images/users/avatar-5.jpg') }}" alt=""
                                        class="avatar-xs rounded-circle flex-shrink-0 me-2">
                                    <div>
                                        <h6 class="mb-0">Alan Carla</h6>
                                        <span class="fs-12 text-muted">React Developer</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- removeItemModal -->
<div id="removeItemModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="close-modal"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#f7b84b,secondary:#f06548" class="lg-remove-item-icon"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this product ?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="remove-product">Yes, Delete It!</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="subscribeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-body p-0 bg-info-subtle rounded">
                <div class="row g-0 align-items-center">
                    <div class="col-lg-6">
                        <div class="p-4 h-100">
                            <span class="badge bg-info-subtle text-info fs-13">GET 10% SALE OFF</span>
                            <h2 class="display-6 mt-2 mb-3">Subscribe & Get <b>50% Special</b> Discount On Email</h2>
                            <p class="mb-4 pb-lg-2 fs-16">Join our newsletter to receive the latest updates and
                                promotion</p>
                            <form action="#!">
                                <div class="position-relative ecommerce-subscript">
                                    <input type="email" class="form-control rounded-pill border-0"
                                        placeholder="Enter your email">
                                    <button type="submit"
                                        class="btn btn-info btn-hover rounded-pill">Subscript</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="p-4 pb-0">
                            <img src="{{ URL::asset('build/images/subscribe.png') }}" alt=""
                                class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end modal -->

{{-- <a href="../backend/index" class="btn btn-warning position-fixed bottom-0 start-0 m-5 z-3 btn-hover d-none d-lg-block"><i class="bi bi-database align-middle me-1"></i> Backend</a> --}}

{{-- <!--start back-to-top-->
<button onclick="topFunction()" class="btn btn-info btn-icon lg-back-to-top" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>
<!--end back-to-top--> --}}

<!-- <a class="btn btn-danger shadow-lg chat-button rounded-bottom-0 d-none d-lg-block" data-bs-toggle="collapse" href="#chatBot" aria-expanded="false" aria-controls="chatBot">Online Chat</a> -->
<div class="collapse chat-box" id="chatBot">
    <div class="card shadow-lg border-0 rounded-bottom-0 mb-0">
        <div class="card-header bg-success d-flex align-items-center border-0">
            <h5 class="text-white fs-16 fw-medium flex-grow-1 mb-0">Hi, Raquel Murillo 👋</h5>
            <button type="button" class="btn-close btn-close-white flex-shrink-0" onclick="chatBot()"
                data-bs-dismiss="collapse" aria-label="Close"></button>
        </div>
        <div class="card-body p-0">
            <div id="users-chat-widget">
                <div class="chat-conversation p-3 lg-chat-conversation" id="chat-conversation" data-simplebar>
                    <ul class="list-unstyled chat-conversation-list chat-sm" id="users-conversation">
                        <li class="chat-list left">
                            <div class="conversation-list">
                                <div class="chat-avatar">
                                    <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="">
                                </div>
                                <div class="user-chat-content">
                                    <div class="ctext-wrap">
                                        <div class="ctext-wrap-content">
                                            <p class="mb-0 ctext-content">Welcome to Themesbrand. We are here to help
                                                you. You can also directly email us at Support@themesbrand.com to
                                                schedule a meeting with our Technology Consultant.</p>
                                        </div>
                                        <div class="dropdown align-self-start message-box-drop">
                                            <a class="dropdown-toggle" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ri-more-2-fill"></i>
                                            </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#"><i
                                                        class="ri-reply-line me-2 text-muted align-bottom"></i>Reply</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>
                                                <a class="dropdown-item delete-item" href="#"><i
                                                        class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="conversation-name"><small class="text-muted time">09:07 am</small>
                                        <span class="text-success check-message-icon"><i
                                                class="ri-check-double-line align-bottom"></i></span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- chat-list -->

                        <li class="chat-list right">
                            <div class="conversation-list">
                                <div class="user-chat-content">
                                    <div class="ctext-wrap">
                                        <div class="ctext-wrap-content">
                                            <p class="mb-0 ctext-content">Good morning, How are you? What about our
                                                next meeting?</p>
                                        </div>
                                        <div class="dropdown align-self-start message-box-drop">
                                            <a class="dropdown-toggle" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ri-more-2-fill"></i>
                                            </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#"><i
                                                        class="ri-reply-line me-2 text-muted align-bottom"></i>Reply</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>
                                                <a class="dropdown-item delete-item" href="#"><i
                                                        class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="conversation-name"><small class="text-muted time">09:08 am</small>
                                        <span class="text-success check-message-icon"><i
                                                class="ri-check-double-line align-bottom"></i></span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- chat-list -->

                        <li class="chat-list left">
                            <div class="conversation-list">
                                <div class="chat-avatar">
                                    <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="">
                                </div>
                                <div class="user-chat-content">
                                    <div class="ctext-wrap">
                                        <div class="ctext-wrap-content">
                                            <p class="mb-0 ctext-content">Yeah everything is fine. Our next meeting
                                                tomorrow at 10.00 AM</p>
                                        </div>
                                        <div class="dropdown align-self-start message-box-drop">
                                            <a class="dropdown-toggle" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ri-more-2-fill"></i>
                                            </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#"><i
                                                        class="ri-reply-line me-2 text-muted align-bottom"></i>Reply</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>
                                                <a class="dropdown-item delete-item" href="#"><i
                                                        class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="conversation-name"><small class="text-muted time">09:10 am</small>
                                        <span class="text-success check-message-icon"><i
                                                class="ri-check-double-line align-bottom"></i></span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- chat-list -->

                    </ul>
                </div>
            </div>
            <div class="border-top border-top-dashed">
                <div class="row g-2 mt-2 mx-3 mb-3">
                    <div class="col">
                        <div class="position-relative">
                            <input type="text" class="form-control border-light bg-light"
                                placeholder="Enter Message...">
                        </div>
                    </div><!-- end col -->
                    <div class="col-auto">
                        <button type="submit" class="btn btn-info"><i class="mdi mdi-send"></i></button>
                    </div><!-- end col -->
                </div><!-- end row -->
            </div>
        </div>
    </div>
</div>

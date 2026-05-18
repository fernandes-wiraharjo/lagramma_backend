<!-- Navbar Menu - Mobile -->
<div class="collapse navbar-collapse d-lg-none" id="navbarSupportedContent">
    {{-- Mobile Header --}}
    <div class="container container-1440 w-100 pb-2 pt-2 lg-topbar-main-row lg-mobile-header">
        <div class="row w-100 g-0 align-items-center">
            <!-- Close Toggle -->
            <div class="col-auto pe-2">
                <button type="button" class="btn p-0 border-0 bg-transparent position-relative lg-topbar-action"
                    data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-x-lg lg-topbar-icon"></i>
                </button>
                <button type="button" class="btn p-0 border-0 bg-transparent lg-topbar-action"
                    id="mobileMenuSearchToggle">
                    <i class="bi bi-search lg-topbar-icon"></i>
                </button>
            </div>

            <!-- Logo - Center -->
            <div class="col d-flex align-items-center justify-content-center">
                <a class="navbar-brand me-0 text-center" href="/">
                    <div class="logo-dark">
                        <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt=""
                            class="lg-topbar-logo lg-topbar-logo-dark">
                    </div>
                    <div class="logo-light">
                        <img src="{{ URL::asset('build/images/logo-light.png') }}" alt=""
                            class="lg-topbar-logo">
                    </div>
                </a>
            </div>

            <!-- Icons - Right -->
            <div class="col-auto d-flex align-items-center justify-content-end lg-topbar-icons">
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

                <div class="header-item">
                    <button type="button" class="btn p-0 border-0 bg-transparent lg-topbar-action"
                        data-bs-toggle="offcanvas" data-bs-target="#userSidebar" aria-controls="userSidebar">
                        <i class="bi bi-person lg-topbar-icon"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <ul class="navbar-nav mb-0 pt-2" id="navigation-menu">
        <li class="nav-item d-lg-none py-2">
            <a style="{{ request()->is('catalogue') ? 'font-weight: 500;' : 'font-weight: 400;' }}" class="nav-link text-black fs-18 {{ request()->is('catalogue') ? 'menu-underline lg-navbar-menu-link' : '' }}" href="{{ config('app.frontend_url') }}/catalogue">
                SHOP NOW
            </a>
        </li>
        <li class="nav-item d-lg-none py-2">
            <a style="{{ request()->is('a-story-of-love') ? 'font-weight: 500;' : 'font-weight: 400;' }}" class="nav-link text-black fs-18 {{ request()->is('a-story-of-love') ? 'menu-underline lg-navbar-menu-link' : '' }}" href="{{ config('app.frontend_url') }}/a-story-of-love">A STORY OF LOVE</a>
        </li>
        <li class="nav-item d-lg-none py-2">
            <a style="{{ request()->is('locations') ? 'font-weight: 500;' : 'font-weight: 400;' }}" class="nav-link text-black fs-18 {{ request()->is('locations') ? 'menu-underline lg-navbar-menu-link' : '' }}" href="{{ config('app.frontend_url') }}/locations">LOCATION</a>
        </li>
        <li class="nav-item d-lg-none py-2">
            <a target="_blank" href="https://wa.me/6282213706036?text=Hello%20Lagramma!%20Saya%20ingin%20bertanya%20terkait"
                class="nav-link d-flex align-items-center gap-3 text-decoration-none text-black lg-navbar-menu-link {{ request()->is('order-now') ? 'lg-navbar-menu-link-active' : '' }}">
                <span class="fs-18" style="font-weight: 400;">ORDER NOW</span>
                <img src="{{ URL::asset('build/images/icons/whatsapp-black.svg') }}" width="20"/>
            </a>
        </li>
        {{-- Mobile Sidebar Cart --}}
        <li class="nav-item d-lg-none py-2">
            <a class="nav-link text-black fs-18 {{ request()->is('locations') ? 'menu-underline lg-navbar-menu-link' : '' }}" style="font-weight: 400;" href="#" data-bs-toggle="offcanvas" data-bs-target="#ecommerceCart" aria-controls="ecommerceCart">
                <div class="d-flex align-items-center gap-2">
                    <span>CART</span>
                    @php
                        $cart = session('shopping_cart', []);
                        $cartCount = count($cart);
                        $subtotal = collect($cart)->sum('total_price');
                    @endphp
                    <span
                        class="ms-2 topbar-badge lg-cartitem-badge fs-10 translate-middle badge rounded-circle bg-danger">{{ $cartCount }}</span>
                </div>
            </a>
        </li>
    </ul>
</div>

<div class="bg-overlay navbar-overlay" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent.show">
</div>


@push('extra_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Mobile Menu Script Loaded");

        document.getElementById('mobileMenuSearchToggle')?.addEventListener('click', function() {
            const navbarCollapse = document.getElementById('navbarSupportedContent');
            const searchInput = document.getElementById('search-mobile');

            if (navbarCollapse) {
                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            }

            if (searchInput) {
                searchInput.focus();
            }
        });
    });
</script>
@endpush

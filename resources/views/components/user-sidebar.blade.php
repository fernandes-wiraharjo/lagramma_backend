 <!-- Offcanvas Sidebar -->
 <div class="offcanvas offcanvas-end border-0 ecommerce-user-sidebar" tabindex="-1" id="userSidebar"
     aria-labelledby="userSidebarLabel">
     <div class="pb-2 pt-2 px-2 lg-topbar-header">
         <div class="row w-100 g-0 align-items-center">
             <!-- Close Toggle -->
             <div class="col-auto pe-2">
                 <button type="button" class="btn p-0 border-0 bg-transparent position-relative lg-topbar-action"
                     id="userSidebarMenuToggle" aria-label="Toggle navigation">
                     <i class="bi bi-list lg-topbar-icon"></i>
                 </button>
                 <button type="button" class="btn p-0 border-0 bg-transparent lg-topbar-action"
                     id="userSidebarSearchToggle">
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
                 <div class="header-item d-none d-sm-flex">
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
                         data-bs-dismiss="offcanvas" aria-label="Close">
                         <i class="bi bi-x-lg lg-topbar-icon"></i>
                     </button>
                 </div>
             </div>
         </div>
     </div>

     <div class="offcanvas-body">
         <ul class="navbar-nav mb-0 pt-2" id="user-sidebar-menu">
             <li class="nav-item nav-link text-black fw-semibold fs-18 py-2">
                 Welcome
             </li>
             <li class="nav-item nav-link text-black fw-normal fs-18 py-3">
                 Enjoy a sweeter experience with La Gramma
             </li>
             <li class="nav-item py-2">
                 <a
                     class="nav-link text-black fs-18 fw-light {{ request()->is('/login') ? 'menu-underline lg-navbar-menu-link' : '' }}"
                     href="/login">Login</a>
             </li>
             <li class="nav-item py-2">
                 <a
                     class="nav-link text-black fs-18 fw-light {{ request()->is('/register') ? 'menu-underline lg-navbar-menu-link' : '' }}"
                     href="/register">Sign Up</a>
             </li>
         </ul>
     </div>
 </div>

@push('extra_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('userSidebarMenuToggle')?.addEventListener('click', function() {
            const userSidebar = document.getElementById('userSidebar');
            const navbarCollapse = document.getElementById('navbarSupportedContent');

            if (userSidebar) {
                const bsOffcanvas = bootstrap.Offcanvas.getInstance(userSidebar);
                if (bsOffcanvas) {
                    userSidebar.addEventListener('hidden.bs.offcanvas', function onHidden() {
                        userSidebar.removeEventListener('hidden.bs.offcanvas', onHidden);
                        if (navbarCollapse) {
                            const bsCollapse = bootstrap.Collapse.getOrCreateInstance(navbarCollapse);
                            bsCollapse.show();
                        }
                    });
                    bsOffcanvas.hide();
                }
            }
        });

        document.getElementById('userSidebarSearchToggle')?.addEventListener('click', function() {
            const userSidebar = document.getElementById('userSidebar');
            const searchInput = document.getElementById('search-mobile');

            if (userSidebar) {
                const bsOffcanvas = bootstrap.Offcanvas.getInstance(userSidebar);
                if (bsOffcanvas) {
                    userSidebar.addEventListener('hidden.bs.offcanvas', function onHidden() {
                        userSidebar.removeEventListener('hidden.bs.offcanvas', onHidden);
                        if (searchInput) {
                            searchInput.focus();
                        }
                    });
                    bsOffcanvas.hide();
                }
            }
        });
    });
</script>
@endpush

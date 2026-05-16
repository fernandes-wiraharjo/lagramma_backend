<!doctype html>
<html lang="en" data-bs-theme="light" data-footer="dark">

<head>
    <meta charset="utf-8">
    <title>La Gramma | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="La gramma backoffice" name="description">
    <meta content="Fernandes Wiraharjo" name="author">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}">

    <!-- head css -->
    @include('layouts.head-css')
</head>

<body>
    @include('layouts.new-topbar')


    @yield('content')

    <!-- footer -->
    @include('layouts.footer')

    <!--script-->
    @include('layouts.vendor-scripts')


    <!-- layout master scripts -->
    <script>
        const backendUrl = @json(config('app.backend_url'));
        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
        let userRole = '';
        const footerOrderLinks = document.querySelectorAll('[data-footer-view-my-order]');
        const userDropdownContent = document.getElementById('userDropdownContent');

        //get user session
        fetch(`${backendUrl}/api/user`, {
            credentials: 'include',
            headers: {
                'Accept': 'application/json'
            }
        }).then(res => {
            if (res.ok) {
                return res.json();
            } else if (res.status === 401) {
                console.log('User is not logged in');
                return null;
            } else {
                console.error('Unexpected error status:', res.status);
                return null;
            }
        }).then(user => {
            if (user) {
                // console.log('User is logged in:', user);
                userRole = user.role_name;
                // alert(userRole);

                // Authenticated user
                userDropdownContent.classList.remove('lg-guest-profile-dropdown');
                userDropdownContent.innerHTML = `
                    <h6 class="dropdown-header">Welcome ${user.name}!</h6>
                    <a class="dropdown-item" href="${backendUrl}/my-account"><i class="bi bi-person-circle text-muted fs-15 me-1"></i> Profile</a>
                    <a class="dropdown-item" href="${backendUrl}/orders" target="_blank"><i class="bi bi-cart4 text-muted fs-15 me-1"></i> Order History</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="${backendUrl}/account-setting"><i class="mdi mdi-cog-outline text-muted fs-15 me-1"></i> Settings</a>
                    <a href="#" onclick="logoutStore()" class="dropdown-item">
                        <i class="bi bi-box-arrow-right text-muted fs-15 me-1"></i> Logout
                    </a>
                `;
                footerOrderLinks.forEach((el) => el.classList.remove('d-none'));
            } else {
                console.log('Guest mode');

                // Guest mode - Profile dropdown
                userDropdownContent.classList.add('lg-guest-profile-dropdown');
                userDropdownContent.innerHTML = `
                    <h6 class="dropdown-header">Welcome</h6>
                    <p class="dropdown-item-text">Enjoy a sweeter experience<br>with La Gramma.</p>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="${backendUrl}/login">Login</a>
                    <a class="dropdown-item" href="${backendUrl}/register">Sign Up</a>
                `;
                footerOrderLinks.forEach((el) => el.classList.add('d-none'));

            }
        }).catch(err => {
            console.error('Error fetching user:', err);
        });


        //get csrf token
        async function getCSRFToken() {
            const response = await fetch(`${backendUrl}/sanctum/csrf-cookie`, {
                method: 'GET',
                credentials: 'include', // Include credentials for cookie sharing
            });

            if (!response.ok) {
                throw new Error('Failed to set CSRF cookie');
            }

            // Extract XSRF-TOKEN from document.cookie
            const getCookie = (name) => {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
            };

            return decodeURIComponent(getCookie('XSRF-TOKEN'));
        }

        //logout store function
        async function logoutStore() {
            const xsrfToken = await getCSRFToken();

            fetch(`${backendUrl}/api/logout-store`, {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-XSRF-TOKEN': xsrfToken,
                    }
                })
                .then(res => {
                    if (res.ok) {
                        alert('Logged out successfully');

                        // Reload or refresh dropdown in guest mode
                        userDropdownContent.classList.add('lg-guest-profile-dropdown');
                        userDropdownContent.innerHTML = `
                        <h6 class="dropdown-header">Welcome, Guest!</h6>
                        <p class="dropdown-item-text">Please log in to access your account.</p>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="${backendUrl}/login">
                            <i class="bi bi-box-arrow-in-right text-muted fs-15 me-1"></i> Login
                        </a>
                    `;
                        userRole = '';

                        location.reload();
                    } else {
                        console.error('Logout failed:', res.status);
                    }
                })
                .catch(err => console.error('Error during logout:', err));
        }

        function numberFormat(value) {
            return value.toLocaleString('id-ID');
        }

        // -- CART SECTION
        function updateCartSubTotal(subtotal) {
            const subTotalElements = document.querySelectorAll('.cart-lg-subtotal');
            subTotalElements.forEach(input => {
                input.textContent = 'Rp ' + numberFormat(subtotal);
            });
        }

        function updateCartTotal(total) {
            const totalElements = document.querySelectorAll('.cart-total');
            totalElements.forEach(input => {
                input.textContent = 'Rp ' + numberFormat(total);
            });
        }

        // Handle plus button
        document.querySelectorAll('.cart-header-plus').forEach(button => {
            button.addEventListener('click', function() {
                const key = this.dataset.key;
                const qtyInputs = document.querySelectorAll(`.product-quantity[data-key="${key}"]`);
                let qty = parseInt(qtyInputs[0].value);

                if (qty < 100) {
                    qty++;
                    if (qty > 100) return;
                    // Update all inputs with the same data-key
                    qtyInputs.forEach(input => {
                        input.value = qty;
                    });
                    updateCartQuantity(key, qty, 1);
                }
            });
        });

        // Handle minus button
        document.querySelectorAll('.cart-header-minus').forEach(button => {
            button.addEventListener('click', function() {
                const key = this.dataset.key;
                const qtyInputs = document.querySelectorAll(`.product-quantity[data-key="${key}"]`);
                let qty = parseInt(qtyInputs[0].value);

                if (qty > 1) {
                    qty--;
                    if (qty < 1) return;
                    // Update all inputs with the same data-key
                    qtyInputs.forEach(input => {
                        input.value = qty;
                    });
                    updateCartQuantity(key, qty, -1);
                }
            });
        });

        // Handle remove item button
        document.querySelectorAll('.remove-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                const key = this.dataset.key;
                removeCartItem(key);
            });
        });

        document.querySelectorAll('.clear-cart-btn').forEach(button => {
            button.addEventListener('click', function() {
                clearCartItem();
            });
        });

        function updateCartQuantity(key, qty, change) {
            const linePriceSpans = document.querySelectorAll(`.product-line-price[data-key="${key}"]`);
            const pricePerItem = parseInt(linePriceSpans[0].dataset
                .price); // store item base price + modifiers price (if selected) in data-price

            fetch(`/cart/update-quantity`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        key: key,
                        change: change
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        linePriceSpans.forEach(input => {
                            input.textContent = numberFormat(pricePerItem * qty);
                        });
                        // linePriceSpan.textContent = numberFormat(pricePerItem * qty);
                        updateCartSubTotal(data.subtotal);
                        updateCartTotal(data.subtotal); // Total equals subtotal for now (shipping not calculated)
                    } else {
                        alert(data.message || 'Something went wrong. Page will be reloaded for data consistency');
                        location.reload();
                    }
                });
        }

        function removeCartItem(key) {
            fetch(`/cart/remove`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        key: key
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Product successfully removed from cart!');
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to remove item.');
                    }
                });
        }

        function clearCartItem(key) {
            fetch(`/cart/remove-all`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        key: key
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('All product successfully removed from cart!');
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to clear cart.');
                    }
                });
        }
        // END OF CART SECTION --

        // Login modal functionality
        const loginRequiredModal = new bootstrap.Modal(document.getElementById('loginRequiredModal'));

        // Initialize login button event listener after DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            const loginModalBtn = document.getElementById('loginModalBtn');
            if (loginModalBtn) {
                loginModalBtn.addEventListener('click', function() {
                    const currentUrl = window.location.href;
                    const backendLoginUrl =
                    `${backendUrl}/login?redirect=${encodeURIComponent(currentUrl)}`;
                    window.location.href = backendLoginUrl;
                });
            }
        });

        document.getElementById('lg-continue-to-co-btn').addEventListener('click', function() {
            if (!isLoggedIn) {
                loginRequiredModal.show();
                return;
            }

            fetch('/cart/validate-stock', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect_url;
                    } else {
                        alert(data.message); // or use SweetAlert/toast
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error validating stock.');
                });
        });
    </script>

    <!-- Login Required Modal -->
    <div class="modal fade round-5" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-body text-center p-4">
                    <p class="mb-4 fs-3">Silahkan login terlebih dahulu untuk melanjutkan ke halaman checkout.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" id="loginModalBtn"
                            class="lagramma-button-solid rounded-4 py-3 px-4 w-100">Login</button>
                        <button type="button" class="lagramma-button-outline solid-border rounded-4 py-3 px-4 w-100"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @stack('extra_scripts')
</body>

</html>

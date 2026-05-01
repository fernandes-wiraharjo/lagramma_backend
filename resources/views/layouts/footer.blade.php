<section class="section footer-landing pb-0 d-none d-lg-block">
    <div class="container container-1440">
        <div class="row">
            <div class="col-lg-6">
                <div class="footer-info d-flex flex-column h-100">
                    <img src="{{ URL::asset('build/images/logo-white.png') }}" alt="" class="lg-footer-logo">
                    {{-- <p class="footer-desc mt-4 mb-2 me-3">LA GRAMMA</p> --}}
                    {{-- <p class="footer-desc mt-4 mb-2 me-3">Hampers Lebaran, Lapis Legit & Nastar Premium Pontianak</p> --}}

                    <div class="footer-social mt-auto pt-3">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item">
                                <a target="_blank" href="https://www.instagram.com/lagrammahomemade/" class="text-reset social-link" target="_blank">
                                    <img src="{{ URL::asset('build/images/instagram.png') }}" alt="Instagram"
                                        class="social-logo">
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a target="_blank" href="https://www.tiktok.com/@lagrammahomemade_" class="text-reset social-link" target="_blank">
                                    <img src="{{ URL::asset('build/images/tiktok.png') }}" alt="Tiktok"
                                        class="social-logo">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row pl-0 pl-lg-3">
                    <div class="col-md-4">
                        <div class="mt-lg-0 mt-4">
                            <h5 class="footer-title text-uppercase">Our Product</h5>
                            <ul class="list-unstyled footer-link mt-3">
                                <li><a href="{{ config('app.frontend_url') }}/catalogue?category_id=4483549">Lapis Legit</a></li>
                                <li><a href="{{ config('app.frontend_url') }}/product-detail/51">Nastar</a></li>
                                <li><a href="{{ config('app.frontend_url') }}/catalogue?category_id=4483551">Cookies</a></li>
                                <li><a href="{{ config('app.frontend_url') }}/product-detail/50">Hampers</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mt-lg-0 mt-4">
                            <h5 class="footer-title text-uppercase">Support</h5>
                            <ul class="list-unstyled footer-link mt-3">
                                <li><a href="{{ config('app.frontend_url') }}/frequently-asked-questions">FAQs</a></li>
                                <li><a href="{{ config('app.frontend_url') }}/contact-us">Contacts</a></li>
                                <li><a href="{{ config('app.frontend_url') }}/e-commerce-term-and-condition">Terms and Condition</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mt-lg-0 mt-4">
                            <h5 class="footer-title text-uppercase">My Account</h5>
                            <ul class="list-unstyled footer-link mt-3">
                                <li><a href="{{ config('app.frontend_url') }}/view-cart">Cart</a></li>
                                <li><a href="/orders" data-footer-view-my-order
                                        class="d-none" target="_blank">Order</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Author Section --}}
        <div class="row mt-4 pb-3 pt-3 align-items-center fs-12">
            <div class="col-sm-6 lg-footer-meta-text">
                <script>document.write(new Date().getFullYear())</script> © La Gramma. Develop by Fernandes Wiraharjo
            </div>

            <span>
                <img src="{{ URL::asset('build/images/search-icon.png') }}" alt="La Gramma Logo" class="lg-mobile-footer-accent">
            </span>
            <!-- <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <a href="#!"><img src="{{ URL::asset('build/images/ecommerce/payment/visa.png') }}" alt="" height="30"></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#!"><img src="{{ URL::asset('build/images/ecommerce/payment/discover.png') }}" alt="" height="30"></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#!"><img src="{{ URL::asset('build/images/ecommerce/payment/american-express.png') }}" alt="" height="30"></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#!"><img src="{{ URL::asset('build/images/ecommerce/payment/paypal.png') }}" alt="" height="30"></a>
                        </li>
                    </ul>
                </div>
            </div> -->
        </div>
    </div>
</section>

@include('layouts.footer-mobile')

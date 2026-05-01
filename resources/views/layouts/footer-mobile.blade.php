<section class="section footer-landing footer-landing-mobile d-lg-none py-0">
    <div class="container-fluid px-0">
        <div class="lg-mobile-footer">
            <div class="lg-mobile-footer-brand text-center">
                <a target="_blank" href="https://www.instagram.com/lagrammahomemade/" class="text-reset social-link"
                    target="_blank">
                    <img src="{{ URL::asset('build/images/instagram.png') }}" alt="Instagram" class="social-logo">
                </a>
                <a target="_blank" href="https://www.tiktok.com/@lagrammahomemade_" class="text-reset social-link"
                    target="_blank">
                    <img src="{{ URL::asset('build/images/tiktok.png') }}" alt="Tiktok" class="social-logo">
                </a>
            </div>

            <div class="lg-mobile-footer-accordion" id="lgMobileFooterAccordion">
                <div class="lg-mobile-footer-item">
                    <button class="lg-mobile-footer-toggle collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#mobileFooterProduct" aria-expanded="false" aria-controls="mobileFooterProduct">
                        <span>OUR PRODUCT</span>
                        <span class="toggle-icon" aria-hidden="true"></span>
                    </button>
                    <div id="mobileFooterProduct" class="collapse" data-bs-parent="#lgMobileFooterAccordion">
                        <ul class="list-unstyled lg-mobile-footer-links">
                            <li><a href="{{ config('app.frontend_url') }}/catalogue?category_id=3">Lapis Legit</a></li>
                            <li><a href="{{ config('app.frontend_url') }}/catalogue?category_id=4">Nastar</a></li>
                            <li><a href="{{ config('app.frontend_url') }}/catalogue?category_id=5">Cookies</a></li>
                            <li><a href="{{ config('app.frontend_url') }}/catalogue?category_id=9">Hampers</a></li>
                        </ul>
                    </div>
                </div>

                <div class="lg-mobile-footer-item">
                    <button class="lg-mobile-footer-toggle collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#mobileFooterSupport" aria-expanded="false" aria-controls="mobileFooterSupport">
                        <span>SUPPORT</span>
                        <span class="toggle-icon" aria-hidden="true"></span>
                    </button>
                    <div id="mobileFooterSupport" class="collapse" data-bs-parent="#lgMobileFooterAccordion">
                        <ul class="list-unstyled lg-mobile-footer-links">
                            <li><a href="{{ config('app.frontend_url') }}/frequently-asked-questions">FAQs</a></li>
                            <li><a href="{{ config('app.frontend_url') }}/contact-us">Contacts</a></li>
                            <li><a href="{{ config('app.frontend_url') }}/e-commerce-term-and-condition">Terms and Condition</a></li>
                        </ul>
                    </div>
                </div>

                <div class="lg-mobile-footer-item">
                    <button class="lg-mobile-footer-toggle collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#mobileFooterAccount" aria-expanded="false" aria-controls="mobileFooterAccount">
                        <span>MY ACCOUNT</span>
                        <span class="toggle-icon" aria-hidden="true"></span>
                    </button>
                    <div id="mobileFooterAccount" class="collapse" data-bs-parent="#lgMobileFooterAccordion">
                        <ul class="list-unstyled lg-mobile-footer-links">
                            <li><a href="{{ config('app.frontend_url') }}/view-cart">Cart</a></li>
                            <li><a href="{{ config('app.frontend_url') }}/orders" data-footer-view-my-order
                                    class="d-none" target="_blank">Order</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="footer-social mt-4">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="#!" class="text-reset social-link">
                            <img src="{{ URL::asset('build/images/instagram.png') }}" alt="Instagram"
                                class="social-logo">
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#!" class="text-reset social-link">
                            <img src="{{ URL::asset('build/images/tiktok.png') }}" alt="Tiktok" class="social-logo">
                        </a>
                    </li>
                </ul>
            </div>

            <div class="lg-mobile-footer-meta lg-mobile-footer-meta-small">
                <script>
                    document.write(new Date().getFullYear())
                </script> &copy; La Gramma . Develop by
                <a href="https://fernandesdev.com/" target="_blank"
                    class="text-reset text-decoration-underline">Fernandes Wiraharjo</a>
            </div>

            <span>
                <img src="{{ URL::asset('build/images/search-icon.png') }}" alt="La Gramma Logo"
                    class="lg-mobile-footer-accent">
            </span>
            {{-- <i class="bi bi-flower1 lg-mobile-footer-accent" aria-hidden="true"></i> --}}
        </div>
    </div>
</section>

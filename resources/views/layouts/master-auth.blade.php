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

    <section
        class="auth-page-wrapper position-relative bg-light min-vh-100 d-flex align-items-center justify-content-between">
        <div class="auth-header position-fixed top-0 start-0 end-0 bg-body">
            <div class="container-fluid">
                <div class="row justify-content-between align-items-center">
                    <div class="col-2">
                        <a class="navbar-brand mb-2 mb-sm-0" href="index">
                            <!-- <img src="{{ URL::asset('build/images/logo-dark.png') }}" class="card-logo card-logo-dark"
                                alt="logo dark" height="22">
                            <img src="{{ URL::asset('build/images/logo-light.png') }}"
                                class="card-logo card-logo-light" alt="logo light" height="22"> -->
                            LA GRAMMA
                        </a>
                    </div>
                    <!---end col-->
                    <div class="col-auto">
                        <!-- <ul class="list-unstyled hstack gap-2 mb-0">
                            <li class="me-md-3">
                                <a href="#!" class="text-body fw-medium fs-15">Become a Selling</a>
                            </li>
                            <li class="d-none d-md-block">
                                <a href="#!" class="btn btn-soft-secondary" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="bi bi-google-play align-middle me-1"></i> Download App
                                </a>
                            </li>
                            <li class="d-none d-md-block">
                                <a href="#!" class="btn btn-soft-primary" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="bi bi-apple align-middle me-1"></i> Download App
                                </a>
                            </li>
                        </ul> -->
                    </div>
                </div>
                <!--end row-->
            </div>
            <!--end container-fluid-->
        </div>

        <!--content here-->
        @yield('content')
    </section>

    <!--script-->
    @include('layouts.vendor-scripts')
</body>

</html>

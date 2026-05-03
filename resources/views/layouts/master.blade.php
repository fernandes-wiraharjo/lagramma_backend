<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="light"
    data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-body-image="none">

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

    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- top tagbar -->
        @include('layouts.top-tagbar')
        <!-- topbar -->
        @include('layouts.topbar')
        @include('layouts.sidebar')

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <!-- footer -->
            @if (!session())
                @include('layouts.footer')
            @else
                <footer class="footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="text-center">
                                    <p class="mb-0 text-muted">©
                                        <script>
                                            document.write(new Date().getFullYear())
                                        </script> La Gramma. Crafted by Fernandes Wiraharjo
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> © La Gramma.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Developed by Fernandes Wiraharjo
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            @endif
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- customizer -->
    @include('layouts.customizer')
    <!-- scripts -->
    @include('layouts.vendor-scripts')

</body>

</html>

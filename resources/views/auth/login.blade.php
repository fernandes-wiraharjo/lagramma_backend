@extends('layouts.master-auth')
@section('title') Login @endsection
@section('content')

    <div class="w-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="auth-card mx-lg-3">
                        <div class="card border-0 mb-0">
                            <div class="card-header bg-primary border-0">
                                <div class="row">
                                    <div class="col-lg-4 col-3">
                                        <img src="{{ URL::asset('build/images/auth/img-1.png') }}" alt=""
                                            class="img-fluid">
                                    </div>
                                    <div class="col-lg-8 col-9">
                                        <h1 class="text-white lh-base fw-lighter">La Gramma Account Login</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="text-muted fs-15">Sign in.</p>
                                <div class="p-2">
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        @if(request('redirect'))
                                            <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                                        @endif
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="" required autocomplete="email" autofocus
                                                placeholder="Enter your email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3 d-none">
                                            <!-- <div class="float-end">
                                                <a href="{{ route('password.request') }}" class="text-muted">Forgot password?</a>
                                            </div> -->
                                            <label class="form-label" for="password-input">Password</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input id="password" type="password"
                                                    class="form-control password-input @error('password') is-invalid @enderror"
                                                    name="password" autocomplete="current-password" placeholder="Enter your password" value="">
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                        </div> -->

                                        <div class="mt-4">
                                            <button class="btn btn-primary w-100" type="submit">Sign In</button>
                                        </div>

                                        <!-- <div class="mt-4 pt-2 text-center">
                                            <div class="signin-other-title">
                                                <h5 class="fs-13 mb-4 title">Sign In with</h5>
                                            </div>
                                            <div class="pt-2 hstack gap-2 justify-content-center">
                                                <button type="button" class="btn btn-soft-primary btn-icon"><i
                                                        class="ri-facebook-fill fs-16"></i></button>
                                                <button type="button" class="btn btn-soft-danger btn-icon"><i
                                                        class="ri-google-fill fs-16"></i></button>
                                                <button type="button" class="btn btn-soft-dark btn-icon"><i
                                                        class="ri-github-fill fs-16"></i></button>
                                                <button type="button" class="btn btn-soft-info btn-icon"><i
                                                        class="ri-twitter-fill fs-16"></i></button>
                                            </div>
                                        </div> -->
                                    </form>

                                    <div class="text-center mt-5">
                                        <p class="mb-0">Don't have an account ? <a href="{{ route('register') }}"
                                                class="fw-semibold text-secondary text-decoration-underline"> Sign Up</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->

        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">©
                                <!-- <script>
                                    document.write(new Date().getFullYear())
                                </script> Toner. Crafted with <i class="mdi mdi-heart text-danger"></i> by
                                Themesbrand -->
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> La Gramma. Crafted by
                                <a href="https://fernandesdev.com" target="_blank">Fernandes Wiraharjo</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection
@section('scripts')
    <script src="{{ URL::asset('build/js/pages/password-addon.init.js') }}"></script>
@endsection

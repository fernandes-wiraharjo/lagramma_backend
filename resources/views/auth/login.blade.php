@extends('layouts.lagramma-master-auth')
@section('title')
    Login
@endsection
@section('content')
    <div class="lagramma-auth-wrapper">
        <div class="w-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="auth-card mx-lg-3">
                            <div class="card border-0 mb-0 shadow-blur lagramma-auth-card">
                                <div class="card-header d-flex p-0 lagramma-auth-card-header" style="border-border-top-left-radius: 20px; border-top-right-radius: 20px;">
                                    <div class="lagramma-auth-tab lagramma-auth-tab-active">
                                        SIGN IN
                                    </div>
                                    <div
                                        class="lagramma-auth-tab lagramma-auth-tab-inactive"
                                        onclick="window.location.href='{{ route('register') }}'"
                                    >
                                        SIGN UP
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div style="padding: 24px 48px 24px 48px;">
                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf
                                            @if (request('redirect'))
                                                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                                            @endif
                                            <div class="mb-3">
                                                <label for="email" class="form-label lagramma-form-label">Email</label>
                                                <input id="email" type="email"
                                                    class="form-control @error('email') is-invalid @enderror lagramma-form-input" name="email"
                                                    value="" required autocomplete="email" autofocus>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <!-- <div class="mb-3 d-none"> -->
                                            <div class="mb-3">
                                                <!-- <div class="float-end">
                                                    <a href="{{ route('password.request') }}" class="text-muted">Forgot password?</a>
                                                </div> -->
                                                <label class="form-label lagramma-form-label" for="password-input">Password</label>
                                                <div class="position-relative auth-pass-inputgroup mb-3">
                                                    <!-- <input id="password" type="password"
                                                        class="form-control password-input @error('password') is-invalid @enderror"
                                                        name="password" autocomplete="current-password" placeholder="Enter your password" value=""> -->
                                                    <input id="password" type="password"
                                                        class="form-control password-input @error('password') is-invalid @enderror lagramma-form-input"
                                                        name="password" required autocomplete="current-password"
                                                        value="">
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
                                                <button class="lagramma-button-solid w-100 py-2 lagramma-auth-button" type="submit">Sign In</button>
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

                                        {{-- <div class="text-center mt-5">
                                            <p class="mb-0">Don't have an account ? <a
                                                    href="{{ route('register', ['redirect' => request('redirect')]) }}"
                                                    class="fw-semibold text-secondary text-decoration-underline"> Sign
                                                    Up</a>
                                            </p>
                                        </div> --}}
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
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ URL::asset('build/js/pages/password-addon.init.js') }}"></script>
@endsection

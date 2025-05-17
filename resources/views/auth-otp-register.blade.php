@extends('layouts.master-auth')
@section('title')
    Two Step Verification
@endsection
@section('css')
    <!-- extra css -->
@endsection
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
                                        <img src="{{ URL::asset('build/images/auth/img-1.png') }}" alt="" class="img-fluid">
                                    </div>
                                    <div class="col-lg-8 col-9">
                                        <h1 class="text-white lh-base fw-lighter">Verify Your Phone</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <p class="text-muted fs-15">
                                    Please enter the 4 digit code sent to <span class="fw-semibold">{{ request('phone') }}</span>.
                                    The code is valid for a maximum of 5 minutes.
                                </p>

                                <div class="p-2">
                                    <form id="verify-otp-form" autocomplete="off" action="{{ route('register.otp.verify.submit') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="phone" value="{{ request('phone') }}">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit1-input" class="visually-hidden">Digit 1</label>
                                                    <input type="text"
                                                        name="otp[]"
                                                        class="form-control form-control-lg bg-light border-light text-center @error('otp') is-invalid @enderror"
                                                        onkeyup="moveToNext(1, event)" maxLength="1" id="digit1-input">
                                                </div>
                                            </div><!-- end col -->

                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit2-input" class="visually-hidden">Digit 2</label>
                                                    <input type="text"
                                                        name="otp[]"
                                                        class="form-control form-control-lg bg-light border-light text-center @error('otp') is-invalid @enderror"
                                                        onkeyup="moveToNext(2, event)" maxLength="1" id="digit2-input">
                                                </div>
                                            </div><!-- end col -->

                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit3-input" class="visually-hidden">Digit 3</label>
                                                    <input type="text"
                                                        name="otp[]"
                                                        class="form-control form-control-lg bg-light border-light text-center @error('otp') is-invalid @enderror"
                                                        onkeyup="moveToNext(3, event)" maxLength="1" id="digit3-input">
                                                </div>
                                            </div><!-- end col -->

                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit4-input" class="visually-hidden">Digit 4</label>
                                                    <input type="text"
                                                        name="otp[]"
                                                        class="form-control form-control-lg bg-light border-light text-center @error('otp') is-invalid @enderror"
                                                        onkeyup="moveToNext(4, event)" maxLength="1" id="digit4-input">
                                                </div>
                                            </div><!-- end col -->
                                        </div>
                                        @error('otp')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                        <!-- <div class="mt-3">
                                            <button type="submit" class="btn btn-primary w-100">Confirm</button>
                                        </div> -->
                                    </form><!-- end form -->
                                </div>
                                <div class="mt-4 text-center">
                                    <p class="mb-0">Didn't receive a code ?
                                        <a href="#"
                                            onclick="event.preventDefault(); document.getElementById('resend-otp-form').submit();"
                                            class="fw-semibold text-primary text-decoration-underline">
                                            Resend
                                        </a>
                                    </p>
                                </div>
                                <div class="mt-4 text-center">
                                    <p class="mb-0"> <a href="{{ route('register') }}"
                                            class="fw-semibold text-primary text-decoration-underline"> Back </a> </p>
                                </div>

                                <form id="resend-otp-form" action="{{ route('register.otp.resend') }}" method="POST" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="phone" value="{{ request('phone') }}">
                                </form>
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
    <script src="{{ URL::asset('build/js/pages/register-otp-verification.init.js') }}"></script>
@endsection

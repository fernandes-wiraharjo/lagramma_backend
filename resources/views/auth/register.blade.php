@extends('layouts.master-auth')
@section('title')
    Register
@endsection
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/css/intlTelInput.min.css">
@endsection
@section('content')
    {{-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

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
                                        <h1 class="text-white text-capitalize lh-base fw-lighter">La Gramma Account Sign Up</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="text-muted fs-15">Get your free La Gramma account now</p>
                                <div class="p-2">
                                    <form class="needs-validation" novalidate method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                                        @csrf

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Name <span
                                                            class="text-danger">*</span></label>
                                                    <input id="name" type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" value="{{ old('name') }}" required
                                                        autocomplete="name" autofocus
                                                        placeholder="Enter your name">
                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="last_name" class="form-label">Last Name <span
                                                            class="text-danger">*</span></label>
                                                    <input id="last_name" type="text"
                                                        class="form-control @error('last_name') is-invalid @enderror"
                                                        name="last_name" value="{{ old('last_name') }}" required
                                                        autocomplete="last_name" autofocus
                                                        placeholder="Enter tour first name">
                                                    @error('last_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div> -->

                                            <div class="mb-3 col-md-12">
                                                <label for="email" class="form-label">Email <span
                                                        class="text-danger">*</span></label>
                                                <input id="email" type="email"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ old('email') }}" required autocomplete="email"
                                                    placeholder="Enter your email">
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-md-12">
                                                <label for="phone" class="form-label d-block">Phone <span
                                                        class="text-danger">*</span></label>
                                                <input id="phone" type="tel"
                                                    class="form-control @error('phone') is-invalid @enderror" name="phone"
                                                    value="{{ old('phone') }}" required autocomplete="phone"
                                                    placeholder="Enter your phone">
                                                <!-- Hidden input to store the full number -->
                                                <input type="hidden" name="full_phone" id="fullPhone">
                                                @error('phone')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-md-12 d-none">
                                                <label class="form-label" for="password-input">Password <span
                                                        class="text-danger">*</span></label>
                                                <div class="position-relative auth-pass-inputgroup">
                                                    <input type="password"
                                                        class="form-control pe-5 password-input @error('password') is-invalid @enderror"
                                                        onpaste="return false" placeholder="Enter password"
                                                        id="password-input" name="password" aria-describedby="passwordInput"
                                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
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

                                            <div class="mb-3 col-md-12 d-none">
                                                <label for="password-confirm"
                                                    class="form-label">{{ __('Confirm Password') }} <span
                                                        class="text-danger">*</span></label>
                                                <div class="position-relative auth-pass-inputgroup">
                                                    <input type="password" class="form-control pe-5 password-input"
                                                        placeholder="Confirm password" name="password_confirmation"
                                                        aria-describedby="passwordInput" required>
                                                    <button
                                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                        type="button"><i class="ri-eye-fill align-middle"></i></button>
                                                </div>
                                            </div>

                                            <!-- <div class="mb-3 col-md-12">
                                                <label for="avatar" class="form-label">Avatar <span
                                                        class="text-danger">*</span></label>
                                                <input id="avatar" type="file"
                                                    class="form-control @error('avatar') is-invalid @enderror"
                                                    name="avatar" value="{{ old('avatar') }}" required
                                                    autocomplete="avatar" placeholder="Enter your avatar">
                                                @error('avatar')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div> -->
                                        </div>

                                        <!-- <div class="mb-4 col-md-12">
                                            <p class="mb-0 fs-12 text-muted fst-italic">By registering you agree to the
                                                Toner <a href="#"
                                                    class="text-primary text-decoration-underline fst-normal fw-medium">Terms
                                                    of Use</a></p>
                                        </div> -->

                                        <div id="password-contain" class="p-3 bg-light mb-2 rounded">
                                            <h5 class="fs-13">Password must contain:</h5>
                                            <p id="pass-length" class="invalid fs-12 mb-2">Minimum <b>8 characters</b></p>
                                            <p id="pass-lower" class="invalid fs-12 mb-2">At <b>lowercase</b> letter (a-z)
                                            </p>
                                            <p id="pass-upper" class="invalid fs-12 mb-2">At least <b>uppercase</b> letter
                                                (A-Z)</p>
                                            <p id="pass-number" class="invalid fs-12 mb-0">A least <b>number</b> (0-9)</p>
                                        </div>

                                        <div class="mt-4 col-md-12">
                                            <button class="btn btn-primary w-100" type="submit">Sign Up</button>
                                        </div>

                                        <!-- <div class="mt-4 text-center col-md-12">
                                            <div class="signin-other-title">
                                                <h5 class="fs-13 mb-4 title text-muted">Create account with</h5>
                                            </div>

                                            <div>
                                                <button type="button" class="btn btn-soft-primary btn-icon "><i
                                                        class="ri-facebook-fill fs-16"></i></button>
                                                <button type="button" class="btn btn-soft-danger btn-icon "><i
                                                        class="ri-google-fill fs-16"></i></button>
                                                <button type="button" class="btn btn-soft-dark btn-icon "><i
                                                        class="ri-github-fill fs-16"></i></button>
                                                <button type="button" class="btn btn-soft-info btn-icon "><i
                                                        class="ri-twitter-fill fs-16"></i></button>
                                            </div>
                                        </div> -->
                                    </form>
                                </div>
                                <div class="mt-4 text-center">
                                    <p class="mb-0">Already have an account ? <a href="{{ route('login') }}"
                                            class="fw-semibold text-primary text-decoration-underline"> Sign in </a> </p>
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
    <script src="{{ URL::asset('build/js/pages/password-match.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/pages/password-addon.init.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script>
        const phoneInput = document.querySelector("#phone");
        const fullPhoneInput = document.querySelector("#fullPhone");

        // Initialize the intl-tel-input plugin
        const iti = intlTelInput(phoneInput, {
            initialCountry: "id",  // Sets default to Indonesia (+62)
            separateDialCode: true, // Shows the dial code separately
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
        });

        // Update the hidden input value before form submission
        phoneInput.addEventListener("change", updateFullNumber);
        phoneInput.addEventListener("keyup", updateFullNumber);

        function updateFullNumber() {
            // Get the full phone number with the country code
            const fullNumber = iti.getNumber();
            // Update the hidden input value
            fullPhoneInput.value = fullNumber;
        }
    </script>
@endsection

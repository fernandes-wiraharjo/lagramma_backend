@extends('layouts.lagramma-master-auth')
@section('title')
    Register
@endsection
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.19/build/css/intlTelInput.min.css">
@endsection
@section('content')
    <div class="lagramma-auth-wrapper">
        <div class="w-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="auth-card mx-lg-3">
                            <div class="card border-0 mb-0 shadow-blur lagramma-auth-card">
                                <div class="d-flex p-0 lagramma-auth-card-header">
                                    <div class="lagramma-auth-tab lagramma-auth-tab-inactive"
                                        onclick="window.location.href='{{ route('login') }}'">
                                        SIGN IN
                                    </div>
                                    <div class="lagramma-auth-tab lagramma-auth-tab-active">
                                        SIGN UP
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="p-2">
                                        <form class="needs-validation" novalidate method="POST"
                                            action="{{ route('register', ['redirect' => request('redirect')]) }}"
                                            enctype="multipart/form-data">
                                            @csrf

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label lagramma-form-label">Name
                                                            <span class="text-danger">*</span></label>
                                                        <input id="name" type="text"
                                                            class="form-control @error('name') is-invalid @enderror lagramma-form-input"
                                                            name="name" value="{{ old('name') }}" required
                                                            autocomplete="name" autofocus>
                                                        @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="email" class="form-label lagramma-form-label">Email
                                                            <span class="text-danger">*</span></label>
                                                        <input id="email" type="email"
                                                            class="form-control @error('email') is-invalid @enderror lagramma-form-input"
                                                            name="email" value="{{ old('email') }}" required
                                                            autocomplete="email">
                                                        @error('email')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="phone"
                                                            class="form-label d-block lagramma-form-label">Phone <span
                                                                class="text-danger">*</span></label>
                                                        <input id="phone" type="tel"
                                                            class="form-control @error('phone') is-invalid @enderror lagramma-form-input"
                                                            name="phone" value="{{ old('phone') }}" required
                                                            autocomplete="phone" placeholder="" style="width: 100%;">
                                                        <!-- Hidden input to store the full number -->
                                                        <input type="hidden" name="full_phone" id="fullPhone">
                                                        @error('phone')
                                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label lagramma-form-label"
                                                            for="password-input">Password <span
                                                                class="text-danger">*</span></label>
                                                        <div class="position-relative auth-pass-inputgroup">
                                                            <input type="password"
                                                                class="form-control pe-5 password-input @error('password') is-invalid @enderror lagramma-form-input"
                                                                onpaste="return false"
                                                                id="password-input" name="password"
                                                                aria-describedby="passwordInput"
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

                                                    <div class="mb-3">
                                                        <label for="password-confirm"
                                                            class="form-label lagramma-form-label">{{ __('Confirm Password') }}
                                                            <span class="text-danger">*</span></label>
                                                        <div class="position-relative auth-pass-inputgroup">
                                                            <input type="password"
                                                                class="form-control pe-5 password-input lagramma-form-input"
                                                                name="password_confirmation"
                                                                aria-describedby="passwordInput" required>
                                                            <button
                                                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                                type="button"><i
                                                                    class="ri-eye-fill align-middle"></i></button>
                                                        </div>
                                                    </div>

                                                    @if (false)
                                                    <div>
                                                        <div id="password-contain" class="p-3 bg-light mb-2 rounded">
                                                            <h5 class="fs-13">Password must contain:</h5>
                                                            <p id="pass-length" class="invalid fs-12 mb-2">Minimum <b>8
                                                                    characters</b></p>
                                                            <p id="pass-lower" class="invalid fs-12 mb-2">At
                                                                <b>lowercase</b>
                                                                letter (a-z)
                                                            </p>
                                                            <p id="pass-upper" class="invalid fs-12 mb-2">At least
                                                                <b>uppercase</b> letter
                                                                (A-Z)
                                                            </p>
                                                            <p id="pass-number" class="invalid fs-12 mb-0">A least
                                                                <b>number</b>
                                                                (0-9)</p>
                                                        </div>

                                                        <h5 class="fs-13">Password must contain:</h5>
                                                        <p id="pass-length" class="invalid fs-12 mb-2">Minimum <b>8
                                                                characters</b>
                                                        </p>
                                                        <p id="pass-lower" class="invalid fs-12 mb-2">At <b>lowercase</b>
                                                            letter
                                                            (a-z)
                                                        </p>
                                                        <p id="pass-upper" class="invalid fs-12 mb-2">At least
                                                            <b>uppercase</b>
                                                            letter
                                                            (A-Z)</p>
                                                        <p id="pass-number" class="invalid fs-12 mb-0">A least
                                                            <b>number</b> (0-9)
                                                        </p>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="mt-4 col-md-12">
                                                <button
                                                    class="lagramma-button-solid w-100 py-2 lagramma-auth-button"
                                                    type="submit">Sign Up</button>
                                            </div>
                                        </form>
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
            initialCountry: "id", // Sets default to Indonesia (+62)
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

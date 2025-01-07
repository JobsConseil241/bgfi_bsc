<html
    lang="en"
    class="light-style layout-wide customizer-hide"
    dir="ltr"
    data-theme="theme-default"
    data-template="vertical-menu-template">
<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Se Connecter | BGFI</title>

    <meta name="description" content="" />

    <!-- Favicon -->'
    <link rel="icon" type="image/x-icon" href="{{url('assets/frontend/img/bgfi.jpg')}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{url('assets/backend/vendor/fonts/fontawesome.css')}}" />
    <link rel="stylesheet" href="{{url('assets/backend/vendor/fonts/tabler-icons.css')}}" />
    <link rel="stylesheet" href="{{url('assets/backend/vendor/fonts/flag-icons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{url('assets/backend/vendor/css/rtl/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{url('assets/backend/vendor/css/rtl/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{url('assets/backend/css/demo.css')}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{url('assets/backend/vendor/libs/node-waves/node-waves.css')}}" />
    <link rel="stylesheet" href="{{url('assets/backend/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
{{--    <link rel="stylesheet" href="assets/backend/vendor/libs/typeahead-js/typeahead.css" />--}}
    <!-- Vendor -->
{{--    <link rel="stylesheet" href="assets/backend/vendor/libs/@form-validation/form-validation.css" />--}}

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{url('assets/backend/vendor/css/pages/page-auth.css')}}" />

    <!-- Helpers -->
{{--    <script src="assets/backend/vendor/js/helpers.js"></script>--}}
{{--    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->--}}
{{--    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->--}}
{{--    <script src="assets/backend/vendor/js/template-customizer.js"></script>--}}
{{--    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->--}}
{{--    <script src="assets/backend/js/config.js"></script>--}}
</head>

<body style="background-image: url('{{url('assets/frontend/img/BGFI BG.jpg')}}'); background-repeat: no-repeat; background-size: cover">
<!-- Content -->

<div class="container-xxl" >
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
            <!-- Login -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-4 mt-2">
                        <a href="#" class="app-brand-link gap-2">
                            <img src="{{url('assets/frontend/img/bgfi.jpg')}}" alt="" srcset="" width="100" height="70">
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h5 class="mb-1 pt-2">Bienvenue sur votre tableau de bord 👋</h5>
                    <p class="mb-4">AUTHENTIFICATION ADMIN</p>

                    <form class="mb-3" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required autocomplete="email"
                                id="email"
                                name="email"
                                placeholder="Enter your email or username"
                                autofocus />

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Mot de Passe</label>
                                <a href="">
                                    <small>Mot de Passe Oublié?</small>
                                </a>
                            </div>
                            <div class="input-group input-group-merge">
                                <input
                                    type="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password"
                                    name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }} />
                                <label class="form-check-label" for="remember-me"> Se Souvenir de Moi </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button data-mdb-ripple-init class="btn btn-primary d-grid w-100" type="submit" id="login">Se Connecter</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>
</div>

<!-- / Content -->

<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->

<script src="{{url('assets/backend/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{url('assets/backend/vendor/libs/popper/popper.js')}}"></script>
<script src="{{url('assets/backend/vendor/js/bootstrap.js')}}"></script>
<script src="{{url('assets/backend/vendor/libs/node-waves/node-waves.js')}}"></script>
<script src="{{url('assets/backend/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{url('assets/backend/vendor/libs/hammer/hammer.js')}}"></script>
<script src="{{url('assets/backend/vendor/libs/i18n/i18n.js')}}"></script>
<script src="{{url('assets/backend/vendor/libs/typeahead-js/typeahead.js')}}"></script>
<script src="{{url('assets/backend/vendor/js/menu.js')}}"></script>

<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{url('assets/backend/vendor/libs/@form-validation/popular.js')}}"></script>
<script src="{{url('assets/backend/vendor/libs/@form-validation/bootstrap5.js')}}"></script>
<script src="{{url('assets/backend/vendor/libs/@form-validation/auto-focus.js')}}"></script>

<!-- Main JS -->
<script src="{{url('assets/backend/js/main.js')}}"></script>

<!-- Page JS -->
<script src="{{url('assets/backend/js/pages-auth.js')}}"></script>

</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPro - Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href={{ asset('favicon.ico') }}>
    <!-- Theme Config Js -->
    <script src={{ asset('hyper/dist/saas/assets/js/hyper-config.js') }}></script>
    <!-- App css -->
    <link href={{ asset('hyper/dist/saas/assets/css/app-saas.min.css') }} rel="stylesheet" type="text/css" id="app-style" />
    <!-- Icons css -->
    <link href={{ asset('hyper/dist/saas/assets/css/icons.min.css') }} rel="stylesheet" type="text/css" />

    <!-- font css -->
    <link rel="stylesheet" href={{ asset('style.css') }}>
</head>

<body>
    <!-- Pre-loader -->
    <div id="preloader" style="display: none;">
        <div id="status" style="display: none;">
            <div class="bouncing-loader"><div></div><div></div><div></div></div>
        </div>
    </div>
    <!-- End Preloader-->

        <div class="container">
            <div class="row justify-content-center align-items-center authentication authentication-basic h-100">
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
                    <div class="mt-5 mb-4 d-flex justify-content-center">
                        <a href="/">
                            <img src="{{asset('images/brand-logos/desktop-logo.png')}}" alt="logo" class="desktop-logo" height="46">
                        </a>
                    </div>
                    <div class="card rounded-4">
                        <div class="card-body px-5 py-4">
                            <p class="h3 fw-bold mb-2 text-center">Sign In</p>
                            <p class="mb-2 text-muted op-7 fw-normal text-center">Welcome back !</p>
                            <div class="row gy-3">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    @if($errors->any())
                                        {!! implode('', $errors->all('<div class="alert alert-danger alert-dismissible fade show">:message<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button></div>')) !!}
                                    @endif
                                    <div class="col-xl-12 mb-2">
                                        <label for="username" class="form-label fw-bold">Username</label>
                                        <input type="text" id="username" name="username" class="form-control form-control-lg" placeholder="username"  value="{{ old('username') }}" required>
                                    </div>
                                    <div class="col-xl-12 mb-2">
                                        <label for="password" class="form-label fw-bold d-block">Password<a href="{{url('resetpassword-basic')}}" class="float-end text-danger">Forget password ?</a></label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password" class="form-control form-control-lg" name="password" placeholder="Enter your password">
                                            <div class="input-group-text" data-password="false">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                                <label class="form-check-label text-muted fw-normal" for="defaultCheck1">
                                                    Remember password ?
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 d-grid mt-2">
                                        <button type="submit" class="btn btn-lg btn-primary">Sign In</button>
                                    </div>
                                </form>
                            </div>
                            {{-- <div class="text-center">
                                <p class="fs-12 text-muted mt-3">Dont have an account? <a href="{{url('signup-basic')}}" class="text-primary">Sign Up</a></p>
                            </div>
                            <div class="text-center my-3 authentication-barrier">
                                <span>OR</span>
                            </div>
                            <div class="btn-list text-center">
                                <button class="btn btn-icon btn-light">
                                    <i class="ri-facebook-line fw-bold text-dark op-7"></i>
                                </button>
                                <button class="btn btn-icon btn-light">
                                    <i class="ri-google-line fw-bold text-dark op-7"></i>
                                </button>
                                <button class="btn btn-icon btn-light">
                                    <i class="ri-twitter-line fw-bold text-dark op-7"></i>
                                </button>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <!-- bundle -->
    <script src={{ asset('hyper/dist/saas/assets/js/vendor.min.js') }}></script>
    <script src={{ asset('hyper/dist/saas/assets/js/app.min.js') }}></script>
</body>


</html>
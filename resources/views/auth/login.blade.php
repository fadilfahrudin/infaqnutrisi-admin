<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login Admin | Infaq nutrisi</title>
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>

<body style="background-color: #CC59AA">
    <div id="app" class="container my-4">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-lg-6 mx-auto">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="p-5">
                            <div class="text-center">
                                <img src="{{ asset('assets/img/logo-header.png') }}" height="100" width="auto" alt="">
                            </div>
                            {{-- <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Assalamu'alaikum</h1>
                            </div> --}}
                            <form action="{{ route('login') }}" method="post" class="user">
                                @csrf
                                <div class="form-group">
                                    <input type="email"
                                        class="form-control form-control-user @error('email') is-invalid @enderror"
                                        placeholder="Masukkan Alamat E-Mail" name="email" value="{{ old('email') }}"
                                        required autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="password"
                                        class="form-control form-control-user @error('password') is-invalid @enderror"
                                        placeholder="Password" name="password" required autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox small">
                                        <input type="checkbox" class="custom-control-input" id="customCheck">
                                        <label class="custom-control-label" for="customCheck">Ingat Saya</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-user btn-block">Login</button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="{{ url('/forgot-password') }}">Lupa Password?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/admin.min.js') }}"></script>
</body>

</html>

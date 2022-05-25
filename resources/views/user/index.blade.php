@extends('layouts.admin')

@section('title')
Akun {{ $user->name }} @endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Akun</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Kunjungi Website</a>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm mt-3">
            <div class="card-header"><h5 class="mb-0">Profil Anda</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="d-block text-center">
                            <img class="img-profile rounded-circle" src="{{ asset('assets/img/user.svg') }}" height="90" alt=""><br><br>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session()->get('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button></div>
                        @endif
                        <form action="{{ url('/user/'.$user->id.'/profile') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control" name="nama" value="{{ $user->name }}">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="btn btn-primary btn-block" type="submit">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card shadow-sm mt-3">
            <div class="card-header"><h5 class="mb-0">Password Anda</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <p class="text-muted"><small>Jika Anda ingin mengubah password, silakan ubah pada form berikut, minimal 6 karakter.</small></p>
                    </div>
                    <div class="col-lg-8">
                        @if(session()->has('success-password'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session()->get('success-password') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button></div>
                        @endif
                        @if(session()->has('error'))
                        <div class="alert alert-danger alert-dismissable fade show" role="alert">
                        {{ session()->get('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        @endif
                        <form action="{{ url('/user/'.$user->id.'/password') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" pattern=".{6,}" placeholder="Password baru">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="confirm_password" pattern=".{6,}" placeholder="Ulangi password baru">
                            </div>
                            <button class="btn btn-primary btn-block" type="submit">Ubah</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
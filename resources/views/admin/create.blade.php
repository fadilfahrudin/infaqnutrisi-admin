@extends('layouts.admin')

@section('title')
Create Data Admin @endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Create Data Admin</h1>
</div>
@if (session()->has('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    {{ session()->get('error') }}
</div>
@endif
<div class="row">
    <div class="col">
        <form action="{{ route('admin.insert') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Nama<sup class="text-danger">*</sup></label>
                        <div class="col-10">
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Email<sup class="text-danger">*</sup></label>
                        <div class="col-10">
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Password Baru<sup class="text-danger">*</sup></label>
                        <div class="col-10">
                            <input type="password" class="form-control" name="password" id="password" pattern=".{6,}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Ulangi Password Baru<sup class="text-danger">*</sup></label>
                        <div class="col-10">
                            <input type="password" class="form-control" name="conpass" id="conpass" pattern=".{6,}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Akitfkan user ini?</label>
                        <div class="col-10 mt-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_super" id="is_super" value="1" required>
                                <label class="form-check-label" for="inlineRadio2">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_super" id="is_super" value="0" >
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-between">
                        <a href="{{ route('admin') }}" class="btn btn-outline-secondary justify-content-start">Batal</a>
                        <button type="submit" id="simpan" class="btn btn-primary justify-content-end">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('bodyscript')
<script>
    $('#conpass').blur(() => {
        if ($('#conpass').val() != $('#password').val()) {
            alert('Password harus sama');
            $('#conpass').removeClass('is-invalid').addClass('is-invalid');
        } else {
            $('#conpass').removeClass('is-invalid').addClass('is-valid');
            $('#password').addClass('is-valid');
        }
    })
</script>
@endsection

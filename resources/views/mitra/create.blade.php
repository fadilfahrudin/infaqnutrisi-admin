@extends('layouts.admin')

@section('title')
Create Data Mitra @endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Create Data Mitra</h1>
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
        <form action="{{ route('mitra.insert') }}" method="post">
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
                        <label for="" class="col-2 col-form-label">No. Telfon<sup class="text-danger">*</sup></label>
                        <div class="col-10">
                            <input type="num" name="phone" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Email<sup class="text-danger">*</sup></label>
                        <div class="col-10">
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Role<sup class="text-danger">*</sup></label>
                        <div class="col-10">
                            <select name="role" id="role" class="form-control" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="1">Mitra</option>
                                <option value="2">User</option>
                            </select>
                            <input type="hidden" name="mitra_id" id="mitra_id">
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
                        <label for="" class="col-2 col-form-label">Kode Referal</label>
                        <div class="col-10">
                            <input type="text" name="refcode" id="refcode" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-between">
                        <a href="{{ route('mitra') }}" class="btn btn-outline-secondary justify-content-start">Batal</a>
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
    $('#email').blur(() => {
        $.get('{{url('/mitra/valEmail')}}',{
            _token: $('meta[name="csrf-token"]').attr('content'),
            email: $('#email').val()
        }, data => {
            if (data == 'true') {
                alert('Email sudah ada');
                $('#email').addClass('is-invalid');
                $('#simpan').prop('disabled', true);
            } else {
                $('#email').removeClass('is-invalid');
                $('#simpan').prop('disabled', false);
            }
        });
    });
    $('#refcode').blur(() => {
        $.get('{{url('/mitra/valRefcode')}}',{
            _token: $('meta[name="csrf-token"]').attr('content'),
            refcode: $('#refcode').val()
        }, data => {
            if (data == 'true') {
                alert('Kode referal sudah ada');
                $('#refcode').addClass('is-invalid');
                $('#simpan').prop('disabled', true);
            } else {
                $('#refcode').removeClass('is-invalid');
                $('#simpan').prop('disabled', false);
            }
        });
    });
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

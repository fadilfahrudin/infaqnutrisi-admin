@extends('layouts.admin')

@section('title')
Edit Data Mitra @endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Data Mitra</h1>
</div>
<div class="row">
    <div class="col">
        <form action="{{ route('mitra.update', ['id' => $r->id]) }}" method="post">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Foto Profil</label>
                        <div class="col-10">
                            <div class="d-flex">
                                @php $photo = !empty($r->photo) ? $sb_asset.'/img/mitra/'.$r->photo : $sb_asset.'/assets/img/avatar.svg'; @endphp
                                <img src="{{ $photo }}" class="img-thumbnail" height="100" width="100">
                                <div class="px-4 mt-auto">
                                    <input type="file" class="d-block" id="upload">
                                    <small class="text-danger"><sup>*</sup>Format jpeg,png,jpg dan Max file size 2MB</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Nama<sup class="text-danger">*</sup></label>
                        <div class="col-10">
                            <input type="text" name="name" class="form-control" value="{{ $r->name }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">No. Telfon<sup class="text-danger">*</sup></label>
                        <div class="col-10">
                            <input type="num" name="phone" class="form-control" value="{{ $r->phone }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Email<sup class="text-danger">*</sup></label>
                        <div class="col-10">
                            <input type="email" name="email" class="form-control" value="{{ $r->email }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Password Baru</label>
                        <div class="col-10">
                            <input type="password" class="form-control" name="password" id="password" pattern=".{6,}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Ulangi Password Baru</label>
                        <div class="col-10">
                            <input type="password" class="form-control" name="conpass" id="conpass" pattern=".{6,}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Kode Referal<sup class="text-danger">*</sup></label>
                        <div class="col-10">
                            <input type="text" name="refcode" id="refcode" class="form-control" value="{{ $r->refcode }}">
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Akitfkan user ini?</label>
                        <div class="col-10 mt-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" id="is_active" value="1" @if ($r->is_active == 1) checked @endif>
                                <label class="form-check-label" for="inlineRadio2">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" id="is_active" value="0" @if ($r->is_active == 0) checked @endif>
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-between">
                        <a href="{{ route('mitra') }}" class="btn btn-outline-secondary justify-content-start">Batal</a>
                        <button type="submit" class="btn btn-primary justify-content-end">Simpan</button>
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
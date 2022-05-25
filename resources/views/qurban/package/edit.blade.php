@extends('layouts.admin')

@section('title','Buat Page Baru')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Paket Qurban</h1>
    <a href="{{ route($route.'.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Kembali ke Tabel Paket</a>
</div>
<div class="row">
    <div class="col">
        <form id="form-insert-donasi" action="{{ route($route.'.update', ['id' => $row->id]) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Kategori</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="category" value="{{ $row->category }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Nama Paket</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="name" value="{{ $row->name }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Deskripsi</label>
                        <div class="col-9">
                            <textarea name="description" cols="30" rows="2" class="form-control">{{ $row->description }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Area Penyaluran</label>
                        <div class="col-3">
                            <select name="area" id="area" class="form-control">
                                <option value="">Semua</option>
                                <option value="indo" {{ $row->area == 'indo' ? 'selected' : '' }}>Indonesia</option>
                                <option value="palestina" {{ $row->area == 'palestina' ? 'selected' : '' }}>Palestina</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Harga Satuan</label>
                        <div class="col-9">
                            <input type="number" class="form-control text-right" name="price" value="{{ $row->price }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Foto Utama</label>
                        <div class="col-9">
                            <input type="file" class="form-control" name="banner">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Aktifkan?</label>
                        <div class="col-9">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" value="1" {{ $row->is_active == 1 ? 'checked' : '' }}>
                                <label class="form-check-label">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" value="0" {{ $row->is_active == 0 ? 'checked' : '' }}>
                                <label class="form-check-label">Jangan dulu</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-between">
                        <a href="{{ route($route.'.index') }}" class="btn btn-outline-secondary justify-content-start">Kembali</a>
                        <div class="ml-auto">
                            <button type="submit" class="btn btn-primary justify-content-end">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
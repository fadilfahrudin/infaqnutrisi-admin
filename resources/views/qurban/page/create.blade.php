@extends('layouts.admin')

@section('title','Buat Page Baru')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Landing Page Baru</h1>
    <a href="{{ route($route.'.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Kembali ke Landing Page</a>
</div>
<div class="row">
    <div class="col">
        <form id="form-insert-donasi" action="{{ route($route.'.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Judul Halaman</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Sub Judul</label>
                        <div class="col-9">
                            <textarea name="pitch" id="pitch" cols="30" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Deskripsi</label>
                        <div class="col-9">
                            <textarea name="description" id="description" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Foto Utama</label>
                        <div class="col-9">
                            <input type="file" class="form-control" name="photo">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Banner Home</label>
                        <div class="col-9">
                            <input type="file" class="form-control" name="banner_photo">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Publikasikan?</label>
                        <div class="col-9">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="published" value="1">
                                <label class="form-check-label">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="published" value="0" checked>
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

@section('headscript')
<script src='https://cdn.tiny.cloud/1/pzcmeuy3smps8e0hgmq8feyxgz7ev0j206s0299jj4ew67lu/tinymce/5/tinymce.min.js' referrerpolicy="origin">
</script>
<script>
  tinymce.init({
    selector: '#description',
    plugins: [
        'lists link image preview',
        'code fullscreen',
        'media table imagetools'
    ],
    toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
    images_upload_url: '{{ route("tinymce.upload") }}',
    image_class_list: [
        { title: 'Fluid Image', value: 'img-fluid' }
    ],
    relative_urls : false,
    remove_script_host : false,
    convert_urls : true,
  });
</script>
@endsection
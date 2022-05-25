@extends('layouts.admin')

@section('title', 'Buat Konten Baru')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Buat Konten Baru</h1>
    <a href="{{ url('/page') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Kembali ke Menu Page</a>
</div>
<div class="row">
    <div class="col">
        <form action="{{ route('page.create') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Title<sup class="text-danger">*</sup></label>
                        <div class="col-10">
                            <input type="text" name="title" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Link<sup class="text-danger">*</sup></label>
                        <div class="col-10">
                            <input type="text" name="slug" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Deskripsi<sup class="text-danger">*</sup></label>
                        <div class="col-10">
                            <textarea name="body" id="description" cols="30" rows="20" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Layout</label>
                        <div class="col-10">
                            <select name="layout" id="layout" class="form-control">
                                <option value="front">Front</option>
                                <option value="footer">Footer</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Publish halaman ini?</label>
                        <div class="col-10 mt-1">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="published" id="published" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="published" id="published" value="0">
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-between">
                        <a href="{{ route('page') }}" class="btn btn-outline-secondary justify-content-start">Batal</a>
                        <button type="submit" class="btn btn-primary justify-content-end">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection


@section('bodyscript')
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
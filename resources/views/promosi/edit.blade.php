@extends('layouts.admin')

@section('title'){{ $title }}@endsection

@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
    @if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ session()->get('error') }}    
    </div>
    @endif
   
</div>
<div class="row">
    <div class="col">
        <form id="form-insert-berita" action="{{ route($route.'.update', ['id' => $row->id]) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Judul Promosi</label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="name" id="name" value="{{ $row->name }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Penempatan</label>
                        <div class="col-4">
                            <select name="attribute1" id="attribute1" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="poster" {{ $row->attribute1 == 'poster' ? 'selected' : '' }}>Area Poster</option>
                                <option value="banner" {{ $row->attribute1 == 'banner' ? 'selected' : '' }}>Banner Carousel</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Link CTA</label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="attribute2" id="attribute2" value="{{ $row->attribute2 }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Sisipkan setelah</label>
                        <div class="col-10">
                            <select name="insert_after" id="insert_after" class="form-control">
                                <option value="">-- Pilih --</option>
                                @if (count($rows) > 0)
                                    @foreach ($rows as $r)
                                        <option value="{{ $r->ordering }}">{{ $r->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Upload Cover</label>
                        <div class="col-10">
                            <div class="custom-file">
                                <input type="file" name="photo" id="photo" required>
                                <br>
                                <small class="text-danger"><sup>*</sup>Max 2MB</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Deskripsi</label>
                        <div class="col-10">
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control">{{ $row->description }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Publikasikan?</label>
                        <div class="col-10 mt-1">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" value="1" {{ $row->is_active == 1 ? 'checked' : '' }} required>
                                <label class="form-check-label" for="inlineRadio1">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" value="0" {{ $row->is_active == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-between">
                        <a href="{{ route($route) }}" class="btn btn-outline-secondary justify-content-start">Batal</a>
                        <button type="submit" class="btn btn-primary justify-content-end">Submit</button>
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
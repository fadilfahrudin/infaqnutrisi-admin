@extends('layouts.admin')

@section('title','Edit Program')

@section('headscript')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Program</h1>
    <a href="{{ url('/program') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Kembali ke Program</a>
</div>
<form action="{{ route('program.update', ['id' => $r->id]) }}" method="post" enctype="multipart/form-data">
    @csrf
<div class="row">
    <div class="col-9">
        <div class="card">
            <div class="card-body">
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label">Judul Program</label>
                    <div class="col-10">
                        <input type="text" name="name" class="form-control" value="{{ $r->name }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label">Sub Judul</label>
                    <div class="col-10">
                        <input type="text" name="pitch" class="form-control" value="{{ $r->pitch }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label">Link</label>
                    <div class="col-10">
                        <input type="text" name="seo_link" class="form-control" value="{{ $r->seo_link }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label">Upload Foto Cover</label>
                    <div class="col-10">
                        @if (!empty($r->photo))
                            <img src="{{ strpos($r->photo, 'http') === false ? 'https://semangatbantu.com/img/'.$r->photo : $r->photo }}" width="200">
                        @endif
                        <div class="custom-file">
                            <input type="file" name="photo" id="photo">
                            <br>
                            <small class="text-danger"><sup>*</sup>Max 2MB</small>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label">Upload Banner</label>
                    <div class="col-10">
                        @if (!empty($r->banner_photo))
                            <img src="{{ strpos($r->banner_photo, 'http') === false ? 'https://semangatbantu.com/img/'.$r->banner_photo : $r->banner_photo }}" width="600">
                        @endif
                        <div class="custom-file">
                            <input type="file" name="banner_photo" id="banner_photo">
                            <br>
                            <small class="text-danger"><sup>*</sup>Max 2MB</small>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label">Tgl. Berakhir</label>
                    <div class="col-3">
                        <input type="date" name="expired_date" class="form-control" value="{{ $r->expired_date }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label">Target Donasi</label>
                    <div class="col-3">
                        <input type="number" name="target_amount" class="form-control text-right" onfocus="this.select()" value="{{ $r->target_amount }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-2 col-form-label">Deskripsi</label>
                    <div class="col-10">
                        <textarea name="description" id="description" cols="30" rows="20" class="form-control">{{ $r->description }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row d-flex justify-content-between">
                    <a href="{{ route('program') }}" class="btn btn-outline-secondary justify-content-start">Batal</a>
                    <button type="submit" class="btn btn-primary justify-content-end">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="">Pemilik Program</label>
                    <select name="created_by" class="form-control select2" required>
                        <option value="">-- Pilih --</option>
                        @if ($owners)
                            @foreach ($owners as $owner)
                                <option value="{{ $owner->id }}" {{ $owner->id == $r->created_by ? 'selected' : '' }}>{{ $owner->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <hr>
                <div class="form-group">
                    <label for="">Munculkan di Home dalam :</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="placement[]" value="slide_banner" {{ strpos($r->placement, 'slide_banner') !== false ? 'checked': '' }}>
                            <label class="form-check-label" for="inlineRadio1">Slide Banner</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="placement[]" value="pilihan" {{ strpos($r->placement, 'pilihan') !== false ? 'checked': '' }}>
                            <label class="form-check-label" for="inlineRadio2">Program Pilihan</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="placement[]" value="ramadhan" {{ strpos($r->placement, 'ramadhan') !== false ? 'checked': '' }}>
                            <label class="form-check-label" for="inlineRadio2">Program Ramadhan</label>
                        </div>

                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="">Urutkan setelah</label>
                    <select name="insert_after" class="form-control select2">
                        <option value="">-- Urutan Pertama --</option>
                        @if ($program)
                            @foreach ($program as $p)
                                <option value="{{ $p->urutan }}" {{ $p->urutan + 1 == $r->urutan ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <hr>
                <div class="form-group">
                    <label for="">Publikasikan?</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="published" value="1" {{ $r->published == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="inlineRadio1">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="published" value="0" {{ $r->published == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="inlineRadio2">Tidak</label>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="">Dipublikasikan Oleh</label>
                    <select name="published_by" id="published_by" class="form-control select2">
                        <option value="">-- Pilih --</option>
                        @if ($owners)
                            @foreach ($owners as $owner)
                                <option value="{{ $owner->id }}" {{ $r->published_by == $owner->id ? 'selected' : '' }}>{{ $owner->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection
@section('bodyscript')
<script src='https://cdn.tiny.cloud/1/pzcmeuy3smps8e0hgmq8feyxgz7ev0j206s0299jj4ew67lu/tinymce/5/tinymce.min.js' referrerpolicy="origin">
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
  $('.select2').select2();
</script>
@endsection
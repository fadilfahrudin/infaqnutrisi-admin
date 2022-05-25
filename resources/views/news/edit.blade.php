@extends('layouts.admin')

@section('title')
Edit Berita {{ $r->title }} @endsection

@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
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
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Berita Penyaluran</h1>
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session()->get('success') }}
    </div>
    @elseif(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session()->get('error') }}    
    </div>
    @endif
   
</div>
<div class="row">
    <div class="col">
        <form id="form-insert-berita" action="{{ route('news.update', ['id' => $r->id]) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Judul Berita</label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="title" id="title" value="{{ $r->title }}"  required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Judul SEO <span class="badge badge-info" data-toggle="popover" data-trigger="hover" title="Wajib huruf kecil semua dan spasi diganti dengan tanda -"><i class="fa fa-info"></i></button></label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="seo_title" id="seo_title" value="{{ $r->seo_title }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Deskripsi</label>
                        <div class="col-10">
                            <textarea name="description" id="description" cols="30" rows="20" class="form-control">{{ $r->description }}</textarea>
                        </div>
                    </div>
                    @if ($r->photo != null)
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Ganti Cover Berita</label>
                        <div class="col-10">
                            <img src="{{ url('/img/photo-berita/'.$r->photo) }}" alt="">
                            <div class="custom-file pt-2">
                                <input type="file" name="photo" id="photo" >
                                <br>
                                <small class="text-danger"><sup>*</sup>Max 2MB</small>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Upload Cover Berita <br><small class="text-info"><sup>*</sup>Optional</small></label>
                        <div class="col-10">
                            <div class="custom-file">
                                <input type="file" name="photo" id="photo" >
                                <br>
                                <small class="text-danger"><sup>*</sup>Max 2MB</small>
                            </div>
                        </div>
                    </div>   
                    @endif
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Nama Program</label>
                        <div class="col-10">
                            <select name="program_id" id="program_id" class="form-control disabled">
                                <option value="{{ $r->program->id }}">{{ $r->program->name }}</option>
                                @foreach($programs as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row row-cols-2">
                        <label for="" class="col-2 col-form-label">Penerima Manfaat</label>
                        <div class="row mx-0">
                            @foreach($category as $c)
                            <label for="" class="col-5 col-form-label my-2">{{ $c->master->name }}</label>
                            <div class="col-5 py-2">
                                <input type="number" class="form-control" name="amount" id="amount" value="{{ $c->amount }}" readonly>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Jumlah Penyaluran</label>
                        <div class="col-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control" name="total_distributed" value="{{ $r->total_distributed }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Tanggal Penyaluran</label>
                        <div class="col-10">
                            <input type="date" class="form-control" name="date_distributed" id="date_distributed" value="{{ date('Y-m-d',strtotime($r->date_distributed))}}" required>
                            
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Publish berita ini?</label>
                        <div class="col-10 mt-1">
                            @if ($r->published == 1)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="published" id="published" value="1" checked>
                                <label class="form-check-label" for="inlineRadio2">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="published" id="published" value="0">
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div>
                            @else
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="published" id="published" value="1">
                                <label class="form-check-label" for="inlineRadio2">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="published" id="published" value="0" checked>
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-between">
                        <a href="{{ route('news') }}" class="btn btn-outline-secondary justify-content-start">Batal</a>
                        <button id="btnSubmitBerita" type="submit" class="btn btn-primary justify-content-end">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
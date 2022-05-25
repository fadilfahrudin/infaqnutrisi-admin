@extends('layouts.admin')

@section('title', 'Berita Penyaluran')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Berita Penyaluran</h1>
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
        <form id="form-insert-berita" action="{{ route('news.insert') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Judul Berita</label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Judul SEO <span class="badge badge-info" data-toggle="popover" data-trigger="hover" title="Wajib huruf kecil semua dan spasi diganti dengan tanda -"><i class="fa fa-info"></i></button></label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="seo_title" id="seo_title" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Deskripsi</label>
                        <div class="col-10">
                            <textarea name="description" id="description" cols="30" rows="20" class="form-control"></textarea>
                        </div>
                    </div>
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
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Nama Program</label>
                        <div class="col-10">
                            <select name="program_id" id="program_id" class="form-control" required>
                                <option value="">-- Pilih Program --</option>
                                @foreach($programs as $p)
                                <option value="{{ $p->id }}" data-mitra="{{ $p->created_by}}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="mitra_id" id="mitra_id">
                        </div>
                    </div>
                    <div class="form-group row row-cols-2">
                        <label for="" class="col-12 col-form-label">Penerima Manfaat <br><small class="text-info"><sup>*</sup>Optional</small></label>
                        @foreach($category as $c)
                        <label for="" class="col-3 col-form-label my-2">{{ $c->name }}</label>
                        <div class="col-3 py-2">
                            <input type="number" class="form-control" name="amount[]" id="amount">
                            <input type="hidden" name="category_id[]" id="category_id" value="{{ $c->id }}">
                        </div>
                        @endforeach
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Jumlah Penyaluran</label>
                        <div class="col-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control text-right" name="total_distributed" value="0" onfocus="this.select()">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Tanggal Penyaluran</label>
                        <div class="col-4">
                            <input type="date" class="form-control" name="date_distributed" id="date_distributed" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Publish berita ini?</label>
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
                        <a href="{{ route('news') }}" class="btn btn-outline-secondary justify-content-start">Batal</a>
                        <button id="btnSubmitBerita" type="submit" class="btn btn-primary justify-content-end">Submit</button>
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
<script>
    $('#program_id').change(function () {
        $('#mitra_id').val($('option:selected', this).attr('data-mitra'));
    })
</script>
@endsection

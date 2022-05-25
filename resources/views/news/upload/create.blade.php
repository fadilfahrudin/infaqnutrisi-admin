@extends('layouts.admin')

@section('title')
 Upload Foto Berita @endsection

@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Upload Foto Berita</h1>
    @if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ session()->get('error') }}    
    </div>
    @endif
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">                
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {!! session()->get('success') !!}
    </div>
    @endif  
</div>
<div class="row">
    <div class="col">
        <form id="form-insert-berita" action="{{ route('news.upload.insert') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Upload Foto Untuk Konten Berita </label>
                        <div class="col-10">
                            <div class="custom-file">
                                <input type="file" name="photo" id="photo" >
                                <br>
                                <small class="text-danger"><sup>*</sup>Max 2MB</small>
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
<script src="{{ asset('assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>
<script>
CKFinder.config( { connectorPath: '/ckfinder/connector' } );
CKEDITOR.replace('description',{
    allowedContent: true,
    extraAllowedContent: 'div[class]',
    filebrowserBrowseUrl: "{{ route('ckfinder_browser', ['_token' => csrf_token()]) }}",
	filebrowserUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
});
</script>
@endsection

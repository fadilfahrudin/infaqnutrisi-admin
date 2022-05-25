@extends('layouts.admin')

@section('title')
Kajian @endsection

@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Kajian</h1>
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
        <form id="form-insert-berita" action="{{ route('kajian.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Nama Kajian</label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Link Kajian</label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="link" id="link" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Upload Cover Kajian</label>
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
                            <textarea name="description" id="description" cols="30" rows="20" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Tanggal Kajian</label>
                        <div class="col-10">
                            <input type="date" class="form-control" name="date_kajian" id="date_kajian" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Publish kajian ini?</label>
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
                        <a href="{{ route('kajian') }}" class="btn btn-outline-secondary justify-content-start">Batal</a>
                        <button type="submit" class="btn btn-primary justify-content-end">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection


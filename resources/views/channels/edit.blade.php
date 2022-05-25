@extends('layouts.admin')

@section('title')
Edit Channel {{ $r->name }} @endsection

@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Channel Pembayaran</h1>
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
        <form id="form-insert-channel" action="{{ route('channel.update', ['id' => $r->id]) }}" method="post">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Nama Pembayaran</label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="name" id="name" value="{{ $r->name }}"  required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Kode</label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="code" id="code" value="{{ $r->code }}"  required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Group Type</label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="group_type" id="group_type" value="{{ $r->group_type  }}" readonly>
                        </div>
                    </div>
                    @if ($r->group_type != 'bankreg')
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label">Vendor</label>
                            <div class="col-10">
                                <input type="text" class="form-control" name="vendor" id="vendor" value="{{ $r->vendor }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label">Vendor Var Code</label>
                            <div class="col-10">
                                <input type="text" class="form-control" name="vendor_var_code" id="vendor_var_code" value="{{ $r->vendor_var_code }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label">Vendor Var No</label>
                            <div class="col-10">
                                <input type="text" class="form-control" name="vendor_var_no" id="vendor_var_no" value="{{ $r->vendor_var_no }}">
                            </div>
                        </div>
                    @endif
                    @if ($r->group_type == 'bankreg')
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label">No Rekening</label>
                            <div class="col-10">
                                <input type="text" class="form-control" name="account_number" id="account_number" value="{{ $r->account_number }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label">Pemilik Rekining</label>
                            <div class="col-10">
                                <input type="text" class="form-control" name="account_name" id="account_name" value="{{ $r->account_name }}">
                            </div>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Munculkan channel pembayaran ini?</label>
                        <div class="col-10 mt-1">
                            
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" id="is_active" value="1" @if ($r->is_active == 1) checked @endif>
                                <label class="form-check-label" for="inlineRadio2">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" id="is_active" value="0" @if ($r->is_active == 0) checked @endif>
                                <label class="form-check-label" for="inlineRadio2">Tidak</label>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-between">
                        <a href="{{ route('channel') }}" class="btn btn-outline-secondary justify-content-start">Batal</a>
                        <button id="btnSubmitChannel" type="submit" class="btn btn-primary justify-content-end">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('bodyscript')
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

@extends('layouts.admin')

@section('title')
Input Channel Pembayaran Baru @endsection

@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Input Channel Pembayaran</h1>
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
        <form id="form-insert-channel" action="{{ route('channel.insert') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Nama Pembayaran</label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Kode Pembayaran</label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="code" id="code" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Group Type</label>
                        <div class="col-10">
                            <select name="group_type" id="group_type" class="form-control" required>
                                <option value="">-- Pilih Program --</option>
                                <option value="bankreg" data-type="reguler">bankreg</option>
                                <option value="va" data-type="vndr">va</option>
                                <option value="emoney" data-type="vndr">emoney</option>
                                <option value="xndva" data-type="vndr">xndva</option>
                            </select>
                            <input type="hidden" name="mitra_id" id="mitra_id">
                        </div>
                    </div>
                    <div id="vndr" style="display:none">
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label">Vendor</label>
                            <div class="col-10">
                                <input type="text" class="form-control" name="vendor" id="vendor" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label">Vendor Var Code</label>
                            <div class="col-10">
                                <input type="text" class="form-control" name="vendor_var_code" id="vendor_var_code" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label">Vendor Var No</label>
                            <div class="col-10">
                                <input type="text" class="form-control" name="vendor_var_no" id="vendor_var_no" >
                            </div>
                        </div>
                    </div>
                    <div id="reguler" style="display:none">
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label">No Rekening</label>
                            <div class="col-10">
                                <input type="text" class="form-control" name="account_number" id="account_number" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-2 col-form-label">Pemilik Rekining</label>
                            <div class="col-10">
                                <input type="text" class="form-control" name="account_name" id="account_name" >
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Munculkan channel pembayaran ini?</label>
                        <div class="col-10 mt-1">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" id="is_active" value="1" required>
                                <label class="form-check-label" for="inlineRadio2">Ya</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" id="is_active" value="0">
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
console.log();

$('#group_type').change(function () {
    const select_op = $('option:selected', this).attr('data-type');
    if (select_op == 'reguler') {
        $('#' + $('option:selected', this).attr('data-type')).show();
        $('#vndr').hide();
        $('#vendor').val('');
        $('#vendor_var_code').val('');
        $('#vendor_var_no').val('');
        console.log('AAAAA');
    } else {
        $('#' + $('option:selected', this).attr('data-type')).show();
        $('#reguler').hide();
        $('#account_number').val('');
        $('#account_name').val('');
        console.log('BBBBB');
    }
})
</script>
@endsection

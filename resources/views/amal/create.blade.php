@extends('layouts.admin')

@section('title')
Donasi Baru @endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Kotak Amal <small>Donasi Baru</small></h1>
    <a href="{{ url('/amal') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-chevron-left fa-sm text-white-50"></i> Kembali</a>
</div>
<div class="row">
    <div class="col">
        <form action="{{ route('amal.store') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">No. Pembayaran</label>
                        <div class="col-4">
                            <input type="text" name="transaction_code" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Nama Donatur <span class="text-danger">*</span></label>
                        <div class="col-10">
                            <input type="text" name="name" class="form-control" required>
                            <div class="form-check mb-3">
                                <input type="checkbox" id="isHideName" class="form-check-input">
                                <input type="hidden" name="isHidden" id="isHidden" value="0">
                                <label for="" class="form-check-label">Sembunyikan nama (Hamba Allah)</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">No. Telepon</label>
                        <div class="col-4">
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">E-Mail</label>
                        <div class="col-6">
                            <input type="text" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                        <div class="col-4">
                            <select name="payment_channel_id" id="payment_channel_id" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            @foreach($channels as $c)
                                <option value="{{ $c->id }}" data-group="{{ $c->group_type }}" data-code="{{ $c->code }}" data-no="{{ $c->account_number }}">{{ $c->name }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Nomor Rekening</label>
                        <div class="col-4">
                            <input type="text" name="payment_account_number" id="payment_account_number" class="form-control" placeholder="Nomor Rekening">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Nominal Transfer (Rp)</label>
                        <div class="col-4">
                            <input type="number" name="amount_final" id="amount_final" class="form-control text-right">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2 col-form-label">Tanggal Pembayaran</label>
                        <div class="col-3">
                            <input type="datetime-local" name="payment_finished" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-between">
                        <a href="{{ route('amal') }}" class="btn btn-outline-secondary justify-content-start">Batal</a>
                        <button type="submit" class="btn btn-primary justify-content-end">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('bodyscript')
<script type="text/javascript">
$(function(){
    $('#isHideName').change(function() {
        if($(this).is(':checked')) $('#isHidden').val(1);
        else $('#isHidden').val(0);
    });
    $('#payment_channel_id').change(function(){
        var lmn = $('option:selected', this).attr('data-no');
        $('#payment_account_number').val(lmn);
    });
});
</script>
@endsection
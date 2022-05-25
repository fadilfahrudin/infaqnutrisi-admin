@extends('layouts.admin')

@section('title')
Input Donasi @endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Input Donasi</h1>
    <a href="{{ url('/donasi') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Kembali ke Donasi</a>
</div>
<div class="row">
    <div class="col">
        <div class="alert alert-danger alert-dismissable fade show" role="alert">
            <strong>PERHATIAN</strong> Form ini hanya dipakai untuk transaksi yang tidak tercatat oleh sistem secara otomatis saja.
        </div>
        <form id="form-insert-donasi" action="{{ route('donasi.insert') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">No. Invoice</label>
                        <div class="col-4">
                            <input type="text" class="form-control" name="transaction_code" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Donasi untuk Program</label>
                        <div class="col-9">
                            <select name="program_id" id="program_id" class="form-control" required>
                                <option value="">-- Pilih Program --</option>
                                @foreach($programs as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Nama Donatur</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="funder_name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">No. Telepon</label>
                        <div class="col-4">
                            <input type="text" class="form-control" name="funder_phone" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">E-Mail</label>
                        <div class="col-4">
                            <input type="text" class="form-control" name="funder_email">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Jumlah</label>
                        <div class="col-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control text-right" name="amount_final" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Bayar via</label>
                        <div class="col-6">
                            <select name="payment_channel_id" id="payment_channel_id" class="form-control" required>
                                <option value="">-- Pilih Metode --</option>
                                @foreach($channels as $p)
                                <option value="{{ $p->id }}" data-account-number="{{ $p->account_number }}">{{ !empty($p->account_number) ? $p->name . ' No. ' . $p->account_number : $p->name }} {{ !empty($p->vendor) ? '(' . ucwords($p->vendor) . ')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">No. Rekening</label>
                        <div class="col-4">
                            <input type="text" class="form-control" name="payment_account_number" id="payment_account_number" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Tanggal pembayaran</label>
                        <div class="col-3">
                            <input type="date" class="form-control" name="payment_finished" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-between">
                        <a href="{{ route('donasi') }}" class="btn btn-outline-secondary justify-content-start">Kembali</a>
                        <div class="ml-auto">
                            <button id="btnSubmitDonasi" type="submit" style="display:none">Simpan</button>
                            <button type="button" class="btn btn-primary justify-content-end" data-toggle="modal" data-target="#confirmPaymentModal">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@include('components.confirmPaymentModal')
@endsection

@section('bodyscript')
<script>
$(function() {
    $('#payment_channel_id').change(function() {
        var accno = $('#payment_channel_id option:selected').attr('data-account-number');
        $('#payment_account_number').val(accno);
    });
});
</script>
@endsection
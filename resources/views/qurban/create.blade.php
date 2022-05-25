@extends('layouts.admin')

@section('title', 'Form Input Transaksi Qurban')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Form Input Transaksi Qurban</h1>
    <a href="{{ route('qurban.transaction.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Kembali</a>
</div>
<div class="row">
    <div class="col">
        <div class="alert alert-danger alert-dismissable fade show" role="alert">
            <strong>PERHATIAN</strong> Form ini hanya dipakai untuk transaksi yang tidak tercatat oleh sistem secara otomatis saja.
        </div>
        <form id="form-insert-donasi" action="{{ route('qurban.transaction.store') }}" method="post">
            @csrf
            <input type="hidden" id="ccart">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">No. Invoice</label>
                        <div class="col-4">
                            <input type="text" class="form-control" name="transaction_code" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Kode Referal Fundraiser</label>
                        <div class="col-3">
                            <input type="text" class="form-control" name="refcode">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Nama Pekurban</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="customer_name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">No. Telepon</label>
                        <div class="col-4">
                            <input type="text" class="form-control" name="customer_phone" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">E-Mail</label>
                        <div class="col-4">
                            <input type="text" class="form-control" name="customer_email">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card my-3">
                <div class="card-body">
                    <span class="font-weight-bold">Detail Pesanan</span>
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th width="20px">No.</th><th width="250px">Paket</th><th width="100px">Harga<br>(Rp)</th><th width="100px">QTY</th><th width="100px">Subtotal<br>(Rp)</th><th>Atas Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($packages))
                            @php $no = 1; @endphp
                                @foreach($packages as $p)
                                <tr>
                                    <td class="text-right">{{ $no }}</td>
                                    <td>{{ $p->name }}</td>
                                    <td class="text-right">{{ number_format($p->price, 0, ',', '.') }}</td>
                                    <td><input type="number" name="qty-{{ $p->id }}" id="qty-{{ $p->id }}" class="form-control text-right" value="0" data-id="{{ $p->id }}" data-name="{{ $p->name }}" data-price="{{ $p->price }}" onchange="updateOrder(this.value, {{ $p->id }})"></td>
                                    <td class="text-right"><span id="subtotal-{{ $p->id }}">0</span></td>
                                    <td><input type="text" class="form-control" name="notes-{{ $p->id }}"></td>
                                </tr>
                                @php $no++; @endphp
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Total (Rp)</label>
                        <div class="col-2">
                            <input type="text" class="form-control text-right" name="total_amount" id="total_amount" value="0" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Kode Unik Transfer</label>
                        <div class="col-2">
                            <input type="text" class="form-control text-right" name="transfer_code" id="transfer_code">
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
                            <input type="text" class="form-control" name="payment_channel_number" id="payment_channel_number" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Tanggal pembayaran</label>
                        <div class="col-2">
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
const updateOrder = (qty, pid) => {
    const price = $('input#qty-'+pid).attr('data-price');
    const name = $('input#qty-'+pid).attr('data-name');
    const subtotal = qty*price;
    const newOrder = { id: pid, name: name, qty: qty, price: price, subtotal: subtotal };
    var orders = [];
    var ccart = $('#ccart').val();
    if(ccart.length == 0) {
        orders.push(newOrder);
    } else {
        ccart = JSON.parse(ccart);
        for(var i=0; i<ccart.length; i++) {
            if(ccart[i].id == pid) {
                ccart.splice(i, 1);
            }
        }
        if(qty > 0) {
            ccart.push(newOrder);
        }
        orders = ccart;
    }
    $('#subtotal-'+pid).text(formatNumber(subtotal));
    $('#ccart').val(JSON.stringify(orders));
    getTotal();
}
const getTotal = () => {
    var ccart = $('#ccart').val();
    ccart = JSON.parse(ccart);
    var total = 0;
    for(var i=0; i<ccart.length; i++) {
        total += ccart[i].subtotal;
    }
    $('#total_amount').val(total);
}
const formatNumber = (x) => {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
$(function() {
    $('#payment_channel_id').change(function() {
        var accno = $('#payment_channel_id option:selected').attr('data-account-number');
        $('#payment_account_number').val(accno);
    });
});
</script>
@endsection
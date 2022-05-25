@extends('layouts.admin')

@section('title')
Detail Donasi @endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Kotak Amal</h1>
    <a href="{{ url('/amal') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Kembali ke Kotak Amal</a>
</div>
<div class="row">
    <div class="col">
        <form id="form-update-donasi" action="{{ route('donasi.update', ['id' => $r->id]) }}" method="post">
            @csrf
            <input type="hidden" name="next-status" value="{{ $r->status == 'pending' ? 'done' : '' }}">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center">Informasi Donasi<br><small>No. Pembayaran : {{ $r->transaction_code }}</small></h3>
                    <div class="row mt-5">
                        <div class="col">
                            <span>Tgl. Invoice : {{ date('d M Y H:i:s', strtotime($r->payment_initiated)) }}</span>
                        </div>
                        <div class="col text-right">
                            @php $color = $r->status == 'pending' ? ' text-gray-800' : ($r->status == 'done' ? ' text-success' : ' text-warning'); @endphp
                            <span>Status :</span> <span class="text-lg text-uppercase{{ $color }}">{{ $r->status }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Nama Donatur</label>
                        <div class="col-9">
                            <input type="text" class="form-control" value="{{ $r->funder_name }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">No. Telepon</label>
                        <div class="col-9">
                            <input type="text" class="form-control" value="{{ $r->funder_phone }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">E-Mail</label>
                        <div class="col-9">
                            <input type="text" class="form-control" value="{{ $r->funder_email }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Jumlah</label>
                        <div class="col-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right" value="{{ number_format($r->amount_final,0,',','.') }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Bayar via</label>
                        <div class="col-9">
                            <input type="text" class="form-control" value="{{ $r->payment_channel_name . ' No. ' . $r->payment_account_number }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Tanggal Pembayaran</label>
                        <div class="col-9">
                            <input type="text" class="form-control" value="{{ date('d M Y H:i:s', strtotime($r->payment_finished)) }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Tanggal Entri</label>
                        <div class="col-9">
                            <input type="text" class="form-control" value="{{ date('d M Y H:i:s', strtotime($r->created_at)) }}" readonly>
                        </div>
                    </div>
                    @if(!empty($r->attachment))
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Konfirmasi Pembayaran</label>
                        <div class="col-9">
                            <img src="https://semangatbantu.com/img/konfirmasi/{{ $r->attachment }}" alt="" class="img-fluid">
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-between">
                        <a href="{{ route('amal') }}" class="btn btn-outline-secondary justify-content-start">Kembali</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
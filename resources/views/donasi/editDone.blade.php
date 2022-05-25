@extends('layouts.admin')

@section('title')
Detail Donasi @endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Donasi</h1>
    <a href="{{ url('/donasi') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Kembali ke Donasi</a>
</div>
<div class="row">
    <div class="col">
        <form id="form-update-donasi" action="{{ route('donasi.done.update', ['id' => $r->id]) }}" method="post">
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
                        <label for="" class="col-3 col-form-label">Donasi untuk Program</label>
                        <div class="col-9">
                            <select name="program_id" id="program_id" class="form-control" required>
                                <option value="">-- Pilih Program --</option>
                                @foreach($programs as $p)
                                @php $selected = $r->program_id == $p->id ? 'selected' : ' ' @endphp
                                <option value="{{ $p->id }}" {{ $selected }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Nama Donatur</label>
                        <div class="col-9">
                            <input type="text" class="form-control" value="{{ $r->funder_name }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">No. Telepon</label>
                        <div class="col-9">
                            <input type="text" class="form-control" value="{{ $r->funder_phone }}" name="funder_phone">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">E-Mail</label>
                        <div class="col-9">
                            <input type="text" class="form-control" value="{{ $r->funder_email }}" name="funder_email">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Kode Referal Fundraiser</label>
                        <div class="col-9">
                            <input type="text" class="form-control" value="{{ $r->refcode }}" name="refcode">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Jumlah</label>
                        <div class="col-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                @php $amount = number_format($r->amount_final,0,',','.') @endphp
                                <input type="text" class="form-control text-right" value="{{ $amount }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Bayar via</label>
                        <div class="col-9">
                            <select name="payment_channel_id" id="payment_channel_id" class="form-control" required>
                                <option value="">-- Pilih Metode --</option>
                                @foreach($channels as $c)
                                @php $selected = $r->payment_channel_id == $c->id ? 'selected' : ' ' @endphp
                                <option value="{{ $c->id }}" {{ $selected }}>{{ $c->name. ' No. ' .$c->account_number}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Batas akhir pembayaran</label>
                        <div class="col-9">
                            <input type="text" class="form-control" value="{{ date('d M Y H:i:s', strtotime($r->payment_expired)) }}" readonly>
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
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Tgl. Transfer</label>
                        <div class="col-3">
                            <input type="date" name="payment_finished" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Jumlah yang ditransfer</label>
                        <div class="col-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" name="accepted_amount" class="form-control text-right" value="{{ $r->amount_final }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-between">
                        <a href="{{ route('donasi') }}" class="btn btn-outline-secondary justify-content-start">Kembali</a>
                        <div class="ml-auto">
                            @if ($r->payment_channel_type === 'bankreg')
                                @php $dataDescription = 'Donasi dari ' . $r->funder_name . ' sebesar Rp ' . $amount. ' melalui ' . $r->payment_channel_name; 
                                    $dataDescription .= !empty($r->transaction_code) ? '. Invoice No. ' . $r->transaction_code : '';
                                @endphp
                                <a href="javascript:;" class="btn btn-danger" onclick="confirmDelete({{ $r->id }}, '{{ $dataDescription }}')">Hapus Pembayaran</a>
                            @endif
                            @if ($r->status === 'done')
                                <button type="submit" class="btn btn-primary">Update Data</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <form action="{{ route('donasi.amal') }}" id="convertPaymentForm" method="post">
            @csrf
            <input type="hidden" name="donation_id" value="{{ $r->id }}">
            <input type="hidden" id="amount_transfered" name="amount_transfered">
        </form>
    </div>
</div>
@include('components.confirmPaymentModal')
@include('components.convertPaymentModal')
@include('components.confirmDeleteModal')
@endsection
@section('bodyscript')
<script>
    function confirmDelete(dataId, dataDescription) {
        $('#deleteForm').attr("action", "{{ route('donasi.delete') }}");
        $('#data_description').text(dataDescription);
        $('#data_id').val(dataId);
        $('#confirmDeleteModal').modal('show');
    }
</script>
@endsection
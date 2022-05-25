@extends('layouts.admin')

@section('title', 'Detail Donasi')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Donasi</h1>
    <a href="{{ url('/donasi') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Kembali ke Donasi</a>
</div>
<div class="row">
    <div class="col">
        <form id="form-update-donasi" action="{{ route('doa.donasi.update', ['id' => $r->id]) }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ isset($dr) && (count($dr) > 0) ? $dr->id : '' }}">
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
                            <input type="text" class="form-control" value="{{ $r->program->name }}" readonly>
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
                        <label for="" class="col-3 col-form-label">Pesan / Doa</label>
                        <div class="col-9">
                            <textarea name="" id="" cols="30" rows="5" class="form-control" readonly>{{ $r->funder_message }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Balasan</label>
                        <div class="col-9">
                            <textarea name="message" id="balasan" cols="30" rows="5" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Publikasikan?</label>
                        <div class="col-9">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="is_published" value="1">
                                <label for="" class="form-check-label">Ya</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="is_published" value="0" checked>
                                <label for="" class="form-check-label">Jangan Dulu</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-between">
                        <a href="{{ route('doa.donasi') }}" class="btn btn-outline-secondary justify-content-start">Kembali</a>
                        <div class="ml-auto">
                            <button type="submit" class="btn btn-primary justify-content-end">Kirim Balasan</button>
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
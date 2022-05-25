@extends('layouts.admin')

@section('title')
Pencairan Dana @endsection

<meta name="csrf-token" content="{{ csrf_token() }}">
@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Pencairan</h1>
    @if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session()->get('error') }}    
    </div>
    @endif
   
</div>
<div class="row">
    <div class="col">
    <form id="form-insert-berita" action="{{route('withdraw.insert')}}" method="post">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Nama Program</label>
                        <div class="col-9">
                            <select name="program_id" id="program_id" class="form-control" required>
                                <option value="">-- Pilih Program --</option>
                                @foreach($programs as $p)
                                <option value="{{ $p->id }}" data-mitra="{{$p->owner->name}}" data-mitraId="{{ $p->created_by }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Nama Mitra</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="mitra_name" id="mitra_name" readonly>
                            <input type="hidden" name="mitra_id" id="mitra_id">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Periode Donasi</label>
                        <div class="col-9 d-flex justify-content input-daterange">
                            <div class="col pl-0">
                                <input type="date" class="form-control" name="donation_start_date" id="donation_start_date" required>
                            </div>
                            <span class="col-form-label">s/d</span>
                            <div class="col pr-0">
                                <input type="date" class="form-control" name="donation_end_date" id="donation_end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Donasi Terkumpul</label>
                        <div class="col-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                            <input type="number" class="form-control text-right" name="donation_collected" id="donation_collected" placeholder="0" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Infaq Pengembangan Teknologi</label>
                        <div class="col-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control text-right" name="infaq_pengembangan" id="infaq_pengembangan" placeholder="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Biaya Iklan</label>
                        <div class="col-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control text-right" name="biaya_iklan" id="biaya_iklan" placeholder="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Payment Gateway</label>
                        <div class="col-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control text-right" name="biaya_payment_gateway" id="biaya_payment_gateway" placeholder="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Dana yang bisa dicairkan</label>
                        <div class="col-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control text-right" name="payable_amount" id="payable_amount" value="0" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-12 col-form-label">Catatan</label>
                        <div class="col-12">
                            <textarea class="form-control" name="details" id="details" rows=5 required></textarea>                               
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-between">
                        <a href="{{ route('withdraw') }}" class="btn btn-outline-secondary justify-content-start">Batal</a>
                        <button id="btnSubmitPencairan" type="submit" class="btn btn-primary justify-content-end">Submit</button>
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
$('#program_id').change(function() {
        $('#mitra_name').val($('option:selected', this).attr('data-mitra'));
        $('#mitra_id').val($('option:selected', this).attr('data-mitraId'));
        getCollected($(this).val(),$('#donation_start_date').val(),$('#donation_end_date').val());   
});

$('#donation_start_date').change(function () {
    // a();
    $.get('{{url('/withdraw/valDate')}}',{
        _token: $('meta[name="csrf-token"]').attr('content'),
        program_id: $('#program_id').val(),
        tanggal: $(this).val(),
        tipe: 'start'
    }, function (tgl) {
        console.log(tgl);
        if (tgl == 'true') {
            alert('Periode Awal sudah ada');
            $('#donation_start_date').val('');
        } else {
            getCollected($('#program_id').val(),$(this).val(),$('#donation_end_date').val());
        } 
    });
    // getCollected($('#program_id').val(),$(this).val(),$('#donation_end_date').val());
});

$('#donation_end_date').change(function () {
    $.get('{{url('/withdraw/valDate')}}',{
        _token: $('meta[name="csrf-token"]').attr('content'),
        program_id: $('#program_id').val(),
        tanggal: $(this).val(),
        tipe: 'end'
    }, function (tgl) {
        console.log(tgl);
        if (tgl == 'true') {
            alert('Periode Akhir sudah ada');
            $('#donation_end_date').val('');
        } else {
            getCollected($('#program_id').val(),$('#donation_start_date').val(),$(this).val());
        }   
    });
    // getCollected($('#program_id').val(),$('#donation_start_date').val(),$(this).val());
});


function getCollected(program_id, start_date, end_date) {
    $.get('{{url('/donasi/getCollected')}}', {
        _token: $('meta[name="csrf-token"]').attr('content'),
        program_id: program_id,
        start_date: start_date,
        end_date: end_date
    }, function (collected) {
        $('#donation_collected').val(collected);
        var infaq = 0.05 * parseInt(collected);
        $('#infaq_pengembangan').val(parseInt(infaq));
    });
}

$('#donation_collected').change(function () {
    getPencairan($(this).val(),$('#infaq_pengembangan').val(),$('#biaya_iklan').val(),$('#biaya_payment_gateway').val());
});

$('#infaq_pengembangan').change(function () {
    getPencairan($('#donation_collected').val(),$(this).val(),$('#biaya_iklan').val(),$('#biaya_payment_gateway').val());
});

$('#biaya_iklan').keyup(function () {
    getPencairan($('#donation_collected').val(),$('#infaq_pengembangan').val(),$(this).val(),$('#biaya_payment_gateway').val());
});

$('#biaya_payment_gateway').keyup(function () {
    getPencairan($('#donation_collected').val(),$('#infaq_pengembangan').val(),$('#biaya_iklan').val(),$(this).val());
});

function getPencairan(donation_collected, infaq_pengembangan, biaya_iklan, biaya_payment_gateway) {
    var pencairan = parseInt(donation_collected) - (parseInt(infaq_pengembangan) + parseInt(biaya_iklan) + parseInt(biaya_payment_gateway));

    $('#payable_amount').val(pencairan);
}



</script>
@endsection

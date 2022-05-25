@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Laporan</h1>
    <a href="{{ url('/') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Kembali ke Dashboard</a>
</div>
<div class="row">
    <div class="col">
        <form id="form-generate-report" action="{{ route('report.export') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="form-inline">
                        <label for="">Periode Donasi</label>
                        <input type="date" class="form-control ml-3" name="start_date" id="donation_start_date" required>
                        <label for="" class="ml-3">s/d</label>
                        <input type="date" class="form-control ml-3" name="end_date" id="donation_end_date" required>
                        <label for="" class="ml-3">Status Transaksi</label>
                        <select name="status" id="status" class="form-control ml-3">
                            <option value="done">DONE</option>
                            <option value="pending">PENDING</option>
                            <option value="moved">MOVED</option>
                        </select>
                        <button id="btnPreview" type="button" class="btn btn-primary ml-3">Tampilkan Data</button>
                        <button id="btnGenerate" type="submit" class="btn btn-success ml-2">Export to Excel</button>
                    </div>
                </div>
                <div id="previewData" class="card-body px-0"></div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('bodyscript')
<script src="{{ asset('assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('#btnPreview').click(function(){
            $(this).attr('disabled', 'disabled');
            $('#previewData').html('<div class="container mt-5"><div class="text-center"><i class="fas fa-spinner fa-spin fa-3x"></i></div></div>');
            $.post("{{ route('report.preview') }}", {
                _token: $('form#form-generate-report input[name="_token"]').val(),
                start_date: $('#donation_start_date').val(),
                end_date: $('#donation_end_date').val(),
                status: $('#status').val()
            }, function(data) {
                if(data && data.success) {
                    $('#btnPreview').removeAttr('disabled');
                    $('#previewData').html(data.html);
                    console.log('datanya ', data);
                }
            });
        });
        // $('#btnGenerate').click(function(){
        //     $(this).attr('disabled', 'disabled');
        //     $('#previewData').html('<div class="container mt-5"><div class="text-center"><i class="fas fa-spinner fa-spin fa-3x"></i></div></div>');
        //     $('#form-generate-report').submit();
        // });
    });
</script>
@endsection
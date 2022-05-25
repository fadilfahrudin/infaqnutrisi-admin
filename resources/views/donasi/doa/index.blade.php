@extends('layouts.admin')

@section('title', 'Doa Donatur')

@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Doa Donatur</h1>
    <a href="{{ route('donasi.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Input Donasi Baru</a>
</div>
<div class="row">
    <div class="col">
        <form id="filter-donation" action="{{ route('donasi.filter') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="form-inline">
                        <label for="">Program</label>
                        <select name="program_id" id="program_id" class="form-control ml-3">
                            <option value="">-- Pilih Program --</option>
                            @foreach ($programs as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        <button id="btnPreview" type="button" class="btn btn-primary ml-3">Tampilkan Data</button>
                    </div>
                </div>
                <div id="previewData" class="card-body"></div>
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
            $.post("{{ route('doa.donasi.filter') }}", {
                _token: $('form#filter-donation input[name="_token"]').val(),
                program_id: $('#program_id').val()
            }, function(data) {
                if(data && data.success) {
                    $('#btnPreview').removeAttr('disabled');
                    $('#previewData').html(data.html);

                    $('#datatable').DataTable({
                        "order": [[ 1, "desc" ]]
                    });
                }
            });
        });
    });
</script>
@endsection
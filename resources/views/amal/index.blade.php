@extends('layouts.admin')

@section('title')
Kotak Amal @endsection

@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Kotak Amal</h1>
    <a href="{{ route('amal.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Input Donasi Baru</a>
</div>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session()->get('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>
                @endif
                @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissable fade show" role="alert">
                {{ session()->get('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                @endif
                <table id="datatable" class="table table-bordered table-sm table-hover table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No. Pembayaran</th><th>Tgl. Invoice</th><th>Tgl. Bayar</th><th>Nama</th><th>Jumlah</th><th>Bayar via</th><th>Status</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $r)
                        @php $amount = !empty($r->amount_final) ? number_format($r->amount_final,0,',','.') : '&infin;'; @endphp
                        @php $status = $r->status == 'pending' ? ' badge-secondary' : ($r->status == 'done' ? ' badge-success' : ' badge-warning'); @endphp
                        @php $payInit = !empty($r->payment_initiated) ? date('Y/m/d', strtotime($r->payment_initiated)) : ''; @endphp
                        @php $payFinish = !empty($r->payment_finished) ? date('Y/m/d', strtotime($r->payment_finished)) : ''; @endphp
                        <tr>
                            <td>{{ $r->transaction_code }}</td>
                            <td>{{ $payInit }}</td>
                            <td>{{ $payFinish }}</td>
                            <td>{{ $r->funder_name }}</td>
                            <td class="text-right">{!! $amount !!}</td>
                            <td class="text-center">{{ $r->payment_channel_name }}</td>
                            <td class="text-center"><span class="badge{{ $status }}">{{ $r->status }}</span></td>
                            <td>
                                <a href="{{ url('/amal/'.$r->id.'/detail') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>&nbsp;Detail
                                </a>
                                @php $dataDescription = 'Donasi dari ' . $r->funder_name . ' sebesar Rp ' . $amount. ' melalui ' . $r->payment_channel_name; 
                                    $dataDescription .= !empty($r->transaction_code) ? '. Invoice No. ' . $r->transaction_code : '';
                                @endphp
                                <a href="javascript:;" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $r->id }}, '{{ $dataDescription }}')">
                                    <i class="fas fa-trash"></i>&nbsp;Hapus
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('components.confirmDeleteModal')
@endsection

@section('bodyscript')
<script src="{{ asset('assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('#datatable').DataTable({
            "order": [[ 1, "desc" ]]
        });
    });
    function confirmDelete(dataId, dataDescription) {
        $('#deleteForm').attr("action", "{{ route('amal.delete') }}");
        $('#data_description').text(dataDescription);
        $('#data_id').val(dataId);
        $('#confirmDeleteModal').modal('show');
    }
</script>
@endsection
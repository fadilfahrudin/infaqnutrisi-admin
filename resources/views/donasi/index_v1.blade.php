@extends('layouts.admin')

@section('title')
Donasi @endsection

@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Donasi</h1>
    <a href="{{ route('donasi.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Input Donasi Baru</a>
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
                            <th>No. Pembayaran</th><th>Tgl. Invoice</th><th>Tgl. Bayar</th><th>Program</th><th>Nama</th><th>Jumlah</th><th>Bayar via</th><th>Status</th><th>Aksi</th>
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
                            <td>{{ $r->program->name }}</td>
                            <td>{{ $r->funder_name }}</td>
                            <td class="text-right">{!! $amount !!}</td>
                            <td class="text-center">{{ $r->payment_channel_name }}</td>
                            <td class="text-center"><span class="badge{{ $status }}">{{ $r->status }}</span></td>
                            <td>
                                @if ($r->payment_channel_type == 'bankreg' && $r->status == 'done')
                                <a href="{{ url('/donasi/'.$r->id.'/edit') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>&nbsp;Edit
                                </a>    
                                @else
                                <a href="{{ url('/donasi/'.$r->id.'/detail') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>&nbsp;Detail
                                </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
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
</script>
@endsection
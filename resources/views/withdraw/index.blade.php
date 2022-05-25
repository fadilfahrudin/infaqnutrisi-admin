@extends('layouts.admin')

@section('title')
Pencairan @endsection

@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Pencairan</h1>
    <a href="{{ route('withdraw.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Input Pencairan</a>
</div>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ session()->get('success') }}
                </div>
                @elseif(session()->has('cancel'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ session()->get('cancel') }}
                </div>
                @endif
                <table id="datatable" class="table table-bordered table-sm table-hover table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tgl. Request</th><th>Nama Program</th><th>Donasi Terkumpul</th><th>Donasi Yang Dicairkan</th><th>Status</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $r)
                        @php $payableAmount = !empty($r->payable_amount) ? number_format($r->payable_amount,0,',','.') : 0; @endphp
                        @php $donationCollected = !empty($r->donation_collected) ? number_format($r->donation_collected,0,',','.') : 0; @endphp
                        @php $badge = $r->status == 'new' ? ' badge-primary' : ($r->status == 'submitted' ? ' badge-warning' : ($r->status == 'approved' ? ' badge-success' : ' badge-danger')); @endphp
                        @php $reqDate = !empty($r->request_date) ? date('Y/m/d', strtotime($r->request_date)) : ''; @endphp
                        <tr>
                            <td>{{ $reqDate }}</td>
                            <td>{{$r->program->name}}</td>
                            <td class="text-right">{!! $donationCollected !!}</td>
                            <td class="text-right">{!! $payableAmount !!}</td>
                            <td class="text-center"><span class="badge {{ $badge }}">{{ $r->status }}</span></td>
                            <td>
                                <a href="{{ url('/withdraw/'.$r->id.'/detail') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>&nbsp;Detail
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
@endsection

@section('bodyscript')
<script src="{{ asset('assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('#datatable').DataTable();
    });
</script>

@endsection
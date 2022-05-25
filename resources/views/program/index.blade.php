@extends('layouts.admin')

@section('title')
    Program
@endsection

@section('headscript')
    <link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Program</h1>
        <a href="{{ url('/program/create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Buat Program</a>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table id="datatable" class="table table-bordered table-sm table-hover table-striped w-100"
                        cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Pemilik</th>
                                <th>Target</th>
                                <th>Batas Waktu</th>
                                <th width="7%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $r)
                                @php $target = !empty($r->target_amount) ? number_format($r->target_amount,0,',','.') : '&infin;'; @endphp
                                @php $expdate = !empty($r->expired_date) ? date('d-m-Y', strtotime($r->expired_date)) : '&infin;'; @endphp
                                <tr>
                                    <td>{{ $r->name }}</td>
                                    <td>{{ $r->owner->name }}</td>
                                    <td class="text-right">{!! $target !!}</td>
                                    <td class="text-center">{!! $expdate !!}</td>
                                    <td>
                                        <a href="{{ url('/program/' . $r->id . '/edit') }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>&nbsp;Edit
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
        $(document).ready(function() {
            $('#datatable').DataTable();
        });
    </script>
@endsection

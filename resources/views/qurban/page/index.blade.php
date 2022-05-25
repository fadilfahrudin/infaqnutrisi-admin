@extends('layouts.admin')

@section('title', 'Landing Page Qurban')

@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Landing Page</h1>
    <a href="{{ route($route.'.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Buat Page Baru</a>
</div>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <table id="datatable" class="table table-bordered table-sm table-hover table-striped w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Judul</th><th>Pitch</th><th>Terkumpul</th><th>Dipublikasikan?</th><th width="7%">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $r)
                        <tr>
                            <td>{{ $r->name }}</td>
                            <td>{{ $r->pitch }}</td>
                            <td class="text-right">{{ number_format($r->collected, 0, ',', '.') }}</td>
                            <td>{{ $r->published == 1 ? 'Ya' : 'Tidak' }}</td>
                            <td>
                                <a href="{{ route($route.'.edit', ['id' => $r->id]) }}" class="btn btn-info btn-sm">
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
    $(document).ready(function(){
        $('#datatable').DataTable();
    });
</script>
@endsection
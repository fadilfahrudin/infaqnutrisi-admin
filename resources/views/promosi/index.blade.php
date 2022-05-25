@extends('layouts.admin')

@section('title'){{ $title }}@endsection

@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
    <a href="{{ route($route.'.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data</a>
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
                @endif
                <table id="datatable" class="table table-bordered table-sm table-hover table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Judul</th><th>Gambar</th><th>Penempatan</th><th>Link</th><th>Urutan</th><th>Status</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $r)
                        @php $status = $r->is_active == 0 ? ' badge-secondary' : ($r->is_active == 1 ? ' badge-success' : ''); @endphp
                        @php $display = $r->is_active == 0 ? 'Belum dipublikasikan' : ($r->is_active == 1 ? 'Sudah dipublikasikan' : ''); @endphp
                        <tr>
                            <td>{{ $r->name }}</td>
                            <td><img src="{{ asset('img/promosi/'.$r->attribute3) }}" height="64"></td>
                            <td>{{ $r->attribute1 }}</td>
                            <td><a href="{{ $r->attribute2 }}" target="_blank">{{ $r->attribute2 }}</a></td>
                            <td>{{ $r->ordering }}</td>
                            <td class="text-center"><span class="badge {{ $status }}">{{ $display }}</span></td>
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
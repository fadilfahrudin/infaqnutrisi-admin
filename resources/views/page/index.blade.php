@extends('layouts.admin')

@section('title')
Program @endsection

@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Page Content</h1>
    <a href="{{ route('page.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Buat Konten</a>
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
                <table id="datatable" class="table table-bordered table-sm table-hover table-striped w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Title</th><th>Layout</th><th width="7%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $r)
                        <tr>
                            <td>{{ $r->title }}</td>
                            <td>{{ Str::ucfirst($r->layout) }}</td>
                            <td>
                                <a href="{{ url('/page/'.$r->id.'/edit') }}" class="btn btn-info btn-sm">
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
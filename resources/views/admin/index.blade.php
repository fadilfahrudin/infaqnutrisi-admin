@extends('layouts.admin')

@section('title')
User Admin @endsection

@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">User Admin</h1>
    <a href="{{ route('admin.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Input User Admin Baru</a>
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
                            <th>Nama</th><th class="text-center">Email</th><th class="text-center">Super Admin</th><th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $r)
                        @php $badge = $r->is_super == 1 ? 'badge-primary' : 'badge-secondary'; @endphp
                        <tr>
                            <td>{{ $r->name }}</td>
                            <td>{{ $r->email }}</td>
                            <td class="text-center"><span class="badge {{ $badge }}">{{ $r->is_super == 1 ? 'Ya' : 'Tidak' }}</span></td>
                            <td class="text-center">
                                <a href="{{ url('/admin/'.$r->id.'/edit') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>&nbsp;Edit
                                </a>
                                @php $dataDescription = 'User admin ' . $r->name . ' dengan Id ' . $r->id.' akan dihapus';
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
    $('#datatable').DataTable();
});
function confirmDelete(dataId, dataDescription) {
    $('#deleteForm').attr("action", "{{ route('admin.delete') }}");
    $('#data_description').text(dataDescription);
    $('#data_id').val(dataId);
    $('#confirmDeleteModal').modal('show');
}
</script>

@endsection
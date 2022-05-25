@extends('layouts.admin')

@section('title')
Berita Penyaluran @endsection

@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Berita Penyaluran</h1>
    <a href="{{ route('news.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Input Berita Baru</a>
    <a href="{{ route('news.upload.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Upload Gambar</a>
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
                            <th>Tgl. Penyaluran</th><th>Judul Berita</th><th>Jumlah Penyaluran</th><th>Program</th><th>Status</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $r)
                        @php $totalDis = !empty($r->total_distributed) ? number_format($r->total_distributed,0,',','.') : 0; @endphp
                        @php $status = $r->published == 0 ? ' badge-secondary' : ($r->published == 1 ? ' badge-success' : ''); @endphp
                        @php $display = $r->published == 0 ? 'Belum dipublikasikan' : ($r->published == 1 ? 'Sudah dipublikasikan' : ''); @endphp
                        @php $dateDis = !empty($r->date_distributed) ? date('Y/m/d', strtotime($r->date_distributed)) : ''; @endphp
                        <tr>
                            <td>{{ $dateDis }}</td>
                            <td>{{ $r->title }}</td>
                            <td class="text-right">{!! $totalDis !!}</td>
                            <td>{{ $r->program->name }}</td>
                            <td class="text-center"><span class="badge {{ $status }}">{{ $display }}</span></td>
                            <td>
                                <a href="{{ url('/news/'.$r->id.'/edit') }}" class="btn btn-info btn-sm">
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
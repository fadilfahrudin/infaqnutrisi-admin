@extends('layouts.admin')

@section('title', 'Fundraiser')
@section('headscript')
<link href="{{ asset('assets/vendor/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Fundraiser</h1>
</div>
<div class="row">
    <div class="col-7">
        <div class="card">
            <div class="card-body">
                <table id="datatable" class="table table-bordered table-sm table-hover table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Fundraiser / Iklan via</th>
                            <th>Kampanye untuk</th>
                            <th>Total Donatur</th>
                            <th>Total Perolehan (Rp.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($rows as $r)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $r['refcode'] == 'umma' ? 'Umma Apps' : $r['name'] }}</td>
                                <td><a href="javascript:;" id="btn-program" class="text-primary" onclick="getProgram('{{$r['refcode']}}')"><u>{{ $r['totalProgram'] }} program</u></a></td>
                                <td>{{ $r['donaturs'] }}</td>
                                <td class="text-right">{{ number_format($r['total'],0,',','.') }}</td>
                            </tr>
                        @php $no++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-5" id="detailFundraiser"></div>
</div>
@endsection
@section('bodyscript')
<script src="{{ asset('assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('#datatable').DataTable();
    });
    getProgram = (refcode) => {
        $('#detailFundraiser').html('<div class="berita-load text-center alert bg-transparent" id="loadBerita"><i class="fa fa-spinner fa-spin fa-2x" aria-hidden="true"></i> </div>');
        $.get('{{route('fundraiser')}}', {
            _token: $('meta[name="csrf-token"]').attr('content'),
            refcode: refcode
        }, data =>{
            if (data.success) {
                $('#detailFundraiser').html(data.html);
            }
        });
    }
</script>

@endsection

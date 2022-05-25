@extends('layouts.admin')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Kunjungi Website</a>
    </div>
    <div class="row">
        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Program</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($programs) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Donasi Terkumpul</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                {{ number_format($collected, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Donasi Tersalurkan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($mitra) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Paket Tersalurkan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($mitra) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Penerima Manfaat</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($donatur) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Rekapitulasi Donasi per Program <small>(sampai dengan hari ini)</small></h4>
                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Program</th>
                                @if ($channels)
                                    @foreach ($channels as $c)
                                        <th>{{ $c->name }}</th>
                                    @endforeach
                                @endif
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rekap)
                                @php $no = 1; @endphp
                                @for ($i = 0; $i < count($rekap); $i++)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $rekap[$i]['name'] }}</td>
                                        @if ($channels)
                                            @foreach ($channels as $c)
                                                <td class="text-right">
                                                    {{ number_format($rekap[$i]['sums'][$c->id], 0, ',', '.') }}</td>
                                            @endforeach
                                        @endif
                                        <td class="text-right">{{ number_format($rekap[$i]['total'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @php $no++; @endphp
                                @endfor
                                <tr>
                                    <td colspan="2" class="text-right font-weight-bold">GRAND TOTAL</td>
                                    @foreach ($channels as $c)
                                        <td class="text-right">
                                            {{ number_format($total_per_channel[$c->id], 0, ',', '.') }}</td>
                                    @endforeach
                                    <td class="text-right">{{ number_format($collected, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

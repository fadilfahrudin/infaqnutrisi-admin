@extends('layouts.admin')

@section('title')
Detail Pencairan @endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Informasi Pencairan Permintaan Dana</h1>
    <a href="{{ url('/withdraw') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Kembali ke Pencairan</a>
</div>
{{-- TAMBAH tramsfer dari, semua read only (status submit approve) kecuali: rekening tujuan(mitra), upload bukti transfer  --}}

<div class="row">
    <div class="col">
    <form id="form-update-withdraw" action="{{route('withdraw.updateDetail',['id' => $r->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center">Informasi Withdraw</h3>
                    @if(session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session()->get('error') }}    
                    </div>
                    @endif
                    <div class="row mt-5">
                        <div class="col">
                        <span>From : {{ date('d M Y', strtotime($r->donation_start_date))}}</span>
                        <br>
                        <span>To : {{ date('d M Y', strtotime($r->donation_end_date))}}</span>
                        </div>
                        <div class="col-md col-sm-12 text-md-right align-self-end">
                            @php $color = $r->status == 'new' ? ' text-primary' : ( $r->status == 'submitted' ? ' text-warning' : ( $r->status == 'approved' ? ' text-success' : ' text-danger')); @endphp
                            <span>Status :</span> <span class="text-lg text-uppercase{{ $color }}">{{  $r->status }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Nama Mitra</label>
                        <div class="col-9">
                        <input type="text" class="form-control" name="mitra_name" id="mitra_name" value="{{$r->program->owner->name}}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Infaq Pengembangan Teknologi</label>
                        <div class="col-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                            <input type="number" class="form-control text-right" name="infaq_pengembangan" id="infaq_pengembangan" value="{{$r->infaq_pengembangan}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Biaya Iklan</label>
                        <div class="col-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control text-right" name="biaya_iklan" id="biaya_iklan" value="{{$r->biaya_iklan}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Payment Gateway</label>
                        <div class="col-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control text-right" name="biaya_payment_gateway" id="biaya_payment_gateway" value="{{$r->biaya_payment_gateway}}"  readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-3 col-form-label">Dana yang bisa dicairkan</label>
                        <div class="col-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control text-right" name="payable_amount" id="payable_amount" value="{{$r->payable_amount}}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label">Rekening Tujuan</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="transfer_to" id="transfer_to" placeholder="Mitra belum input data" value="{{ $r->transfer_to}}" readonly>
                        </div>
                    </div>

                    @if (in_array($r->status, array('submitted', 'cancelled')))
                    
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label">Transfer Dari</label>
                        <div class="col-md-9">
                        <input type="text" class="form-control" name="transfer_from" id="transfer_from" placeholder="No Rekening, Nama Bank, Atas Nama" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label">Upload Bukti Transfer</label>
                        <div class="col-md-9">
                            <div class="custom-file">
                                <input type="file"  name="bukti_transfer" id="bukti_transfer" >
                                <br>
                                <small class="text-danger"><sup>*</sup>Max size 2MB</small>
                            </div>
                        </div>
                    </div>
                    @elseif (in_array($r->status, array('approved')))
                    
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label">Transfer Dari</label>
                        <div class="col-md-9">
                        <input type="text" class="form-control" name="transfer_from" id="transfer_from" value="{{ $r->transfer_from }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label">Bukti Transfer</label>
                        <div class="col-md-9">
                            <img src="{{ url('/img/bukti-transfer/'.$r->bukti_transfer) }}" alt="">
                            <br>
                            <span>{{ $r->bukti_transfer }}</span>
                        </div>
                    </div>
   
                    @else

                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label">Transfer Dari</label>
                        <div class="col-md-9">
                        <input type="text" class="form-control" name="transfer_from" id="transfer_from" placeholder="No Rekening, Nama Bank, Atas Nama" required>
                        </div>
                    </div>

                    @endif

                    <div class="form-group row">
                        <label for="" class="col-12 col-form-label">Catatan</label>
                        <div class="col-12">
                            <textarea class="form-control" name="details" id="details" rows=5 readonly>{{ $r->details}}</textarea>                               
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row d-flex justify-content-between">
                        <a href="{{ route('withdraw') }}" class="btn btn-outline-secondary justify-content-start">Kembali</a>
                        <div class="ml-auto">
                            
                            @if (in_array($r->status, array('new', 'submitted')))    
                            <button id="btnCancelledPencairan" type="button" class="btn btn-warning justify-content-end" data-toggle="modal" data-target="#exampleModal">
                                Batalkan
                            </button>
                            @endif
                            @if (in_array($r->status, array('submitted', 'cancelled')))    
                            <button id="btnUpdatePencairan" type="submit" class="btn btn-primary justify-content-end">Submit</button>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </form>
          <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tunggu dulu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                Anda yakin mau membatalkan pencairan ini?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
                <form action="{{route('withdraw.cancelRequest',['id' => $r->id])}}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-primary">Iya</button>
                </form>  
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection
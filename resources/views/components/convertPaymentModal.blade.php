<div class="modal fade" id="convertPaymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pindahkan ke Kotak Amal?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="" class="col-form-label col-6">Donasi yang diterima (Rp)</label>
                    <div class="col-6">
                        <input type="text" class="form-control text-right" id="input_amount" placeholder="0">
                    </div>
                </div>
                Anda yakin akan menerima pembayaran dan memindahkan donasi ini ke kotak amal?<br>
                <span class="font-italic">(Proses tidak dapat diulang)</span>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary" onclick="$('#amount_transfered').val($('#input_amount').val()); $('#convertPaymentForm').submit()">Ya</a>
            </div>
        </div>
    </div>
</div>
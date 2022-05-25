<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Anda yakin?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">Anda yakin ingin menghapus data ini?</div>
                <p><span id="data_description"></span></p>
                <form action="" id="deleteForm" method="post">
                    @csrf
                    <input type="hidden" name="id" id="data_id">
                    <button id="deleteBtn" type="submit" style="display: none">Hapus</button>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <a class="btn btn-primary" href="javascript:;" onclick="$('#deleteBtn').click()">Ya</a>
            </div>
        </div>
    </div>
</div>
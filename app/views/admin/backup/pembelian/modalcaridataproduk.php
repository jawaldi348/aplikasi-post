<div class="modal fade bd-example-modal-lg" id="modalcariproduk" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-default">
                <h5 class="modal-title" id="exampleModalLabel">Cari Data Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="dataproduk table table-bordered table-sm" id="dataproduk" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barcode</th>
                            <th>Nama Produk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    table = $('.dataproduk').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": './ambildataproduk',
            "type": "POST"
        },


        "columnDefs": [{
                "targets": [0],
                "orderable": false,
                "width": 5
            }

        ],
    });
});

function pilihData(kode, nama) {
    $('#kodebarcode').val(kode);
    // $('.viewnamaproduk').show();
    // $('.namaproduk').html(`<h5 style="font-style:italic;color:blue;">${nama}</h5>`)
    $('#modalcariproduk').on('hidden.bs.modal', function(e) {
        $('#kodebarcode').focus();
    })
    $('#modalcariproduk').modal('hide');
}
</script>
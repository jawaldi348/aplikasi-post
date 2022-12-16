<div class="modal fade" id="modalcarikoreksi" tabindex="-1" role="dialog" aria-labelledby="modalcarikoreksiLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalcarikoreksiLabel">Cari Data Koreksi Stok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-striped table-bordered display nowrap" id="datakoreksi" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th>No.Koreksi</th>
                            <th style="width: 10%;">Tanggal</th>
                            <th style="width: 15%;">Pemasok</th>
                            <th style="width: 5%;">Jml.Item</th>
                            <th style="width: 5%;">#</th>
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
function tampildata() {
    table = $('#datakoreksi').DataTable({
        responsive: true,
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?= site_url('laporan/ambildatakoreksistok') ?>",
            "type": "POST"
        },


        "columnDefs": [{
                "targets": [0],
                "orderable": false,
                "width": 5
            },
            {
                "targets": [5],
                "orderable": false,
            },
            {
                "targets": [4, 5],
                "className": 'text-center'
            }
        ],

    });
}

function pilih(no, nama) {
    $('#nokoreksi').val(no);
    $('#pemasok').val(nama);
    $('#modalcarikoreksi').modal('hide');

}
$(document).ready(function() {
    tampildata();
});
</script>
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datatables/responsive/rowReorder.dataTables.min.css">
<script src="<?= base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.rowReorder.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/datatables/responsive/dataTables.responsive.min.js"></script>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-pinterest waves-effect waves-light btn-sm"
                onclick="window.location=('./index')">
                <i class="fa fa-backward" aria-hidden="true"></i> Kembali
            </button>
        </div>
        <div class="card-body">
            <table class="table table-sm table-striped table-bordered display nowrap" id="datapembelian" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Faktur</th>
                        <th>Tgl.Faktur</th>
                        <th>Jenis Pembayaran</th>
                        <th>Tgl.Jatuh Tempo</th>
                        <th>Total Kotor (Rp)</th>
                        <th>Total Bersih (Rp)</th>
                        <th>Status</>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<script src="<?= base_url('assets/novinaldi/pembelian/data.js') ?>"></script>
<script>
function detailfaktur(faktur) {
    $.ajax({
        type: "post",
        url: "./tampildetailfaktur",
        data: {
            faktur: faktur
        },
        success: function(response) {
            $('.viewmodal').html(response).show();
            $('#modaldetailfaktur').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
</script>
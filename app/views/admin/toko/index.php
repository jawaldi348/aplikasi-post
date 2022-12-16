<div class="col-md-12 col-lg-12 col-xl-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-instagram waves-effect waves-light btnupdatetoko">
                <i class="fa fa-tag"></i> Update Toko
            </button>
        </div>
        <div class="card-body">
            <?= $this->session->flashdata('pesan'); ?>
            <table class="table table-striped table-sm">
                <tr>
                    <td style="width: 20%;">Nama Toko</td>
                    <td style="width: 1%;">:</td>
                    <td><?php echo (isset($data['nmtoko'])) ? $data['nmtoko'] : '-'; ?></td>
                    <td rowspan="5" style="width: 20%; text-align: center;">
                        <img src="<?= base_url($data['logo']) ?>" alt="Belum ada Logo" style="width: 50%;">
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%;">Alamat</td>
                    <td style="width: 1%;">:</td>
                    <td><?php echo (isset($data['alamat'])) ? $data['alamat'] : '-'; ?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">Telp</td>
                    <td style="width: 1%;">:</td>
                    <td><?php echo (isset($data['telp'])) ? $data['telp'] : '-'; ?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">Telp / HP</td>
                    <td style="width: 1%;">:</td>
                    <td><?php echo (isset($data['hp'])) ? $data['hp'] : '-'; ?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">Pemilik</td>
                    <td style="width: 1%;">:</td>
                    <td><?php echo (isset($data['pemilik'])) ? $data['pemilik'] : '-'; ?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">Tulisan dibawah struk</td>
                    <td style="width: 1%;">:</td>
                    <td><?php echo (isset($data['tulisanstruk'])) ? $data['tulisanstruk'] : ''; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<script>
$(document).ready(function() {
    $('.btnupdatetoko').click(function(e) {
        $.ajax({
            url: "<?= site_url('admin/toko/formupdate') ?>",
            success: function(response) {
                $('.viewmodal').html(response).show();
                $('#modalupdate').modal('show');
            }
        });
    });


});
</script>
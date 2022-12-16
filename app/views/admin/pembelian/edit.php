<div class="row mt-1">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" style="background-color: #0a1d8c; color:white; font-weight: bold;">

                <div class="d-flex justify-content-between">
                    <div>
                        Edit Transaksi Pembelian
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-warning"
                            onclick="document.location='<?= site_url('beli/data') ?>'">
                            <i class="fa fa-backward"></i> Kembali
                        </button>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <?= form_open('beli/simpanfaktur', ['class' => 'formsimpanfaktur']) ?>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="faktur">No.Faktur Pembelian</label>
                            <input type="text" name="faktur" id="faktur" class="form-control form-control-sm"
                                value="<?= $data['nofaktur']; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="tglfaktur">Tgl.Faktur</label>
                            <input type="date" name="tglfaktur" id="tglfaktur" class="form-control form-control-sm"
                                readonly value="<?= $data['tglbeli']; ?>">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="pemasok">Pemasok</label>
                            <div class="input-group">
                                <input type="text" name="namapemasok" id="namapemasok"
                                    class="form-control form-control-sm" value="<?= $pemasok['nama'] ?>" disabled>
                                <input type="hidden" name="idpemasok" id="idpemasok" class="form-control"
                                    value="<?= $pemasok['id'] ?>">
                            </div>

                        </div>
                    </div>
                </div>
                <?= form_close(); ?>

                <div class="row tampilforminput" style="display: none;">

                </div>
                <div class="row tampildatadetail" style="display: none;">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<div class="viewmodalcarisatuan" style="display: none;"></div>
<script>
function tampilforminput() {
    let faktur = $('#faktur').val();
    let tglfaktur = $('#tglfaktur').val();
    $.ajax({
        type: 'post',
        url: "<?= site_url('beli/tampilforminput') ?>",
        data: {
            faktur: faktur,
            tglfaktur: tglfaktur
        },
        dataType: "json",
        beforeSend: function() {
            $('.tampilforminput').html(`<i class="fa fa-spin fa-spinner"></i>`).show();
        },
        success: function(response) {
            $('.tampilforminput').html(response.data).show();
            $('#kode').focus();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function datadetailpembelian() {
    let faktur = $('#faktur').val();
    let tglfaktur = $('#tglfaktur').val();
    $.ajax({
        type: 'post',
        url: "<?= site_url('beli/datadetailpembelian') ?>",
        data: {
            faktur: faktur,
            tglfaktur: tglfaktur
        },
        dataType: "json",
        beforeSend: function() {
            $('.tampildatadetail').html(`<i class="fa fa-spin fa-spinner"></i>`).show();
        },
        success: function(response) {
            $('.tampildatadetail').html(response.data).show();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
$(document).ready(function() {
    tampilforminput();
    datadetailpembelian();
});
</script>
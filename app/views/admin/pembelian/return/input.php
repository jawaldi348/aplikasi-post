<link href="<?= base_url(); ?>assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css">
<script src="<?= base_url(); ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<style>
ul.ui-autocomplete {
    width: auto;
    border: none;
}

ul.ui-autocomplete li.ui-menu-item {
    font-weight: 100 !important;
    font-size: 14px;
    padding: 8px;
}

ul.ui-autocomplete li.ui-menu-item:hover {
    background-color: #333;
    color: #fff;
    border: 0;
    font-weight: 100 !important;
}
</style>
<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-sm btn-warning"
                onclick="window.location='<?= site_url('beli/index') ?>'">
                <i class="fa fa-fast-backward"></i> Kembali
            </button>
            <button type="button" class="btn btn-sm btn-success"
                onclick="window.location='<?= site_url('beli/return-data') ?>'">
                <i class="fa fa-tasks"></i> Lihat Data Return Item
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <label for="">No.Faktur</label>
                    <div class="input-group mb-3">
                        <input type="text" name="faktur" id="faktur" class="form-control form-control-sm" autofocus
                            placeholder="Cari Faktur">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary btn-sm" type="button"
                                id="btntombolcarifakturpembelian">
                                <i class="fa fa-fw fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label for="">Tgl.Faktur Pembelian</label>
                    <input type="date" name="tglbeli" id="tglbeli" class="form-control form-control-sm" readonly>
                </div>
                <div class="col-sm-3">
                    <label for="">Nama Pemasok</label>
                    <input type="text" name="namapemasok" id="namapemasok" class="form-control form-control-sm"
                        readonly>
                    <input type="hidden" name="idpemasok" id="idpemasok" class="form-control form-control-sm" readonly>
                </div>
                <div class="col-sm-2">
                    <label for="">#</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-primary btn-sm btn-block tomboltampilkanitem"
                                type="button">Tampilkan
                                Item</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row viewtampilitem" style="display: none;"></div>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<div class="viewmodalfakturpembelian" style="display: none;"></div>
<script>
function tampilkanitemfaktur() {
    let faktur = $('#faktur').val();

    if (faktur.length === 0) {
        $.toast({
            heading: 'Information',
            text: 'No.Faktur tidak boleh kosong',
            icon: 'info',
            loader: true,
            loaderBg: '#9EC600'
        });
    } else {
        $.ajax({
            type: "post",
            url: "<?= site_url('beli/tampilkan-item-return') ?>",
            data: {
                faktur: faktur
            },
            dataType: "json",
            beforeSend: function(e) {
                $('.viewtampilitem').html(`<i class="fa fa-spin fa-spinner"></i>`)
                    .show();
            },
            success: function(response) {
                $('.viewtampilitem').html(`${response.data}`)
                    .show();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }
}
$(document).ready(function() {
    $('#faktur').keydown(function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
        }
    });

    $('#faktur').autocomplete({
        source: "<?php echo site_url('admin/pembelian/ambil_data_pembelian'); ?>",
        select: function(event, ui) {
            $('#tglbeli').val(ui.item.tglbeli);
            $('#namapemasok').val(ui.item.nama);
            $('#idpemasok').val(ui.item.idpemasok);
        },
        change: function(event, ui) {
            $('#faktur').val(ui.item.nofaktur);
            tampilkanitemfaktur();
        }
    });

    $('.tomboltampilkanitem').click(function(e) {
        e.preventDefault();
        tampilkanitemfaktur();
    });

    $('#btntombolcarifakturpembelian').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('beli/return-carifaktur') ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodalfakturpembelian').html(response.data).show();
                    $('#modalfakturpembelian').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });
});
</script>
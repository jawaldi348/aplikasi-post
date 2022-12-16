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
    <div class="card border-light animated slideInRight">
        <div class="card-header">
            <button type="button" class="btn btn-warning btn-sm"
                onclick="location.href=('<?= site_url('admin/penjualan/index') ?>')">
                <i class="fa fa-fast-backward"></i> Kembali
            </button>

            <button type="button" class="btn btn-primary btn-sm"
                onclick="location.href=('<?= site_url('admin/penjualan/return-data') ?>')">
                <i class="fa fa-tasks"></i> Data Produk Yang di Return
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-3">
                    <label for="">Cari Faktur</label>
                    <div class="input-group mb-3">
                        <input type="text" name="faktur" id="faktur" placeholder="Cari Faktur" autofocus
                            class="form-control form-control-sm">
                        <div class="input-group-append">
                            <button class="btn btn-outline-info btn-sm" type="button" id="btncarifakturpenjualan">
                                <i class="fa fa-fw fa-search"></i>
                            </button>
                        </div>
                    </div>

                </div>
                <div class="col-sm-3">
                    <label for="">Tgl.Faktur</label>
                    <input type="date" name="tgl" id="tgl" class="form-control-sm form-control">
                </div>
                <div class="col-sm-3">
                    <label for="">Member</label>
                    <input type="text" name="member" id="member" placeholder="Cari Member"
                        class="form-control-sm form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 viewdatadetail">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<div class="viewmodalreturn" style="display: none;"></div>
<script>
function tampildetailitempenjualan() {
    let faktur = $('#faktur').val();

    $.ajax({
        type: "post",
        url: "<?= site_url('admin/penjualan/return_ambilitempenjualan') ?>",
        data: {
            faktur: faktur
        },
        dataType: "json",
        beforeSend: function(e) {
            $('.viewdatadetail').html('<i class="fa fa-spin fa-spinner"></i> Tunggu sedang ditampilkan')
                .show();
        },
        success: function(response) {
            if (response.data) {
                $('.viewdatadetail').html(response.data).show();
                $('#tgl').val(response.tgl);
                $('#member').val(response.member);
                $('#pelanggan').val(response.napel);
            } else {
                $('.viewdatadetail').html('Error').show();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
$(document).ready(function() {
    // $('#faktur').keydown(function(e) {
    //     if (e.keyCode === 13) {
    //         e.preventDefault();
    //         let faktur = $(this).val();
    //         if (faktur.length === 0) {

    //         }
    //     }
    // });

    $('#faktur').autocomplete({
        source: "<?php echo site_url('admin/penjualan/returnambildata'); ?>",
        select: function(event, ui) {
            $('#tgl').val(ui.item.tgl);
            $('#member').val(ui.item.member);
            $('#pelanggan').val(ui.item.pelanggan);

            $('#tgl').prop('readonly', true);
            $('#member').prop('readonly', true);
        },
        change: function(event, ui) {
            $('#faktur').val(ui.item.faktur);
            tampildetailitempenjualan();
        }
    });

    $('#btncarifakturpenjualan').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('admin/penjualan/return-tampildatapenjualan') ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodal').html(response.data).show();
                    $('#modalsemuadatapenjualan').modal('show');
                }
            }
        });
    });
});
</script>
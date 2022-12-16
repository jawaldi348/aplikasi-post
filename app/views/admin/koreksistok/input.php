<div class="row mt-1">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary text-white">

                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Input Koreksi Stok Produk</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-warning"
                            onclick="document.location='<?= site_url('koreksistok/index') ?>'">
                            <i class="fa fa-backward"></i> Kembali
                        </button>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <label for="">No.Koreksi</label>
                        <input type="text" name="koreksino" id="koreksino" class="form-control form-control-sm"
                            value="<?= $koreksino; ?>" readonly>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Tgl.Koreksi</label>
                        <input type="date" name="tgl" id="tgl" class="form-control-sm form-control"
                            value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-sm-3">
                        <label for="">Cari Pemasok</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control form-control-sm" readonly name="namapemasok"
                                id="namapemasok" value="-">
                            <input type="hidden" name="idpemasok" id="idpemasok" value="1">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary btn-sm btncaripemasok" type="button"
                                    title="Cari Pemasok">
                                    <i class="fa fa-fw fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3"></div>
                </div>
                <hr>

                <div class="row viewforminputproduk mt-4" style="display: none;">

                </div>
                <div class="row viewtampildata mt-2" style="display: none;">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<div class="viewmodalpemasok" style="display: none;"></div>
<script>
function tampilforminputproduk() {
    $.ajax({
        type: "post",
        url: "<?= site_url('koreksistok/forminputproduk') ?>",
        data: {
            koreksino: $('#koreksino').val(),
            tgl: $('#tgl').val(),
            idpemasok: $('#idpemasok').val()
        },
        dataType: "json",
        beforeSend: function() {
            $('.viewforminputproduk').html('<i class="fa fa-spin fa-spinner"></i>').show();
        },
        success: function(response) {
            if (response.data) {
                $('.viewforminputproduk').html(response.data).show();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}

function tampildata_koreksi_stok() {
    $.ajax({
        type: "post",
        url: "<?= site_url('koreksistok/tampildata') ?>",
        data: {
            koreksino: $('#koreksino').val()
        },
        dataType: "json",
        cache: false,
        beforeSend: function(e) {
            $('.viewtampildata').html('<i class="fa fa-spin fa-spinner"></i>').show();
        },
        success: function(response) {
            if (response.data) {
                $('.viewtampildata').html(response.data).show();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                thrownError);
        }
    });
}

$(document).ready(function() {
    tampilforminputproduk();

    tampildata_koreksi_stok();

    $(this).keydown(function(e) {
        if (e.keyCode == 27) {
            e.preventDefault();
            $('#kode').focus();
        }
    });
    $('#koreksino').keydown(function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            $(this).prop('readonly', true);
            tampildata_koreksi_stok();
        }
    });

    $('#koreksino').dblclick(function(e) {
        $(this).prop('readonly', false);
    });

    $('#tgl').change(function(e) {
        let tgl = $(this).val();
        $.ajax({
            type: "post",
            url: "<?= site_url('koreksistok/buatnomor_lagi') ?>",
            data: {
                tgl: tgl
            },
            dataType: "json",
            success: function(response) {
                if (response.koreksino) {
                    $('#koreksino').val(response.koreksino);
                    tampilforminputproduk();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                    thrownError);
            }
        });
    });

    $('.btncaripemasok').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('admin/koreksistok/caripemasok') ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodalpemasok').html(response.data).show();
                    $('#modalcaripemasok').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                    thrownError);
            }
        });
    });
});
</script>
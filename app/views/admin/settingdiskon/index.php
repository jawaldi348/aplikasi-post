<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">

        </div>
        <div class="card-body">
            <?= form_open('setting-diskon-member/simpan', ['class' => 'formsimpan']) ?>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Diskon (%)</label>
                <div class="col-sm-2">
                    <input type="text" readonly class="form-control form-control-sm" id="diskon" name="diskon"
                        value="<?= number_format($data['diskon'], 2, ".", ","); ?>">
                </div>
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-success btn-sm btnsimpan">
                        Update Diskon
                    </button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
$(document).ready(function() {
    $('#diskon').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });

    $('#diskon').click(function(e) {
        e.preventDefault();
        $(this).prop('readonly', false);
    });

    $('.formsimpan').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Update Diskon Member',
            html: `Yakin di Update ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "post",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "json",
                    cache: false,
                    success: function(response) {
                        if (response.sukses) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.sukses,
                            }).then((result) => {
                                if (result.value) {
                                    window.location.reload();
                                }
                            })
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            }
        })
        return false;
    });
});
</script>
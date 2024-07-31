@extends(layouts/index)
@section(content)
<div class="col-md-12 col-lg-12 col-xl-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-instagram waves-effect waves-light edit">
                <i class="fa fa-tag"></i> Update Toko
            </button>
        </div>
        <div class="card-body">
            <table class="table table-striped table-sm">
                <tr>
                    <td style="width: 20%;">Nama Toko</td>
                    <td style="width: 1%;">:</td>
                    <td><?= $data['bisnis'] ?></td>
                    <td rowspan="5" style="width: 20%; text-align: center;">
                        <img src="<?= $data['logo'] ?>" alt="" style="width: 50%;">
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%;">Alamat</td>
                    <td style="width: 1%;">:</td>
                    <td><?= $data['alamat'] ?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">Telepon</td>
                    <td style="width: 1%;">:</td>
                    <td><?= $data['telp'] ?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">Handphone</td>
                    <td style="width: 1%;">:</td>
                    <td><?= $data['phone'] ?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">Pemilik</td>
                    <td style="width: 1%;">:</td>
                    <td><?= $data['pemilik'] ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div id="tampil-modal"></div>
@endsection
@section(script)
<script>
    $(document).on('click', '.edit', function() {
        $.post(BASE_URL + 'profil-bisnis/edit', function(resp) {
            $('#tampil-modal').show();
            $('#tampil-modal').html(resp);
            const modalForm = document.querySelector('#modal-form');
            modalForm.classList.add('animated', 'zoomIn');
            $('#modal-form').modal('show');
        });
    });

    $(document).on('submit', '.form_data', function(e) {
        event.preventDefault();
        var formData = new FormData($('.form_data')[0]);
        $.ajax({
            url: $('.form_data').attr('action'),
            dataType: 'json',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('.store_data').attr('disabled', 'disabled');
                $('.store_data').html('<i class="fa fa-spin fa-spinner"></i> Sedang di Proses');
            },
            success: function(resp) {
                $('.form-group').removeClass('has-error').find('.help-block').remove();
                if (resp.status == 'success') {
                    window.location.href = BASE_URL + 'profil-bisnis';
                } else {
                    $.each(resp.error, function(key, value) {
                        var element = $('#' + key);
                        element.closest('div.form-group')
                            .removeClass('has-error')
                            .addClass(value.length > 0 ? 'has-error' : 'has-success')
                            .find('.help-block')
                            .remove();
                        element.after('<div class="help-block">' + value + '</div>');
                    });
                }
            },
            complete: function() {
                $('.store_data').removeAttr('disabled');
                $('.store_data').html('Simpan');
            }
        })
    });
</script>
@endsection
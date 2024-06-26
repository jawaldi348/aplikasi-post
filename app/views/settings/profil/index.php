@extends(layouts/index)
@section(content)
<div class="col-md-12 col-lg-12 col-xl-2">
    <div class="card m-b-30">
        <img class="card-img-top img-fluid" src="<?= $data['image'] ?>" alt="Foto User">
        <div class="card-body">
            <a href="#" class="btn btn-primary waves-effect waves-light btn-block change_image">Ganti Foto Profil</a>
        </div>
    </div>
</div>
<div class="col-md-12 col-lg-12 col-xl-10">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-pinterest waves-effect waves-light change_profil">
                <i class="fa fa-user"></i> Update Profil
            </button>
            <button type="button" class="btn btn-twitter waves-effect waves-light change_password">
                <i class="fa fa-lock"></i> Ganti Password
            </button>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <tr>
                    <td style="width: 20%;">ID User</td>
                    <td style="width: 2%;">:</td>
                    <td><?= $data['username']; ?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">Nama Lengkap</td>
                    <td style="width: 2%;">:</td>
                    <td><?= $data['nama']; ?></td>
                </tr>
                <tr>
                    <td style="width: 20%;">Grup</td>
                    <td style="width: 2%;">:</td>
                    <td><?= $data['group']; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div id="tampil-modal"></div>
@endsection
@section(script)
<script src="<?= base_url() ?>src/js/profil.js"></script>
<script>
    $(document).on('click', '.change_image', function() {
        $.post(BASE_URL + 'profil/change-image', function(resp) {
            $('#tampil-modal').show();
            $('#tampil-modal').html(resp);
            const modalForm = document.querySelector('#modal-form');
            modalForm.classList.add('animated', 'zoomIn');
            $('#modal-form').modal('show');
        });
    });

    $(document).on('click', '.change_profil', function() {
        $.post(BASE_URL + 'profil/change-profil', function(resp) {
            $('#tampil-modal').show();
            $('#tampil-modal').html(resp);
            const modalForm = document.querySelector('#modal-form');
            modalForm.classList.add('animated', 'zoomIn');
            $('#modal-form').modal('show');
        });
    });

    $(document).on('click', '.change_password', function() {
        $.post(BASE_URL + 'profil/change-password', function(resp) {
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
                    $('#modal-form').modal('hide');
                    $.toast({
                        heading: 'Success!',
                        text: resp.message,
                        icon: 'success',
                        loader: true,
                    });
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
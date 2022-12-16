$(document).ready(function (e) {
    $('.btngantifoto').click(function (e) {
        $.ajax({
            url: "./formgantifoto",
            success: function (response) {
                $('.viewmodal').fadeIn();
                $('.viewmodal').html(response).show();
                $('#modalgantifoto').modal('show');
            }
        });
    })
    $('.btngantiprofil').click(function (e) {
        $.ajax({
            url: "./formgantiprofil",
            success: function (response) {
                $('.viewmodal').fadeIn();
                $('.viewmodal').html(response).show();
                $('#modalgantiprofil').modal('show');
            }
        });
    });
    $('.btngantipassword').click(function (e) {
        $.ajax({
            url: "./formgantipassword",
            success: function (response) {
                $('.viewmodal').fadeIn();
                $('.viewmodal').html(response).show();
                $('#modalgantipassword').modal('show');
            }
        });
    });

    $('.formupdateprofil').submit(function (e) {
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            cache: false,
            beforeSend: function () {
                $('.btnsimpan').attr('disabled', 'disabled');
                $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i> Sedang di Proses');
            },
            success: function (response) {
                if (response.error) {
                    $('.msg').fadeIn();
                    $('.msg').html(response.error).show();
                }
                if (response.sukses) {
                    window.location.reload();
                }
            },
            complete: function () {
                $('.btnsimpan').removeAttr('disabled');
                $('.btnsimpan').html('Simpan');
            }
        });
        return false;
    });

    $('.formgantipassword').submit(function (e) {
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            cache: false,
            beforeSend: function () {
                $('.btnsimpan').attr('disabled', 'disabled');
                $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i> Sedang di Proses');
            },
            success: function (response) {
                if (response.error) {
                    $('.msg').fadeIn();
                    $('.msg').html(response.error).show();
                }
                if (response.sukses) {
                    window.location.reload();
                }
            },
            complete: function () {
                $('.btnsimpan').removeAttr('disabled');
                $('.btnsimpan').html('Simpan');
            }
        });
        return false;
    });
});
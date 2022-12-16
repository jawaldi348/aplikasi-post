$(document).ready(function (e) {
    $('.formsimpan').submit(function (e) {
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
                    $('.msg').show();
                    $('.msg').html(response.error);
                }
                if (response.sukses) {
                    $.toast({
                        heading: 'Berhasil',
                        text: response.sukses,
                        icon: 'success',
                        position: 'top-center',
                        loader: true,
                    });
                    tampilhargaproduk();
                    $('#viewmodaledit').modal('hide');
                }
            },
            complete: function () {
                $('.btnsimpan').removeAttr('disabled');
                $('.btnsimpan').html('Update');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });

        return false;
    })
})
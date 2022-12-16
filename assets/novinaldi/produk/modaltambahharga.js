$(document).ready(function (e) {
    $('#satuan').select2();

    ambildatasatuan();

    $('.formsimpan').submit(function (e) {
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            cache: false,
            beforeSend: function () {
                $('.btnsimpan').attr('disabled', 'disabled');
                $('.btnsimpan').html(
                    '<i class="fa fa-spin fa-spinner"></i> Sedang di Proses');
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
                    $('#viewmodal').modal('hide');
                    tampilhargaproduk();
                }
            },
            complete: function () {
                $('.btnsimpan').removeAttr('disabled');
                $('.btnsimpan').html('Simpan');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });

        return false;
    });
});

function ambildatasatuan() {
    $.ajax({
        url: ".././ambildatasatuan",
        dataType: "json",
        success: function (response) {
            $('#satuan').html(response.datasatuan).show();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

$(document).on('click', '.tombolrefresh', function (e) {
    ambildatasatuan();
});

var newwindow;

function tambahsatuan() {
    newwindow = window.open("../.././satuan/index", 'name',
        'height=520,width=700,scrollbars=yes,location=no,top=80');
    if (window.focus) {
        newwindow.focus()
    }
    return false;
}
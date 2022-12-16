
const tambahHargaProduk = document.querySelector('.tambahhargaproduk');
const kode = document.getElementById('kode');

$(document).ready(function (e) {
    tampilhargaproduk();
});

tambahHargaProduk.addEventListener('click', function (e) {
    $.ajax({
        url: '.././formtambahharga',
        type: 'POST',
        data: {
            kode: kode.value
        },
        success: function (response) {
            $('.viewmodal').fadeIn();
            $('.viewmodal').html(response);
            $('#viewmodal').modal('show');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
});

function tampilhargaproduk() {
    $.ajax({
        type: "post",
        url: ".././tampilhargaproduk",
        data: {
            kode: kode.value
        },
        cache: false,
        beforeSend: function () {
            $('.vtampilhargaproduk').html('<i class="fa fa-spin fa-spinner"></i>').show();
        },
        success: function (response) {
            $('.vtampilhargaproduk').html(response).show();
        }, error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

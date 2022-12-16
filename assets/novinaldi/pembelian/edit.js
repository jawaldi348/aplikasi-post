function tampilformeditdetail() {
    $.ajax({
        type: "post",
        url: ".././formeditdetail",
        data: {
            faktur: $('#faktur').val()
        },
        beforeSend: function () {
            $('.viewformeditdetail').html('<i class="fa fa-spin fa-spinner"></i>').show();
        },
        success: function (response) {
            $('.viewformeditdetail').html(response).show();
            $('#kode').focus();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function tampildatadetail() {
    $.ajax({
        type: "post",
        url: ".././editdatadetail",
        data: {
            faktur: $('#faktur').val()
        },
        beforeSend: function () {
            $('.viewtampildatadetail').html('<i class="fa fa-spin fa-spinner"></i>').show();
        },
        success: function (response) {
            $('.viewtampildatadetail').html(response).show();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

$(document).ready(function () {
    tampildatadetail();
    $('.btntampilforminput').click(function (e) {
        e.preventDefault();
        tampilformeditdetail();
    });
    $('.btntampildatadetail').click(function (e) {
        e.preventDefault();
        tampildatadetail();
    });
});
const viewKategori = document.querySelector('.viewkategori');

function tampilforminputsatuankategori() {
    $.ajax({
        url: "./tampilforminputsatuankategori",
        dataType: 'json',
        success: function (response) {
            $('#kategori').html(response.datakategori);
            $('#satuan').html(response.datasatuan);
            $('#kategori').select2();
            $('#satuan').select2();
        }
    });
}

$(document).ready(function (e) {
    tampilforminputsatuankategori();

    //setting currency
    $('#hargabeli').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });
    //setting currency
    $('#hargajual').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });
    $('#hargajualgrosir').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });
    //setting currency
    $('#margin').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });

    $('#btntambahsatuan').click(function (e) {
        $.ajax({
            url: ".././satuan/tambah",
            success: function (response) {
                $('.viewform').show();
                $('.viewform').html(response);
                $('#modaltambah').modal('show');
            }
        });
    });

});

const btnTambahKategori = document.getElementById('btnTambahKategori');
const tombolTambahKategori = () => {
    $.ajax({
        url: "./tambahkategori",
        success: function (response) {
            $('.viewform').show();
            $('.viewform').html(response);
            $('#modaltambah').modal('show');
        }
    });
}

btnTambahKategori.addEventListener('click', tombolTambahKategori);

$(document).on('submit', '.formtambah', function (e) {
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
                $('.msg').html(response.error);
            }
            if (response.sukses) {
                $.toast({
                    heading: 'Berhasil',
                    text: response.sukses,
                    icon: 'success',
                    loader: true,
                });
                tampilforminputsatuankategori();
                $('#modaltambah').modal('hide');
            }
        },
        complete: function () {
            $('.btnsimpan').removeAttr('disabled');
            $('.btnsimpan').html('Simpan Data');
        }
    });

    return false;
});

$(document).on('keyup', '#margin', function (e) {
    let margin = $(this).val();
    let hargabeli = $('#hargabeli').val();
    let konversi_hargabeli = hargabeli.replace(",", "");

    hitung_hargajual = parseFloat(konversi_hargabeli) + ((parseFloat(konversi_hargabeli) *
        parseFloat(margin)) / 100);

    $('#hargajual').autoNumeric('set', hitung_hargajual);
});

$(document).on('keyup', '#hargajual', function (e) {
    let hargajual = $(this).autoNumeric('get');
    let hargabeli = $('#hargabeli').autoNumeric('get');

    let hitunglaba;
    hitunglaba = parseFloat(hargajual) - parseFloat(hargabeli);

    let margin;
    margin = (hitunglaba / hargabeli) * 100;

    $('#margin').autoNumeric('set', margin);
});
// Perhitungan Margin

// const inputMargin = document.getElementById('margin');
// const inputHargaJual = document.getElementById('hargajual');

// inputMargin.addEventListener('keyup', function (e) {
//     let hargabeli = document.getElementById('hargabeli');
//     let hargajual = document.getElementById('hargajual');
//     let margin = document.getElementById('margin');

//     let hitungHargajual;
//     hitungHargajual = parseInt(hargabeli.value) + ((parseInt(hargabeli.value) * parseFloat(margin.value)) / 100);

//     hargajual.value = hitungHargajual;
// });

// inputHargaJual.addEventListener('keyup', function (e) {
//     let hargabeli = document.getElementById('hargabeli');
//     let hargajual = document.getElementById('hargajual');
//     let margin = document.getElementById('margin');

//     let hitungMargin;
//     let laba;
//     laba = parseInt(hargajual.value) - parseInt(hargabeli.value);
//     hitungMargin = (laba / hargabeli.value) * 100;

//     margin.value = hitungMargin.toFixed(2);
// });
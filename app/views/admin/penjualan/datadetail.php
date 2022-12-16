<table class="table table-sm table-bordered" style="width: 100%;">
    <thead class="table-light">
        <tr>
            <th colspan="4" style="text-align: left; font-size:14pt;">
                <?php $jmlitem = 0;
                $totalitem = 0;
                foreach ($data->result_array() as $rrr) : $jmlitem = $jmlitem + 1;
                    $totalitem = $totalitem + $rrr['jml'];
                ?>
                <?php endforeach; ?>
                <?= "Jml.Item : $jmlitem ($totalitem)"; ?>
            </th>
            <th colspan="7" class="tampilsisauang" style="text-align: right; font-size:30pt; font-weight: bold; color:green;">
                <?php if ($jualsisapembulatan != 0.00) : ?>
                    <?= number_format($jualpembulatan, 0, ".", ","); ?>
                <?php else : ?>
                    <?= number_format($jualtotalbersih, 0, ".", ","); ?>
                <?php endif; ?>
            </th>
        </tr>
        <tr style="font-size: 9pt;">
            <th style="width: 3%;">#</th>
            <th style="width: 12%;">Kode <span style="font-size: 8pt; font-weight: bold; color: red;">(Escape)</span>
            </th>
            <th>Item</th>
            <th style="width: 5%;">Qty <span style="font-size: 8pt; font-weight: bold; color: red;">(+)</span></th>
            <th style="width: 5%;">Sat.<span style="font-size: 8pt; font-weight: bold; color: red;">(F1)</span></th>
            <th style="width: 5%;">Jml.Return <i class="fa fa-undo-alt"></i></th>
            <th style="width: 10%; text-align: right;">Harga(Rp)</th>
            <th style="width: 5%; text-align: center;">Disc(%) <span style="font-size: 8pt; font-weight: bold; color: red;">(Alt+D)</span></th>
            <th style="width: 10%; text-align: center;">Disc(Rp)</th>
            <th style="width: 10%; text-align: right;">Sub.Total</th>
            <th style="width: 8%; text-align: right;">#</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>
                <span class="aktiflangsung">
                    <i class="fa fa-check-circle" style="color: green;"></i>
                </span>
                <input type="hidden" name="aktif" id="aktif" value="1">
            </th>
            <th>
                <input type="hidden" name="id" id="id">
                <input type="text" name="kode" id="kode" class="form-control form-control-sm" autocomplete="off">
            </th>
            <th id="namaproduk">
                <input type="hidden" name="namaproduk" id="tnamaproduk">
            </th>
            <th>
                <input type="text" name="jml" id="jml" class="form-control form-control-sm" value="1">
            </th>
            <th id="namasatuan"></th>
            <th></th>
            <th id="hargajual"></th>
            <th>
                <input type="text" name="dispersen" id="dispersen" class="form-control form-control-sm">
            </th>
            <th>
                <input type="text" name="disuang" id="disuang" class="form-control form-control-sm">
            </th>
            <th></th>
            <th style="text-align: right;">
                <button type="button" class="btn btn-sm btn-round btn-outline-primary btnadditem">
                    <i class="fa fa-plus-circle"></i>
                </button>
            </th>
        </tr>
        <?php
        $nomor = 0;
        foreach ($data->result_array() as $row) :
            $nomor++;
        ?>
            <tr style="font-size: 9pt;">
                <td><?= $nomor; ?></td>
                <td><?= $row['kode']; ?></td>
                <td><?= $row['namaproduk']; ?></td>
                <td style="text-align: center;">
                    <i class="fa fa-minus fa-fw" style="color: blue; font-size:10px; cursor:pointer;" onclick="updatekurangjml('<?= $row['id'] ?>','<?= $row['kode'] ?>','<?= $row['jml'] ?>')"></i>&nbsp;&nbsp;<?= "<strong>" . number_format($row['jml'], 0) . "</strong>"; ?>&nbsp;&nbsp;<i class="fa fa-plus fa-fw" style="color: blue; font-size:10px; cursor:pointer;" onclick="updatetambahjml('<?= $row['id'] ?>','<?= $row['kode'] ?>')"></i>
                </td>
                <td style="text-align: center;">
                    <a class="dGantiSatuan" href="#" onclick="gantisatuan('<?= $row['kode']; ?>','<?= $row['id']; ?>','<?= $row['jml']; ?>')"><?= $row['namasatuan']; ?></a>
                </td>
                <td><?= $row['jmlreturn'] ?></td>
                <td style="text-align: right;cursor: pointer;" onclick="cekHarga('<?= $row['kode'] ?>','<?= $row['id'] ?>')">
                    <?= number_format($row['harga'], 0, ".", ",") ?></td>
                <td style="text-align: right;">
                    <?= number_format($row['dispersen'], 2); ?>
                </td>
                <td style="text-align: right;">
                    <?= number_format($row['disuang'], 2); ?>
                </td>
                <td style="text-align: right;">
                    <?= number_format($row['subtotal'], 0, ".", ",") ?>
                </td>
                <td style="text-align: right;">
                    <button type="button" class="btn btn-sm btn-outline-info" title="Edit Item" onclick="edit('<?= $row['id'] ?>')">
                        <i class="fa fa-tags"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus Item" onclick="hapusitem('<?= $row['id'] ?>','<?= $row['kode'] ?>','<?= $row['jml'] ?>')">
                        <i class="fa fa-trash-alt fa-sm"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th colspan="9" style="text-align: left; font-size:12pt;">
                Discount (%) <small style="color: red; font-weight: bold; font-style:italic; font-size:8pt;">CTRL +
                    D</small>
            </th>
            <th colspan="" style="text-align: right; font-size:10pt;">
                <input type="text" name="dispersensemua" id="dispersensemua" class="form-control form-control-sm" value="<?= $jualdispersen ?>" style="text-align: right; font-weight: bold; font-size:12pt;">
            </th>
            <th></th>
        </tr>
        <tr>
            <th colspan="9" style="text-align: left; font-size:12pt;">
                Discount (Rp)
            </th>
            <th colspan="" style="text-align: right; font-size:10pt;">
                <input type="text" name="disuangsemua" id="disuangsemua" class="form-control form-control-sm" value="<?= $jualdisuang; ?>" style="text-align: right; font-weight: bold; font-size:12pt;">
            </th>
            <th></th>
        </tr>
        <tr>
            <th colspan="9" style="text-align: left; font-size:12pt;">
                Total Keseluruhan (Rp.)
            </th>
            <th colspan="" style="text-align: right; font-size:10pt;">
                <input type="hidden" name="total_kotor" id="total_kotor" value="<?= $jualtotalkotor; ?>">
                <input type="text" id="total_bersih_semua" name="total_bersih_semua" value="<?= $jualtotalbersih; ?>" class="form-control-lg form-control total_bersih_semua" style="text-align: right; font-weight: bold; font-size:14pt;color:#010a6b" readonly="readonly">
            </th>
            <th></th>
        </tr>

        <tr>
            <th colspan="9" style="text-align: left; font-size:12pt;">
                Pembulatan (Rp.)
            </th>
            <th colspan="" style="text-align: right; font-size:10pt;">
                <?php
                if ($jualsisapembulatan != 0.00) {
                ?>
                    <input type="text" id="pembulatan" name="pembulatan" value="<?= $jualpembulatan; ?>" class="form-control-lg form-control pembulatan" style="text-align: right; font-weight: bold; font-size:14pt;color:#010a6b" readonly="readonly">
                <?php
                } else { ?>
                    <input type="text" id="pembulatan" name="pembulatan" value="<?= $jualpembulatan; ?>" class="form-control-lg form-control pembulatan" style="text-align: right; font-weight: bold; font-size:14pt;color:#010a6b; display:none;" readonly="readonly">
                <?php
                }
                ?>
            </th>
            <th></th>
        </tr>
    </tbody>
</table>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
    function cekHarga(kode, id) {
        $.ajax({
            type: "post",
            url: "<?= site_url('admin/penjualan/modalPilihanHargaProduk') ?>",
            data: {
                kodebarcode: kode,
                id: id
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewModalGantiHarga').html(response.data).show();
                    $('#modal_pilihanHargaProduk').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    function perhitungandissemua() {
        let totalkotor = $('#total_kotor').val();
        let dispersen = $('#dispersensemua').autoNumeric('get');
        let disuang = $('#disuangsemua').autoNumeric('get');

        let totalbersih = parseFloat(totalkotor) - (parseFloat(totalkotor) * parseFloat(dispersen) / 100) - parseFloat(
            disuang);
        $('.total_bersih_semua').autoNumeric('set', totalbersih);

        let ambilratusan = totalbersih.toString().slice(-2);
        console.log(ambilratusan);
        if (ambilratusan >= 01 & ambilratusan <= 99) {
            // Lakukan Pembulatan
            $('.pembulatan').show();
            let pembulatan = Math.ceil(totalbersih / 100) * 100;
            $('.pembulatan').autoNumeric('set', pembulatan);

            $('.tampilsisauang').html(`Rp. ${$('.pembulatan').val()}`);
        } else {
            $('.pembulatan').hide();
            $('.pembulatan').autoNumeric('set', 0);
            $('.tampilsisauang').html(`Rp. ${$('.total_bersih_semua').val()}`);
        }

        // console.log(totalbersih);
    }

    function updatekurangjml(id, kode, jml) {
        // alert(id + "\n" + kode + "\n" + "kurang");
        if (jml == 1) {
            $.toast({
                heading: 'Error',
                icon: 'warning',
                text: 'Jumlah tidak Boleh 0',
                position: 'bottom-center',
                stack: false
            });
        } else {
            $.ajax({
                type: "post",
                url: "<?= site_url('admin/penjualan/updatekurangjml') ?>",
                data: {
                    id: id,
                    kode: kode,
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        tampildatatemppenjualan();
                    } else {
                        alert(`${response.error}`);
                        tampildatatemppenjualan();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    }

    function updatetambahjml(id, kode) {
        // alert(id + "\n" + kode + "\n" + "Tambah");
        $.ajax({
            type: "post",
            url: "<?= site_url('admin/penjualan/updatetambahjml') ?>",
            data: {
                id: id,
                kode: kode,
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    tampildatatemppenjualan();
                    // alert(response.sukses);
                } else {
                    alert(`${response.error}`);
                    tampildatatemppenjualan();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    function detailproduk() {
        let produk = $('#kode').val();
        let aktif = $('#aktif').val();
        let diskonuang = $('#disuang').val();
        let faktur = $('#faktur').val();
        let jualtgl = $('#jualtgl').val();
        if (aktif == 1) {
            if (produk == "" || produk == null) {
                cariproduk();
            } else {
                $.ajax({
                    type: "post",
                    url: "<?= site_url('admin/penjualan/detailproduk') ?>",
                    data: {
                        kode: produk,
                        faktur: faktur,
                        jml: $('#jml').val(),
                        dispersen: $('#dispersen').val(),
                        disuang: diskonuang.replace(",", ""),
                        jualtgl: jualtgl,
                        namaproduk: $('#tnamaproduk').val()
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            tampildatatemppenjualan();
                        }
                        if (response.banyakdata) {
                            $('.viewmodal').html(response.banyakdata).show();
                            $('#modaldatacariproduk').modal('show');
                        }

                        if (response.error) {
                            let timerInterval
                            Swal.fire({
                                title: 'Error',
                                html: response.error,
                                timer: 1000,
                                timerProgressBar: true,
                                onBeforeOpen: () => {
                                    Swal.showLoading()
                                    timerInterval = setInterval(() => {
                                        const content = Swal
                                            .getContent()
                                        if (content) {
                                            const b = content
                                                .querySelector('b')
                                            if (b) {
                                                b.textContent = Swal
                                                    .getTimerLeft()
                                            }
                                        }
                                    }, 100)
                                },
                                onClose: () => {
                                    clearInterval(timerInterval)
                                }
                            }).then((result) => {
                                tampildatatemppenjualan();
                            })
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            }
        }
    }
    $(document).ready(function() {
        $('#kode').focus();
        $('#dispersensemua').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                perhitungandissemua();
            }
        });
        $('#disuangsemua').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                perhitungandissemua();
            }
        });
        $('#pembulatan').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });
        $('#total_bersih_semua').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });
        $('#disuangsemua').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });
        $('#dispersensemua').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });
        $('#jml').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });

        $('#disuang').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '0'
        });

        $('#dispersen').autoNumeric('init', {
            aSep: ',',
            aDec: '.',
            mDec: '2'
        });

        $(this).keydown(function(e) {


            if (e.keyCode == 27) {
                e.preventDefault();
                $('#kode').focus();
            }

            // Focus ke input qty
            if (e.keyCode == 61) { // Press +
                e.preventDefault();
                $('#jml').val('');
                $('#jml').focus();
            }

            // Focus ke input diskon
            if (e.altKey && e.keyCode == 68) {
                e.preventDefault();
                $('#dispersen').focus();
            }

            // reload data
            if (e.altKey && e.keyCode == 82) {
                e.preventDefault();
                tampildatatemppenjualan();
            }

            // ambil data terakhir tombol F2
            if (e.keyCode == 113) {
                e.preventDefault();
                ambildatatemp_terakhir();
            }

        });

        //Pencarian kode atau nama produk
        $('#kode').keydown(function(e) {
            // if (e.altKey && e.keyCode == 68) {
            //     e.preventDefault();
            //     alert('saya menekan tombol alt+d');
            // }
            if (e.keyCode === 13) {
                e.preventDefault();
                detailproduk();
            }
        });

        //Tombol Add item
        $('.btnadditem').click(function(e) {
            e.preventDefault();
            detailproduk();
        });

        // Menampilkan modal ganti satuan
        $('#jml').keydown(function(e) {
            if (e.keyCode == 112) {
                e.preventDefault();
                $.ajax({
                    type: "post",
                    url: "<?= site_url('admin/penjualan/gantisatuan') ?>",
                    data: {
                        kode: $('#kode').val(),
                        id: $('#id').val(),
                        jualfaktur: $('#faktur').val(),
                        jml: $('#jml').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.sukses) {
                            $('.viewmodalgantisatuan').html(response.sukses.tampilmodal).show();
                            $('#modalgantisatuan').on('shown.bs.modal', function(e) {
                                $('#satuan').focus();
                            });
                            $('#modalgantisatuan').modal('show');
                        } else {
                            $.toast({
                                heading: 'Error',
                                text: response.error,
                                showHideTransition: 'slide',
                                icon: 'error',
                                position: 'top-center'
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            }
        });

        // Mengupdate jml, dispersen, disuang
        $('#jml').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();

                updatejml();
            }
        });

        $('#dispersen').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();

                updatejml();
            }
        });
        $('#disuang').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();

                updatejml();
            }
        });
    });

    function gantisatuan(kode, id, jml) {
        $.ajax({
            type: "post",
            url: "<?= site_url('admin/penjualan/gantisatuan') ?>",
            data: {
                kode: kode,
                id: id,
                jualfaktur: $('#faktur').val(),
                jml: jml
            },
            dataType: 'json',
            success: function(response) {
                if (response.sukses) {
                    $('.viewmodalgantisatuan').html(response.sukses.tampilmodal).show();
                    $('#modalgantisatuan').on('shown.bs.modal', function(e) {
                        $('#satuan').focus();
                    });
                    $('#modalgantisatuan').modal('show');
                } else {
                    $.toast({
                        heading: 'Error',
                        text: response.error,
                        showHideTransition: 'slide',
                        icon: 'error',
                        position: 'top-center'
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    function cariproduk() {
        $.ajax({
            url: "<?= site_url('admin/penjualan/cariproduk') ?>",
            success: function(response) {
                $('.viewmodal').html(response).show();
                $('#modalcariproduk').on('shown.bs.modal', function(e) {
                    $('[type="search"]').focus();
                });
                $('#modalcariproduk').modal('show');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    function hapusitem(id, kode, jml) {
        Swal.fire({
            title: 'Hapus Item',
            text: "Yakin menghapus item ini",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "post",
                    url: "<?= site_url('admin/penjualan/hapusitem') ?>",
                    data: {
                        id: id,
                        kode: kode,
                        jml: jml
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            $.toast({
                                heading: 'Sukses',
                                text: response.sukses,
                                showHideTransition: 'slide',
                                icon: 'success',
                                position: 'bottom-right'
                            });
                            tampildatatemppenjualan();
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            }
        })
    }

    function ambildatatemp_terakhir() {
        $.ajax({
            type: "post",
            url: "<?= site_url('admin/penjualan/ambildatatemp_terakhir') ?>",
            data: {
                faktur: $('#faktur').val()
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    let data = response.sukses
                    $('#jml').focus();
                    $('#jml').val(data.jml);
                    $('#kode').attr('readonly', 'readonly');
                    $('#kode').val(data.kode);
                    $('#id').val(data.id);
                    $('#namaproduk').html(data.namaproduk);
                    $('#namasatuan').html(data.satuan);
                    $('#hargajual').html(data.hargajual);
                    $('#dispersen').val(data.dispersen);
                    $('#disuang').val(data.disuang);
                }

                if (response.error) {
                    $.toast({
                        heading: 'Error',
                        text: response.error,
                        showHideTransition: 'slide',
                        icon: 'error',
                        position: 'bottom-right'
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    function updatejml() {
        let jml = $('#jml').val();
        let id = $('#id').val();
        let dispersen = $('#dispersen').val();
        let disuang = $('#disuang').val();
        let kode = $('#kode').val();

        if (kode == "" || kode == null) {
            $('#kode').focus();
        } else {
            $.ajax({
                type: "post",
                url: "<?= site_url('admin/penjualan/updatejmlproduk') ?>",
                data: {
                    jml: jml,
                    id: id,
                    dispersen: dispersen.replace(",", ""),
                    disuang: disuang.replace(",", ""),
                    kode: kode
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        tampildatatemppenjualan();
                    } else {
                        alert(`${response.error}`);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    }

    function edit(id) {
        $.ajax({
            type: "post",
            url: "<?= site_url('admin/penjualan/edititem_tempjual') ?>",
            data: {
                id: id
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    let data = response.sukses
                    $('#jml').focus();
                    $('#jml').val(data.jml);
                    $('#kode').attr('readonly', 'readonly');
                    $('#kode').val(data.kode);
                    $('#id').val(data.id);
                    $('#namaproduk').html(data.namaproduk);
                    $('#namasatuan').html(data.satuan);
                    $('#hargajual').html(data.hargajual);
                    $('#dispersen').val(data.dispersen);
                    $('#disuang').val(data.disuang);
                }

                if (response.error) {
                    $.toast({
                        heading: 'Error',
                        text: response.error,
                        showHideTransition: 'slide',
                        icon: 'error',
                        position: 'bottom-right'
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }
</script>
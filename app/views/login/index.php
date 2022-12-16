<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Halaman Login | <?= $namatoko; ?></title>
    <meta content="Admin Dashboard" name="Novinaldi" />
    <meta content="Mannatthemes" name="Novinaldi" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="<?php echo base_url($logo) ?>">

    <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>assets/css/style.css" rel="stylesheet" type="text/css">
    <!-- SweetAlert -->
    <script src="<?= base_url(); ?>assets/plugins/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/sweetalert2/dist/sweetalert2.min.css" type="text/css">
    <style>
        .accountbg {
            background: url("<?= site_url('assets/images/bg-login.jpg') ?>");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: fixed;
            height: 100%;
            width: 100%;
            top: 0;
            filter: blur(2px);
        }
    </style>
</head>


<body>

    <!-- Begin page -->
    <div class="accountbg"></div>
    <div class="wrapper-page">

        <div class="card">
            <div class="card-body">

                <h3 class="text-center mt-0 m-b-15">
                    <a href="<?= site_url(); ?>" class="logo logo-admin"><img src="<?= base_url($logo) ?>" height="150" alt="logo Belum Ada"></a>
                </h3>

                <div class="p-3">
                    <?= form_open('login/validasi_user') ?>
                    <?= $this->session->flashdata('pesan'); ?>
                    <div class="form-group row">
                        <div class="col-12">
                            <!--<input class="form-control" type="text" required="" placeholder="Username"
                                value="<? //= $this->session->flashdata('iduser'); 
                                        ?>" name="iduser" autofocus>
                            -->
                            <select name="iduser" id="iduser" class="form-control" required="required">
                                <?php foreach ($datauser->result_array() as $u) : ?>
                                    <option value="<?= $u['userid']; ?>"><?= $u['userid']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12">
                            <input class="form-control" type="password" required="" placeholder="Password" name="pass" autofocus="true">
                        </div>
                    </div>

                    <div class="form-group text-center row m-t-20">
                        <div class="col-12">
                            <button class="btn btn-success btn-block waves-effect waves-light" type="submit" onclick="fullscreen();">Log
                                In</button>
                        </div>
                    </div>

                    <div class="form-group m-t-10 mb-0 row">
                        <div class="col-sm-7 m-t-20">
                            <a href="" class="text-muted btnforgotpassword"><i class="mdi mdi-lock"></i>
                                <small>Forgot your password ?</small></a>
                        </div>
                    </div>
                    <?= form_open() ?>
                </div>

            </div>
        </div>
    </div>



    <!-- jQuery  -->
    <script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/popper.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/modernizr.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/waves.js"></script>
    <script src="<?php echo base_url() ?>assets/js/jquery.slimscroll.js"></script>
    <script src="<?php echo base_url() ?>assets/js/jquery.nicescroll.js"></script>
    <script src="<?php echo base_url() ?>assets/js/jquery.scrollTo.min.js"></script>

    <!-- App js -->
    <script src="<?php echo base_url() ?>assets/js/app.js"></script>
    <script>
        const btnForgot = document.querySelector('.btnforgotpassword');
        btnForgot.onclick = () => {
            Swal.fire('Perhatian', 'Silahkan Hubungin Admin, jika lupa password', 'warning');
            return false;
        }
    </script>
</body>

</html>
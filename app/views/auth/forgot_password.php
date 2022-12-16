<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Halaman Ganti Password | <?= $this->config->item('title'); ?></title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Mannatthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url() ?>assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url() ?>assets/css/style.css" rel="stylesheet" type="text/css">

</head>


<body>

    <!-- Begin page -->
    <div class="accountbg"></div>
    <div class="wrapper-page">

        <div class="card">
            <div class="card-body">

                <h3 class="text-center mt-0 m-b-15">
                    Lupa Password ?
                </h3>

                <div class="p-3">
                    <?php echo form_open("auth/forgot_password"); ?>
                    <div id="infoMessage"><?php echo $message; ?></div>
                    <div class="alert alert-info alert-dismissible">
                        <strong>Reset Password</strong> akan dikirimkan ke Email anda. Pastikan Juga
                        <strong>PC/Laptop</strong> anda sudah terkoneksi.
                        internet
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <?php echo form_input($identity, '', ['class' => 'form-control', 'placeholder' => 'Inputkan Username Anda']); ?>
                        </div>
                    </div>

                    <div class="form-group text-center row m-t-20">
                        <div class="col-12">
                            <button class="btn btn-danger btn-block waves-effect waves-light" type="submit">Reset
                                Password</button>
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>

            </div>
        </div>
    </div>


    <!-- jQuery  -->
    <script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/popper.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/js/modernizr.min.js"></script>
    <script src="<?= base_url() ?>assets/js/waves.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery.slimscroll.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery.nicescroll.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery.scrollTo.min.js"></script>

    <!-- App js -->
    <script src="<?= base_url() ?>assets/js/app.js"></script>

</body>

</html>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>
        <?= $this->config->item('title'); ?> | Halaman Login
    </title>
    <meta content="<?= $this->config->item('title'); ?>" name="Novinaldi" />
    <meta content="<?= $this->config->item('title'); ?>" name="Novinaldi" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet" type="text/css">

</head>


<body>


    <!-- Begin page -->
    <div class="accountbg"></div>
    <div class="wrapper-page">

        <div class="card">
            <div class="card-body">

                <h3 class="text-center mt-0 m-b-15">
                    Silahkan Login
                </h3>

                <div class="p-3">
                    <div id="infoMessage"><?php echo $message; ?></div>
                    <?php echo form_open("auth/login", ['class' => 'form-horizontal m-t-20']); ?>
                    <div class="form-group row">
                        <div class="col-12">
                            <?php echo form_input($identity, '', ['class' => 'form-control', 'placeholder' => 'Inputkan ID User Anda', 'required' => 'required', 'autofocus' => 'autofocus']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12">
                            <?php echo form_input($password, '', ['class' => 'form-control', 'placeholder' => 'Inputkan Password', 'required' => 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12">
                            <div class="custom-control custom-checkbox">
                                <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?>
                                <?php echo lang('login_remember_label', 'remember'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center row m-t-20">
                        <div class="col-12">
                            <button type="submit"
                                class="btn btn-danger btn-block waves-effect waves-light">Login</button>
                        </div>
                    </div>

                    <div class="form-group m-t-10 mb-0 row">
                        <div class="col-sm-7 m-t-20">
                            <a href="forgot_password"><small>Forgot your password ?</small></a>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>

            </div>
        </div>
    </div>



    <!-- jQuery  -->
    <script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/popper.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/modernizr.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/waves.js"></script>
    <script src="<?= base_url(); ?>assets/js/jquery.slimscroll.js"></script>
    <script src="<?= base_url(); ?>assets/js/jquery.nicescroll.js"></script>
    <script src="<?= base_url(); ?>assets/js/jquery.scrollTo.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

</body>

</html>
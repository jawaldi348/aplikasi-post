<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <title>Login | <?= $bisnis['bisnis'] ?></title>
    <link rel="shortcut icon" href="<?= $bisnis['logo'] ?>">
    <link href="<?= assets() ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= assets() ?>css/icons.css" rel="stylesheet">
    <link href="<?= assets() ?>css/style.css" rel="stylesheet">
    <link href="<?= assets() ?>plugins/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="<?= assets() ?>src/css/style.css" rel="stylesheet">
    <style>
        .accountbg {
            background: url("<?= assets() . 'images/bg-login.jpg' ?>");
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
    <div class="accountbg"></div>
    <div class="wrapper-page">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mt-0 m-b-15">
                    <a href="<?= site_url() ?>" class="logo logo-admin">
                        <img src="<?= $bisnis['logo'] ?>" height="94" alt="<?= $bisnis['bisnis'] ?>">
                    </a>
                </h3>
                <div class="p-3">
                    <?= form_open('login/validasi-user', ['class' => 'auth-login-form', 'autocomplete' => 'off']) ?>
                    <div class="form-group">
                        <input type="text" name="username" id="username" class="form-control" placeholder="Username" autofocus>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="form-group text-center row m-t-20">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success btn-block waves-effect waves-light btn_signin">Log In</button>
                        </div>
                    </div>
                    <div class="form-group m-t-10 mb-0 row">
                        <div class="col-sm-7 m-t-20">
                            <a href="" class="text-muted btnforgotpassword"><i class="mdi mdi-lock"></i><small>Forgot your password ?</small></a>
                        </div>
                    </div>
                    <?= form_open() ?>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= assets() ?>js/jquery.min.js"></script>
    <script src="<?= assets() ?>js/popper.min.js"></script>
    <script src="<?= assets() ?>js/bootstrap.min.js"></script>
    <script src="<?= assets() ?>js/modernizr.min.js"></script>
    <script src="<?= assets() ?>js/waves.js"></script>
    <script src="<?= assets() ?>js/jquery.slimscroll.js"></script>
    <script src="<?= assets() ?>js/jquery.nicescroll.js"></script>
    <script src="<?= assets() ?>js/jquery.scrollTo.min.js"></script>
    <script src="<?= assets() ?>plugins/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="<?= assets() ?>js/app.js"></script>
    <script>
        const btnForgot = document.querySelector('.btnforgotpassword');
        btnForgot.onclick = () => {
            Swal.fire('Perhatian', 'Silahkan Hubungin Admin, jika lupa password', 'warning');
            return false;
        }

        var BASE_URL = "<?= site_url() ?>";

        $(document).ready(function() {
            $('.auth-login-form').on('submit', function(event) {
                event.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    beforeSend: function() {
                        $('.btn_signin').button('loading');
                    },
                    success: function(resp) {
                        $('.form-control').removeClass('error');
                        $('.error-text').remove();
                        if (resp.status == false) {
                            $('.form-control').removeClass('input-error');
                            if (resp.error != null) {
                                if (resp.error.username != '') {
                                    $('#username').addClass('input-error');
                                    $('#username').after('<div class="error-text">' + resp.error.username + '</div>');
                                }
                                if (resp.error.password != null) {
                                    $('#password').addClass('input-error');
                                    $('#password').after('<div class="error-text">' + resp.error.password + '</div>');
                                }
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: resp.message,
                            }).then(okay => {
                                if (okay) {
                                    window.location.href = BASE_URL;
                                }
                            });
                        }
                        $('.btn_signin').button('reset');
                    }
                })
            });
        });
    </script>
</body>

</html>
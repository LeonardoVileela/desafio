<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sicredi | Assinatura</title>
    <link rel="icon" type="image/png" href="<?php echo BASE_URL_PUBLIC; ?>images/sicredi.png">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo BASE_URL_PUBLIC; ?>plugins/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo BASE_URL_PUBLIC; ?>plugins/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <img src="<?php echo BASE_URL_PUBLIC; ?>images/sicredi-logo.png" alt="Logo"
             width="150">
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Faça login para iniciar sua sessão</p>

            <form action="<?php echo BASE_URL; ?>/login" method="post">
                <div class="input-group mb-3">
                    <input name="email" type="email" class="form-control" placeholder="E-mail">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input name="password" type="password" class="form-control" placeholder="Senha">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                    </div>
                </div>
            </form>

        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo BASE_URL_PUBLIC; ?>plugins/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo BASE_URL_PUBLIC; ?>plugins/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo BASE_URL_PUBLIC; ?>plugins/adminlte.min.js"></script>
</body>
</html>

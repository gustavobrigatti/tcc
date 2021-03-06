<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>FalaAÍ</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="login-page bg-blue">
<div class="login-box">
    <div class="logo">
        <a href="javascript:void(0);">Fala<b>AÍ</b></a>
        <small>Portal Estudantil</small>
    </div>
    <div class="card">
        <div class="body">
            <form id="sign_in" action="{{ route('user.login') }}" method="POST">
                {{ csrf_field() }}
                <div class="msg">
                    Entre para iniciar sua sessão
                    @if($errors->any())
                        <br>
                        <span style="color: #F44336">{{ $errors->first() }}</span>
                    @endif
                </div>
                <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                    <div class="form-line">
                        <input type="email" class="form-control" name="email" placeholder="E-mail" required autofocus>
                    </div>
                </div>
                <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                    <div class="form-line">
                        <input type="password" class="form-control" name="password" placeholder="Senha" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-8 p-t-5 invisible">
                        <input type="checkbox" id="rememberme" class="filled-in chk-col-pink">
                        <label for="rememberme">Remember Me</label>
                    </div>
                    <div class="col-xs-4">
                        <button class="btn btn-block bg-red waves-effect" type="submit">ENTRAR</button>
                    </div>
                </div>
                <div class="row m-t-15 m-b--20">
                    <div class="col-xs-6 invisible">
                        <a href="sign-up.html">Register Now!</a>
                    </div>
                    <div class="col-xs-6 align-right">
                        <a style="cursor:pointer; color: #2196f3" data-toggle="modal" data-target="#mdModal">Esqueceu a senha?</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- For Material Design Colors -->
<div class="modal fade" id="mdModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-col-blue">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Esqueceu a senha?</h4>
            </div>
            <div class="modal-body" style="text-align: justify">
                Caso sua senha ainda não tenha sido modificada, ela será as 3 primeiras letras minúsculas do seu nome, somado aos 3 primeiros
                dígitos do seu CPF.<br><br>
                Exemplo: <b>gus123</b><br><br>
                Se você modificou sua senha e esqueceu, entre em contato com alguém da direção, biblioteca ou secretaria para que sua senha
                seja resetada para a padrão.
            </div>
            <div class="modal-footer" >
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">FECHAR</button>
            </div>
        </div>
    </div>
</div>

<!-- Jquery Core Js -->
<script src="plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap Core Js -->
<script src="plugins/bootstrap/js/bootstrap.js"></script>

<!-- Waves Effect Plugin Js -->
<script src="plugins/node-waves/waves.js"></script>

<!-- Validation Plugin Js -->
<script src="plugins/jquery-validation/jquery.validate.js"></script>

<!-- Custom Js -->
<script src="js/admin.js"></script>
<script src="js/pages/ui/modals.js"></script>
<script src="js/pages/examples/sign-in.js"></script>
<!-- Demo Js -->
<script src="js/demo.js"></script>
</body>

</html>

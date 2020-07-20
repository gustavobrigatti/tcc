<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <base href="{{ url('') }}"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>{{ config('app.name') }}</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
          type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet"/>

    <!-- Animation Css -->
    <link href="plugins/animate-css/animate.css" rel="stylesheet"/>

@stack('head')

<!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="css/themes/all-themes.css" rel="stylesheet"/>
</head>

<body class="theme-blue">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="preloader">
            <div class="spinner-layer pl-blue">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <p>Aguarde...</p>
    </div>
</div>
<!-- #END# Page Loader -->
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- Search Bar -->
<div class="search-bar">
    <div class="search-icon">
        <i class="material-icons">search</i>
    </div>
    <input type="text" placeholder="PROCURAR...">
    <div class="close-search">
        <i class="material-icons">close</i>
    </div>
</div>
<!-- #END# Search Bar -->
<!-- Top Bar -->
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle js-search"></a>
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="{{ route('index') }}">{{ config('app.name') }}</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <!-- Call Search -->
                <li><a href="javascript:void(0);" class="js-search" data-close="true"><i
                            class="material-icons">search</i></a></li>
                <!-- #END# Call Search -->
            </ul>
        </div>
    </div>
</nav>
<!-- #Top Bar -->
<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">
            <div class="image">
                <a href="{{ route('user.perfil.show', Auth::user()->hash_id) }}">
                    @if($exists = Storage::disk('public')->exists('users/'.\Illuminate\Support\Facades\Auth::user()->id.'.png'))
                        <img src="/storage/users/{{ \Illuminate\Support\Facades\Auth::user()->id }}.png" width="48"
                             height="48" alt="User"/>
                    @else
                        <img src="images/user.png" width="48" height="48" alt="User"/>
                    @endif
                </a>
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true"
                     aria-expanded="false">{{ \Illuminate\Support\Facades\Auth::user()->name }}</div>
                <div class="email">{{ \Illuminate\Support\Facades\Auth::user()->email }}</div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="{{ route('user.perfil.show', \Illuminate\Support\Facades\Auth::user()->hash_id) }}"><i
                                    class="material-icons">person</i>Perfil</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('logout') }}"><i class="material-icons">input</i>Sair</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="header">NAVEGAÇÃO PRINCIPAL</li>
                <li class="{{ \Request::route()->getName() == 'inbox.index' ? 'active':'' }}">
                    <a href="{{ route('index') }}">
                        <i class="material-icons">home</i>
                        <span>Início</span>
                    </a>
                </li>
                @if(\Illuminate\Support\Facades\Auth::user()->role == 100 || \Illuminate\Support\Facades\Auth::user()->role == 200)
                    <li class="{{ isset($ad) ? 'active':'' }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">settings</i>
                            <span>Administrador</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="{{ substr(\Request::route()->getName(), 0, 4) == 'user' ? 'active':""}}">
                                <a href="javascript:void(0);" class="menu-toggle">
                                    <span>Usuários</span>
                                </a>
                                <ul class="ml-menu">
                                    <li class="{{ \Request::route()->getName() == 'user.index' ? 'active':"" }}">
                                        <a href="{{ route('user.index') }}">Listar usuários</a>
                                    </li>
                                    <li class="{{ \Request::route()->getName() == 'user.edit' && $id == 0 ? 'active':"" }}">
                                        <a href="{{ route('user.edit', 0) }}">Adicionar usuário</a>
                                    </li>
                                </ul>

                            </li>
                            @if(\Illuminate\Support\Facades\Auth::user()->role == 100)
                                <li class="{{ substr(\Request::route()->getName(), 0, 11) == 'instituicao' ? 'active':""}}">
                                    <a href="javascript:void(0);" class="menu-toggle">
                                        <span>Instituições</span>
                                    </a>
                                    <ul class="ml-menu">
                                        <li class="{{ \Request::route()->getName() == 'instituicao.index' ? 'active':"" }}">
                                            <a href="{{ route('instituicao.index') }}">Listar instituições</a>
                                        </li>
                                        <li class="{{ \Request::route()->getName() == 'instituicao.edit' && $id == 0 ? 'active':"" }}">
                                            <a href="{{ route('instituicao.edit', 0) }}">Adicionar instituição</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            @if(\Illuminate\Support\Facades\Auth::user()->role == 200)
                                <li class="{{ substr(\Request::route()->getName(), 0, 5) == 'turma' ? 'active':""}}">
                                    <a href="javascript:void(0);" class="menu-toggle">
                                        <span>Turmas</span>
                                    </a>
                                    <ul class="ml-menu">
                                        <li class="{{ \Request::route()->getName() == 'turma.index' ? 'active':"" }}">
                                            <a href="{{ route('turma.index') }}">Listar turmas</a>
                                        </li>
                                        <li class="{{ \Request::route()->getName() == 'turma.edit' && $id == 0 ? 'active':"" }}">
                                            <a href="{{ route('turma.edit', 0) }}">Adicionar turma</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="{{ substr(\Request::route()->getName(), 0, 4) == 'aula' ? 'active':""}}">
                                    <a href="javascript:void(0);" class="menu-toggle">
                                        <span>Aulas</span>
                                    </a>
                                    <ul class="ml-menu">
                                        <li class="{{ \Request::route()->getName() == 'aula.index' ? 'active':"" }}">
                                            <a href="{{ route('aula.index') }}">Listar aulas</a>
                                        </li>
                                        <li class="{{ \Request::route()->getName() == 'aula.edit' && $id == 0 ? 'active':"" }}">
                                            <a href="{{ route('aula.edit', 0) }}">Adicionar aula</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if(\Illuminate\Support\Facades\Auth::user()->role == '400' || \Illuminate\Support\Facades\Auth::user()->role == '500')
                    <li class="{{ \Request::route()->getName() == 'grade' ? 'active':'' }}">
                        <a href="{{ route('grade') }}">
                            <i class="material-icons">today</i>
                            <span>Grade de Horários</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="">
                            <i class="material-icons">grade</i>
                            <span>Notas</span>
                        </a>
                    </li>
                @endif
                <li class="{{ \Request::route()->getName() == 'inbox.favoritas' || \Request::route()->getName() == 'inbox.arquivadas' || \Request::route()->getName() == 'inbox.excluidas' || \Request::route()->getName() == 'inbox.enviadas' ? 'active':""}}">
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">email</i>
                        <span>Mensagens</span>
                    </a>
                    <ul class="ml-menu">
                        <li class="{{\Request::route()->getName() == 'inbox.enviadas' ? 'active':""}}">
                            <a href="{{ route('inbox.enviadas') }}">Enviadas</a>
                        </li>
                        <li class="{{\Request::route()->getName() == 'inbox.favoritas' ? 'active':""}}">
                            <a href="{{ route('inbox.favoritas') }}">Favoritas</a>
                        </li>
                        <li class="{{\Request::route()->getName() == 'inbox.arquivadas' ? 'active':""}}">
                            <a href="{{ route('inbox.arquivadas') }}">Arquivadas</a>
                        </li>
                        <li class="{{\Request::route()->getName() == 'inbox.excluidas' ? 'active':""}}">
                            <a href="{{ route('inbox.excluidas') }}">Excluídas</a>
                        </li>
                    </ul>
                </li>
                <li class="{{ substr(\Request::route()->getName(), 0, 5) == 'album' ? 'active':'' }}">
                    <a href="{{ route('album.index') }}">
                        <i class="material-icons">perm_media</i>
                        <span>Galeria</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- #Menu -->
        <!-- Footer -->
        <div class="legal">
            <div class="copyright">
                &copy; 2020 <a href="https://github.com/gustavobrigatti" target="_blank">FalaAÍ - Gustavo Rosolen
                    Brigatti</a>.
            </div>
        </div>
        <!-- #Footer -->
    </aside>
    <!-- #END# Left Sidebar -->
</section>

<section class="content">
    <div class="container-fluid">

        @yield('content')

    </div>
</section>

<!-- Jquery Core Js -->
<script src="plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap Core Js -->
<script src="plugins/bootstrap/js/bootstrap.js"></script>

<!-- Waves Effect Plugin Js -->
<script src="plugins/node-waves/waves.js"></script>

<!-- Slimscroll Plugin Js -->
<script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

@stack('scripts')

<!-- Demo Js -->
<script src="js/demo.js"></script>

</body>

</html>

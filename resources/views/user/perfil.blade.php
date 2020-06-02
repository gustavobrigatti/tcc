@extends('templates.master')

@push('head')
    <!-- Dropzone Css -->
    <link href="plugins/dropzone/dropzone.css" rel="stylesheet">
    <link href="plugins/fab/fab.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- Dropzone Plugin Js -->
    <script src="plugins/dropzone/dropzone.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/examples/profile.js"></script>

    <script src="js/jquery.mask.min.js"></script>

    <script type="text/javascript">
        Dropzone.options.myAwesomeDropzone = {
            renameFile: function (file) {
                var dt = new Date();
                var time = dt.getTime();
                return time + file.name;
            },
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            maxFiles: 1,
            addRemoveLinks: true,
            timeout: 5000,
            dictRemoveFile: 'Remover arquivo',
            dictFallbackMessage: "Seu navegador não suporta arrastar e soltar fotos",
            dictFallbackText: "Por favor, use o botão para selecionar fotos",
            dictInvalidFileType: "Você não pode carregar arquivos deste tipo.",
            dictCancelUpload: "Cancelar upload",
            dictCancelUploadConfirmation: "Tem certeza de que deseja cancelar este upload?",
            dictMaxFilesExceeded: "Você não pode carregar mais arquivos.",
            autoProcessQueue: false,
            accept: function (file, done) {
                console.log("uploaded");
                console.log(file);
                done();
            },
            init: function () {
                var myDropzone = this;

                // Update selector to match your button
                $("#button").click(function (e) {
                    e.preventDefault();
                    myDropzone.processQueue();
                    $("#form_perfil").submit();
                });

                this.on('sending', function (file, xhr, formData) {
                    // Append all form inputs to the formData Dropzone will POST
                    var data = $('#myAwesomeDropzone').serializeArray();
                    $.each(data, function (key, el) {
                        formData.append(el.name, el.value);
                    });
                });

                this.on("maxfilesexceeded", function (file) {
                    this.removeFile(file);
                    alert("Você não pode carregar mais arquivos!");
                });
            },
        };

        $("#envioPerfil").click(function (e) {
            e.preventDefault();
            myDropzone.processQueue();
        });

        document.addEventListener('DOMContentLoaded', function () {
            var SPMaskBehavior = function (val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                spOptions = {
                    onKeyPress: function (val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };

            $('.mask-tel').mask(SPMaskBehavior, spOptions);
        }, false);
    </script>
@endpush

@section('content')
    @if(\Illuminate\Support\Facades\Auth::user()->id != $user->id && (\Illuminate\Support\Facades\Auth::user()->role == 100 || \Illuminate\Support\Facades\Auth::user()->role == 200))
        <div class="fab">
            <button class="main">
                <a href="{{ route('user.edit', $user->hash_id) }}" style="width: 100%; height: 100%">
                    <i class="material-icons" style="padding-top: 8px; color: #fff">create</i>
                </a>
            </button>
        </div>
    @endif
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-{{ $user->id == \Illuminate\Support\Facades\Auth::user()->id ? '4':'12'}}">
            <div class="card profile-card card-about-me">
                <div class="profile-header">&nbsp;</div>
                <div class="profile-body">
                    <div class="image-area">
                        @if($exists = Storage::disk('public')->exists('users/'.$user->id.'.png'))
                            <img src="/storage/users/{{ $user->id }}.png" style="width: 128px; height: 128px;"
                                 alt="AdminBSB - Profile Image"/>
                        @else
                            <img src="/images/user.png" style="width: 128px; height: 128px;"
                                 alt="AdminBSB - Profile Image"/>
                        @endif
                    </div>
                    <div class="content-area">
                        <h3>{{ $user->name }}</h3>
                        <p>{{ $user->instituicao->nome }}</p>
                        @if($user->role == 100)
                            <p>Administrador</p>
                        @elseif($user->role == 200)
                            <p>Direção/Coordenação</p>
                        @elseif($user->role == 300)
                            <p>Bibliotecári{{ $user->genero == 'M' ? 'o':'a' }}</p>
                        @elseif($user->role == 400)
                            <p>Professor{{ $user->genero == 'F' ? 'a':'' }}</p>
                        @elseif($user->role == 500)
                            <p>Alun{{ $user->genero == 'M' ? 'o':'a' }}</p>
                        @elseif($user->role == 600)
                            <p>Responsável</p>
                        @endif
                    </div>
                </div>
                <div class="body">
                    <ul>
                        <li>
                            <div class="title">
                                <i class="material-icons">person</i>
                                Contato
                            </div>
                            <div class="content">
                                {{ $user->telefone }}<br>
                                {{ $user->email }}
                            </div>
                        </li>@if($user->role == 500 || $user->role == 600)
                            <li>
                                <div class="title">
                                    <i class="material-icons">group</i>
                                    {{ $user->role == 500 ? 'Responsáveis':'Alunos' }}
                                </div>
                                <div class="content">
                                    @if($user->role == 500)
                                        {{ implode(', ', $user->responsaveis->pluck('name')->all()) }}
                                    @else
                                        {{ implode(', ', $user->alunos->pluck('name')->all()) }}
                                    @endif
                                </div>
                            </li>
                        @endif
                        @if($user->role == 400 || $user->role == 500)
                            <li>
                                <div class="title">
                                    <i class="material-icons">group</i>
                                    Turmas
                                </div>
                                <div class="content">
                                    @if($user->role == 400)
                                        {{ implode(', ', $user->professor_turmas->pluck('nome')->all()) }}
                                    @else
                                        {{ implode(', ', $user->aluno_turmas->pluck('nome')->all()) }}
                                    @endif
                                </div>
                            </li>
                        @endif
                        <li>
                            <div class="title">
                                <i class="material-icons">location_on</i>
                                Localização
                            </div>
                            <div class="content">
                                {{ $user->cidade }}/{{ $user->estado }}
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @if($user->id == \Illuminate\Support\Facades\Auth::user()->id)
            <div class="col-xs-12 col-sm-8">
                <div class="card">
                    <div class="body">
                        <div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#profile_settings"
                                                                          aria-controls="settings"
                                                                          role="tab" data-toggle="tab">Informações</a>
                                </li>
                                <li role="presentation"><a href="#change_password_settings" aria-controls="settings"
                                                           role="tab" data-toggle="tab">Alterar Senha</a></li>
                            </ul>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="profile_settings">
                                    <form class="form-horizontal" method="post" id="form_perfil"
                                          action="{{ route('user.perfil', $user->hash_id) }}">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="nome" class="col-sm-2 control-label">Nome Completo</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" id="nome" name="nome"
                                                           placeholder="Nome Completo"
                                                           value="{{ old('nome', $user->name) }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="email" class="col-sm-2 control-label">E-mail</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    <input type="email" class="form-control" id="email" name="email"
                                                           placeholder="E-mail" value="{{ old('email', $user->email) }}"
                                                           required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="telefone" class="col-sm-2 control-label">Telefone</label>

                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    <input type="text" class="form-control mask-tel" id="telefone"
                                                           name="telefone" placeholder="Telefone"
                                                           value="{{ old('telefone', $user->telefone) }}" required>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <center>
                                        <form action="{{ route('user.perfil', $user->hash_id) }}"
                                              id="my-awesome-dropzone"
                                              class="dropzone" method="post" enctype="multipart/form-data"
                                              style="width: 95%">
                                            {{ csrf_field() }}
                                            <div class="dz-message">
                                                <div class="drag-icon-cph">
                                                    <i class="material-icons">touch_app</i>
                                                </div>
                                                <h3>Solte sua foto aqui ou clique para fazer o upload.</h3>
                                                <em>(A largura e a altura da foto devem ter as <strong>mesmas</strong>
                                                    dimensões.)</em>
                                            </div>
                                        </form>
                                    </center>
                                    <br>
                                    <div class="col-sm-12">
                                        <button type="submit" id="button" class="btn btn-block btn-danger">ATUALIZAR
                                        </button>
                                    </div>
                                    <br>
                                </div>

                                <div role="tabpanel" class="tab-pane fade in" id="change_password_settings">
                                    <form class="form-horizontal" method="post"
                                          action="{{ route('user.senha', $user->hash_id) }}">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="oldPassword" class="col-sm-3 control-label">Senha Antiga</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="password" class="form-control" id="oldPassword"
                                                           name="oldPassword" placeholder="Senha Antiga" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="newPassword" class="col-sm-3 control-label">Nova Senha</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="password" class="form-control" id="newPassword"
                                                           name="newPassword" placeholder="Nova Senha" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="newPasswordConfirm" class="col-sm-3 control-label">Nova Senha
                                                (Confirmar)</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="password" class="form-control" id="newPasswordConfirm"
                                                           name="newPasswordConfirm"
                                                           placeholder="Nova Senha (Confirmar)"
                                                           required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <button type="submit" class="btn btn-danger">ALTERAR SENHA</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

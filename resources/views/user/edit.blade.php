@extends('templates.master')

@push('head')
    <!-- Sweet Alert Css -->
    <link href="plugins/sweetalert/sweetalert.css" rel="stylesheet"/>

    <!-- Bootstrap Select Css -->
    <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet"/>
@endpush

@push('scripts')
    <!-- Select Plugin Js -->
    <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Jquery Validation Plugin Css -->
    <script src="plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/forms/form-validation.js"></script>
    <script src="js/pages/forms/advanced-form-elements.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <script type="text/javascript">
        var estados = [];

        function loadEstados(element) {
            if (estados.length > 0) {
                putEstados(element);
                $(element).removeAttr('disabled').selectpicker('refresh');
                $('#uf').trigger('change');
            } else {
                $.ajax({
                    url: '/js/estados.json',
                    method: 'get',
                    dataType: 'json',
                    beforeSend: function () {
                        $(element).html('<option>Carregando...</option>').selectpicker('refresh');
                    }
                }).done(function (response) {
                    estados = response.estados;
                    putEstados(element);
                    $(element).removeAttr('disabled').selectpicker('refresh');
                    if ('{{ $id }}' > 0) {
                        $('#uf').trigger('change');
                    } else {
                        var estadoSel = '{{ old('uf') }}';
                        if (estadoSel.length > 0) {
                            $('#uf').trigger('change');
                        }
                    }
                });
            }
        }

        function putEstados(element) {

            var label = $(element).data('label');
            label = label ? label : 'Estado';

            var options = '<option value="">' + label + '</option>';
            for (var i in estados) {
                var estado = estados[i];
                var estadoSel = '{{ old('uf', $user->estado) }}';
                options += '<option value="' + estado.sigla + '"' + (estado.sigla == estadoSel ? ' selected' : '') + '>' + estado.nome + '</option>';
            }

            $(element).html(options).selectpicker('refresh');
        }

        function loadCidades(element, estado_sigla) {
            if (estados.length > 0) {
                putCidades(element, estado_sigla);
                $(element).removeAttr('disabled').selectpicker('refresh');
            } else {
                $.ajax({
                    url: theme_url + '/assets/json/estados.json',
                    method: 'get',
                    dataType: 'json',
                    beforeSend: function () {
                        $(element).html('<option>Carregando...</option>').selectpicker('refresh');
                    }
                }).done(function (response) {
                    estados = response.estados;
                    putCidades(element, estado_sigla);
                    $(element).removeAttr('disabled').selectpicker('refresh');
                });
            }
        }

        function putCidades(element, estado_sigla) {
            var label = $(element).data('label');
            label = label ? label : 'Cidade';

            var options = '<option value="">' + label + '</option>';
            for (var i in estados) {
                var estado = estados[i];
                if (estado.sigla != estado_sigla)
                    continue;
                for (var j in estado.cidades) {
                    var cidade = estado.cidades[j];
                    var cidadeSel = "{!! old('cidade', $user->cidade) !!}";
                    options += '<option value="' + cidade + '"' + (cidade == cidadeSel ? ' selected' : '') + '>' + cidade + '</option>';
                }
            }
            $(element).html(options).selectpicker('refresh');
        }

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
            $('.cpf').mask('000.000.000-00', {reverse: true});
            $('.dateBR').mask('00/00/0000');
            $('#div-responsaveis').hide();
            $('#div-alunos').hide();
            if ('{{ $user->id }}' > 0) {
                if ('{{ $user->role }}' === '500') {
                    $('#div-responsaveis').show();
                } else if ('{{ $user->role }}' === '600') {
                    $('#div-alunos').show();
                }
            }
            loadEstados('#uf');
            $(document).on('change', '#uf', function (e) {
                var target = $(this).data('target');
                if (target) {
                    loadCidades(target, $(this).val());
                }
            });
            $(document).on('change', '#role', function() {
                if (this.value === '100'){
                    $('#div-responsaveis').hide();
                    $('#div-alunos').hide();
                }else if(this.value === '200'){
                    $('#div-responsaveis').hide();
                    $('#div-alunos').hide();
                }else if(this.value === '300'){
                    $('#div-responsaveis').hide();
                    $('#div-alunos').hide();
                }else if(this.value === '400'){
                    $('#div-responsaveis').hide();
                    $('#div-alunos').hide();
                }else if(this.value === '500'){
                    $('#div-responsaveis').show();
                    $('#div-alunos').hide();
                }else if(this.value === '600'){
                    $('#div-responsaveis').hide();
                    $('#div-alunos').show();
                }
            });
        }, false);
    </script>
@endpush

@section('content')

    <!-- Advanced Select -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        USUÁRIO
                    </h2>
                    @if($id > 0)
                        <form id="excluir"
                              action="{{ route('user.destroy', $user->hash_id)  }}"
                              method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE"/>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
                                       role="button"
                                       aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="{{ route('user.show', $user->hash_id) }}">Perfil</a>
                                            <a href="{{ route('user.resetarSenha', $user->hash_id) }}">Resetar Senha</a>
                                            <a href="javascript:document.getElementById('excluir').submit();">Excluir</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </form>
                    @endif
                </div>
                <div class="body">
                    <form id="form_advanced_validation"
                          action="{{ route($id > 0 ? 'user.update' : 'user.store', \Vinkla\Hashids\Facades\Hashids::encode($id)) }}"
                          method="post">
                        {{ $id > 0 ? method_field('PUT') : '' }}
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="nome"
                                           value="{{ old('nome', $user->name) }}"
                                           required>
                                    <label class="form-label">Nome do Usuário</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <p><b>Estado</b></p>
                                    <select name="uf" id="uf" class="form-control show-tick"
                                            data-live-search="true" data-target="#cidade" disabled required>
                                        <option value="">Estado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <p><b>Cidade</b></p>
                                    <select name="cidade" id="cidade" class="form-control show-tick"
                                            data-live-search="true" disabled required>
                                        <option value="">Por favor, selecione um estado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="endereco"
                                           value="{{ old('endereco', $user->endereco) }}" required>
                                    <label class="form-label">Endereço</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <p><b>&nbsp;</b></p>
                                <div class="form-line">
                                    <input type="text" class="form-control dateBR" name="data_nascimento"
                                           value="{{ old('data_nascimento', optional($user->data_nascimento)->format('d/m/Y')) }}"
                                           required>
                                    <label class="form-label">Data de Nascimento</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <p><b>Tipo</b></p>
                                    <select name="role" id="role" class="form-control show-tick" required>
                                        <option value="" selected disabled>Selecione o tipo de usuário</option>
                                        @if(\Illuminate\Support\Facades\Auth::user()->role == 100)
                                            <option value="100" {{ old('role', $user->role) == '100' ? 'selected':'' }}>
                                                Administrador
                                            </option>
                                        @endif
                                        <option value="200" {{ old('role', $user->role) == '200' ? 'selected':'' }}>
                                            Direção/Coordenação
                                        </option>
                                        <option value="300" {{ old('role', $user->role) == '300' ? 'selected':'' }}>
                                            Bibliotecário(a)
                                        </option>
                                        <option value="400" {{ old('role', $user->role) == '400' ? 'selected':'' }}>
                                            Professor(a)
                                        </option>
                                        <option value="500" {{ old('role', $user->role) == '500' ? 'selected':'' }}>
                                            Aluno(a)
                                        </option>
                                        <option value="600" {{ old('role', $user->role) == '600' ? 'selected':'' }}>
                                            Responsável
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="div-responsaveis">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <p><b>Responsáveis</b></p>
                                    <select name="responsaveis[]" id="responsaveis" class="form-control show-tick"
                                            data-live-search="true" multiple>
                                        @foreach($responsaveis as $responsavel)
                                            <option value="{{ $responsavel->id }}" {{ in_array($responsavel->id, $user->responsaveis->pluck('id')->all()) ? 'selected':'' }}>{{ $responsavel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="div-alunos">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <p><b>Alunos</b></p>
                                    <select name="alunos[]" id="alunos" class="form-control show-tick"
                                            data-live-search="true" multiple>
                                        @foreach($alunos as $aluno)
                                            <option value="{{ $aluno->id }}" {{ in_array($aluno->id, $user->alunos->pluck('id')->all()) ? 'selected':'' }}>{{ $aluno->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="email" class="form-control" name="email"
                                           value="{{ old('email', $user->email) }}" required>
                                    <label class="form-label">E-mail</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control mask-tel" name="telefone"
                                           value="{{ old('telefone', $user->telefone) }}" required>
                                    <label class="form-label">Telefone/Celular</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" name="cpf" class="form-control cpf"
                                           value="{{ old('cpf', $user->cpf) }}" required>
                                    <label class="form-label">CPF</label>
                                </div>
                            </div>
                        </div>
                        @if(\Illuminate\Support\Facades\Auth::user()->role == 100)
                            <div class="col-md-12">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <p><b>Instituição</b></p>
                                        <select name="escola_id" class="form-control show-tick"
                                                data-live-search="true" required>
                                            <option value="" selected disabled>Selecione uma instituição</option>
                                            @foreach($instituicoes as $instituicao)
                                                <option
                                                    value="{{ $instituicao->id }}" {{ old('escola_id', $user->escola_id) == $instituicao->id ? 'selected':'' }}>{{ $instituicao->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @elseif(\Illuminate\Support\Facades\Auth::user()->role == 200)
                            <input type="hidden" name="escola_id"
                                   value="{{ \Illuminate\Support\Facades\Auth::user()->escola_id }}">
                        @endif
                        <div class="col-md-12">
                            <div class="form-group form-float">
                                <p><b>Gênero</b></p>
                                <input type="radio" name="gender" id="male" class="with-gap radio-col-blue"
                                       value="M" {{ old('gender', $user->genero) == 'M' ? 'checked':'' }}>
                                <label for="male">Masculino</label>

                                <input type="radio" name="gender" id="female" class="with-gap radio-col-blue"
                                       value="F" {{ old('gender', $user->genero) == 'F' ? 'checked':'' }}>
                                <label for="female" class="m-l-20">Feminino</label>
                            </div>
                        </div>
                        <div style="width: 100%">
                            <button class="btn btn-primary waves-effect" style="width: 100%" type="submit">CADASTRAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Advanced Select -->

@endsection

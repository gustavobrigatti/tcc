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
                var estadoSel = '{{ old('uf', $instituicao->estado) }}';
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
                    var cidadeSel = "{!! old('cidade', $instituicao->cidade) !!}";
                    options += '<option value="' + cidade + '"' + (cidade == cidadeSel ? ' selected' : '') + '>' + cidade + '</option>';
                }
            }
            $(element).html(options).selectpicker('refresh');
        }

        document.addEventListener('DOMContentLoaded', function () {
            $('.phone_with_ddd').mask('(00) 0000-0000');
            $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
            loadEstados('#uf');
            $(document).on('change', '#uf', function (e) {
                var target = $(this).data('target');
                if (target) {
                    loadCidades(target, $(this).val());
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
                        INSTITUIÇÃO
                    </h2>
                    @if($id > 0)
                        <form id="excluir"
                              action="{{ route('instituicao.destroy', \Vinkla\Hashids\Facades\Hashids::encode($id)) }}"
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
                          action="{{ route($id > 0 ? 'instituicao.update' : 'instituicao.store', \Vinkla\Hashids\Facades\Hashids::encode($id)) }}"
                          method="post">
                        {{ $id > 0 ? method_field('PUT') : '' }}
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="nome"
                                           value="{{ old('nome', $instituicao->nome) }}"
                                           required>
                                    <label class="form-label">Nome da Instituição</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <p><b>Estado</b></p>
                                <select name="uf" id="uf" class="form-control show-tick"
                                        data-live-search="true" data-target="#cidade" disabled required>
                                    <option value="">Estado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <p><b>Cidade</b></p>
                                <select name="cidade" id="cidade" class="form-control show-tick"
                                        data-live-search="true" disabled required>
                                    <option value="">Por favor, selecione um estado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="endereco"
                                           value="{{ old('endereco', $instituicao->endereco) }}" required>
                                    <label class="form-label">Endereço</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="email" class="form-control" name="email"
                                           value="{{ old('email', $instituicao->email) }}" required>
                                    <label class="form-label">E-mail</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control phone_with_ddd" name="telefone"
                                           value="{{ old('telefone', $instituicao->telefone) }}" required>
                                    <label class="form-label">Telefone</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" name="cnpj" class="form-control cnpj"
                                           value="{{ old('cnpj', $instituicao->cnpj) }}" required>
                                    <label class="form-label">CNPJ</label>
                                </div>
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

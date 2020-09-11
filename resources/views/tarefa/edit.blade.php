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

        $('#turma').on('change', function() {
            if ($('#turma').val() !== "0") {
                var turma = $('#turma').val();
                $.post('/tarefa/buscaAulas', {turma}, function (data) {
                    $('#aula').empty().selectpicker('refresh');;
                    $('#aula').append('<option value="0" disabled>Selecione a aula</option>').selectpicker('refresh');
                    for (item in data.aulas) {
                        $('#aula').append($('<option>', {
                            value: data.aulas[item].id,
                            text: data.aulas[item].nome
                        })).selectpicker('refresh');
                    }
                    $('#aula').prop('disabled', false).selectpicker('refresh');
                });
            }
        });
        document.addEventListener('DOMContentLoaded', function () {
            $('.dateBR').mask('00/00/0000');
        });
    </script>
@endpush

@section('content')

    <!-- Advanced Select -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ $id > 0 ? $tarefa->nome : 'TAREFA' }}
                    </h2>
                    @if($id > 0)
                        <form id="excluir"
                              action="{{ route('tarefa.destroy', $tarefa->hash_id)  }}"
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
                    <form id="form_advanced_validation" action="{{ route($id > 0 ? 'tarefa.update' : 'tarefa.store', $tarefa->hash_id) }}" method="post">
                        {{ $id > 0 ? method_field('PUT') : '' }}
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <div class="col-xs-8">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="nome"
                                               value="{{ old('nome', $tarefa->nome) }}"
                                               required>
                                        <label class="form-label">Nome da Tarefa</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control dateBR" name="data_entrega" value="{{ old('data_entrega', optional($tarefa->data_entrega)->format('d/m/Y')) }}" required>
                                        <label class="form-label">Data de Entrega</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <textarea rows="4" name="descricao" class="form-control no-resize" placeholder="Descrição da tarefa..." required>{{ $tarefa->descricao }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <p><b>Turma</b></p>
                                        <select name="turma_id" id="turma" class="form-control show-tick" data-live-search="true" required>
                                            <option value="0" selected disabled>Selecione a turma</option>
                                            @foreach($turmas as $turma)
                                                <option value="{{ $turma->id }}" {{ old('turma_id', $tarefa->turma_id) == $turma->id ? 'selected':'' }}>{{ $turma->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group form-float">
                                    <div class="form-line" id="div-aula">
                                        <p><b>Aula</b></p>
                                        <select name="aula_id" id="aula" class="form-control show-tick" data-live-search="true" required disabled>
                                            <option value="0" selected disabled>Selecione a aula</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="width: 100%">
                            <button class="btn btn-primary waves-effect" type="submit" style="width: 100%">CADASTRAR
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Advanced Select -->

@endsection

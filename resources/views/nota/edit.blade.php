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
                    $('#aula').empty().selectpicker('refresh');
                    var options = '<option value="0" selected disabled>Selecione a aula</option>';
                    var aulaSel = '{{old('aula_id', $nota->aula_id)}}';
                    for (item in data.aulas) {
                        options += '<option value="' + data.aulas[item].id + '"' + (''+data.aulas[item].id === aulaSel ? ' selected' : '') + '>' + data.aulas[item].nome + '</option>';
                    }
                    $('#aula').html(options).selectpicker('refresh');
                    $('#aula').prop('disabled', false).selectpicker('refresh');
                });
                $.post('/nota/buscaAluno', {turma}, function (data) {
                    $('#user_id').empty().selectpicker('refresh');
                    var options = '<option value="0" selected disabled>Selecione o aluno</option>';
                    var alunoSel = '{{ old('user_id', $nota->user_id) }}';
                    for (item in data.alunos) {
                        options += '<option value="' + data.alunos[item].id + '"' + (''+data.alunos[item].id === alunoSel ? 'selected' : '') + '>' + data.alunos[item].name + '</option>';
                    }
                    $('#user_id').html(options).selectpicker('refresh');
                    $('#user_id').prop('disabled', false).selectpicker('refresh');
                });
            }
        });
        document.addEventListener('DOMContentLoaded', function () {
            $('.nota').mask("00,0", {reverse: true});
            var id = '{{ $id }}';
            if (id !== '0'){
                $('#turma').trigger('change');
            }
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
                        {{ $id > 0 ? $nota->nome : 'NOTA' }}
                    </h2>
                    @if($id > 0)
                        <form id="excluir"
                              action="{{ route('nota.destroy', $nota->hash_id)  }}"
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
                    <form id="form_advanced_validation" action="{{ route($id > 0 ? 'nota.update' : 'nota.store', $nota->hash_id) }}" method="post">
                        {{ $id > 0 ? method_field('PUT') : '' }}
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <div class="col-xs-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="nome"
                                               value="{{ old('nome', $nota->nome) }}"
                                               required>
                                        <label class="form-label">Nome da Nota</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control nota" name="nota"
                                               value="{{number_format(old('nota', $nota->nota), 1, ',', '.') }}"
                                               required>
                                        <label class="form-label">Nota</label>
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
                                                <option value="{{ $turma->id }}" {{ old('turma_id', $nota->turma_id) == $turma->id ? 'selected':'' }}>{{ $turma->nome }}</option>
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
                            <div class="col-xs-12">
                                <div class="form-group form-float">
                                    <div class="form-line" id="div-aula">
                                        <p><b>Aluno</b></p>
                                        <select name="user_id" id="user_id" class="form-control show-tick" data-live-search="true" required disabled>
                                            <option value="0" selected disabled>Selecione o aluno</option>
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

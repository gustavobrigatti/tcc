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

    <!-- Bootstrap Notify Plugin Js -->
    <script src="../../plugins/bootstrap-notify/bootstrap-notify.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/forms/form-validation.js"></script>
    <script src="js/pages/forms/advanced-form-elements.js"></script>

    <script src="js/jquery.mask.min.js"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            $('.hour').mask('00:00');
            var msg = '{{Session::get('alert')}}';
            var exist = '{{Session::has('alert')}}';
            if(exist){
                $.notify({
                    // options
                    message: msg,
                },{
                    // settings
                    type: 'danger',
                    allow_dismiss: false,

                });
            }
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
                        TURMA
                    </h2>
                    @if($id > 0)
                        <form id="excluir"
                              action="{{ route('turma.destroy', $turma->hash_id)  }}"
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
                          action="{{ route($id > 0 ? 'turma.update' : 'turma.store', \Vinkla\Hashids\Facades\Hashids::encode($id)) }}"
                          method="post">
                        {{ $id > 0 ? method_field('PUT') : '' }}
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="nome"
                                           value="{{ old('nome', $turma->nome) }}"
                                           required>
                                    <label class="form-label">Nome da Turma</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <p><b>Alunos</b></p>
                                    <select name="alunos[]" id="alunos" class="form-control show-tick"
                                            data-live-search="true" multiple>
                                        @foreach($alunos as $aluno)
                                            <option value="{{ $aluno->id }}" {{ in_array($aluno->id, $turma->alunos->pluck('id')->all()) ? 'selected':'' }}>{{ $aluno->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <p><b>Escolaridade</b></p>
                                    <select name="escolaridade" class="form-control show-tick" required>
                                        <option value="" selected disabled>Selecione a escolaridade da turma</option>
                                        <option value="Ensino Fundamental" {{ old('escolaridade', $turma->escolaridade) == 'Ensino Fundamental' ? 'selected':'' }}>
                                            Ensino Fundamental
                                        </option>
                                        <option value="Ensino Médio" {{ old('escolaridade', $turma->escolaridade) == 'Ensino Médio' ? 'selected':'' }}>
                                            Ensino Médio
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <p><b>Período</b></p>
                                    <select name="periodo" class="form-control show-tick" required>
                                        <option value="" selected disabled>Selecione o período da turma</option>
                                        <option value="Manhã" {{ old('periodo', $turma->periodo) == 'Manhã' ? 'selected':'' }}>
                                            Manhã
                                        </option>
                                        <option value="Tarde" {{ old('periodo', $turma->periodo) == 'Tarde' ? 'selected':'' }}>
                                            Tarde
                                        </option>
                                        <option value="Noite" {{ old('periodo', $turma->periodo) == 'Noite' ? 'selected':'' }}>
                                            Noite
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-primary btn-block waves-effect" type="submit">CADASTRAR</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Advanced Select -->

    @if($id > 0)
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            AULAS
                        </h2>
                    </div>
                    <div class="body">
                        @if(count($turma->aulas) > 0)
                            <div>
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#segunda" aria-controls="settings" role="tab" data-toggle="tab">Segunda</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#terca" aria-controls="settings" role="tab" data-toggle="tab">Terça</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#quarta" aria-controls="settings" role="tab" data-toggle="tab">Quarta</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#quinta" aria-controls="settings" role="tab" data-toggle="tab">Quinta</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#sexta" aria-controls="settings" role="tab" data-toggle="tab">Sexta</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#sabado" aria-controls="settings" role="tab" data-toggle="tab">Sábado</a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="segunda">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="white-space: nowrap;">
                                                <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Professor</th>
                                                    <th>Horário</th>
                                                    <th class="col-xs-1">Ações</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Professor</th>
                                                    <th>Horário</th>
                                                    <th>Ações</th>
                                                </tr>
                                                </tfoot>
                                                <tbody>
                                                @forelse($turma->aulas->where('dia_semana', 'segunda')->sortBy('hora_inicio') as $aulaTurma)
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            {{ $aulaTurma->aula->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ $aulaTurma->user->name }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ substr($aulaTurma->hora_inicio, 0, 5) }} às {{ substr($aulaTurma->hora_fim, 0, 5) }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;"><a href="javascript:document.getElementById('excluirAula{{ $aulaTurma->id }}').submit();" class="btn btn-primary waves-effect"><i class="material-icons">delete</i></a></td>
                                                        <form id="excluirAula{{ $aulaTurma->id }}"
                                                              action="{{ route('aulaTurma.destroy', $aulaTurma->hash_id)  }}"
                                                              method="POST">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="_method" value="DELETE"/>
                                                        </form>
                                                    </tr>
                                                @empty
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade in" id="terca">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="white-space: nowrap;">
                                                <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Professor</th>
                                                    <th>Horário</th>
                                                    <th class="col-xs-1">Ações</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Professor</th>
                                                    <th>Horário</th>
                                                    <th>Ações</th>
                                                </tr>
                                                </tfoot>
                                                <tbody>
                                                @forelse($turma->aulas->where('dia_semana', 'terca')->sortBy('hora_inicio') as $aulaTurma)
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            {{ $aulaTurma->aula->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ $aulaTurma->user->name }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ substr($aulaTurma->hora_inicio, 0, 5) }} às {{ substr($aulaTurma->hora_fim, 0, 5) }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;"><a href="javascript:document.getElementById('excluirAula{{ $aulaTurma->id }}').submit();" class="btn btn-primary waves-effect"><i class="material-icons">delete</i></a></td>
                                                        <form id="excluirAula{{ $aulaTurma->id }}"
                                                              action="{{ route('aulaTurma.destroy', $aulaTurma->hash_id)  }}"
                                                              method="POST">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="_method" value="DELETE"/>
                                                        </form>
                                                    </tr>
                                                @empty
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade in" id="quarta">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="white-space: nowrap;">
                                                <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Professor</th>
                                                    <th>Horário</th>
                                                    <th class="col-xs-1">Ações</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Professor</th>
                                                    <th>Horário</th>
                                                    <th>Ações</th>
                                                </tr>
                                                </tfoot>
                                                <tbody>
                                                @forelse($turma->aulas->where('dia_semana', 'quarta')->sortBy('hora_inicio') as $aulaTurma)
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            {{ $aulaTurma->aula->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ $aulaTurma->user->name }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ substr($aulaTurma->hora_inicio, 0, 5) }} às {{ substr($aulaTurma->hora_fim, 0, 5) }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;"><a href="javascript:document.getElementById('excluirAula{{ $aulaTurma->id }}').submit();" class="btn btn-primary waves-effect"><i class="material-icons">delete</i></a></td>
                                                        <form id="excluirAula{{ $aulaTurma->id }}"
                                                              action="{{ route('aulaTurma.destroy', $aulaTurma->hash_id)  }}"
                                                              method="POST">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="_method" value="DELETE"/>
                                                        </form>
                                                    </tr>
                                                @empty
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade in" id="quinta">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="white-space: nowrap;">
                                                <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Professor</th>
                                                    <th>Horário</th>
                                                    <th class="col-xs-1">Ações</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Professor</th>
                                                    <th>Horário</th>
                                                    <th>Ações</th>
                                                </tr>
                                                </tfoot>
                                                <tbody>
                                                @forelse($turma->aulas->where('dia_semana', 'quinta')->sortBy('hora_inicio') as $aulaTurma)
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            {{ $aulaTurma->aula->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ $aulaTurma->user->name }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ substr($aulaTurma->hora_inicio, 0, 5) }} às {{ substr($aulaTurma->hora_fim, 0, 5) }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;"><a href="javascript:document.getElementById('excluirAula{{ $aulaTurma->id }}').submit();" class="btn btn-primary waves-effect"><i class="material-icons">delete</i></a></td>
                                                        <form id="excluirAula{{ $aulaTurma->id }}"
                                                              action="{{ route('aulaTurma.destroy', $aulaTurma->hash_id)  }}"
                                                              method="POST">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="_method" value="DELETE"/>
                                                        </form>
                                                    </tr>
                                                @empty
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade in" id="sexta">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="white-space: nowrap;">
                                                <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Professor</th>
                                                    <th>Horário</th>
                                                    <th class="col-xs-1">Ações</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Professor</th>
                                                    <th>Horário</th>
                                                    <th>Ações</th>
                                                </tr>
                                                </tfoot>
                                                <tbody>
                                                @forelse($turma->aulas->where('dia_semana', 'sexta')->sortBy('hora_inicio') as $aulaTurma)
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            {{ $aulaTurma->aula->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ $aulaTurma->user->name }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ substr($aulaTurma->hora_inicio, 0, 5) }} às {{ substr($aulaTurma->hora_fim, 0, 5) }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;"><a href="javascript:document.getElementById('excluirAula{{ $aulaTurma->id }}').submit();" class="btn btn-primary waves-effect"><i class="material-icons">delete</i></a></td>
                                                        <form id="excluirAula{{ $aulaTurma->id }}"
                                                              action="{{ route('aulaTurma.destroy', $aulaTurma->hash_id)  }}"
                                                              method="POST">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="_method" value="DELETE"/>
                                                        </form>
                                                    </tr>
                                                @empty
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade in" id="sabado">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="white-space: nowrap;">
                                                <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Professor</th>
                                                    <th>Horário</th>
                                                    <th class="col-xs-1">Ações</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Professor</th>
                                                    <th>Horário</th>
                                                    <th>Ações</th>
                                                </tr>
                                                </tfoot>
                                                <tbody>
                                                @forelse($turma->aulas->where('dia_semana', 'sabado')->sortBy('hora_inicio') as $aulaTurma)
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            {{ $aulaTurma->aula->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ $aulaTurma->user->name }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ substr($aulaTurma->hora_inicio, 0, 5) }} às {{ substr($aulaTurma->hora_fim, 0, 5) }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;"><a href="javascript:document.getElementById('excluirAula{{ $aulaTurma->id }}').submit();" class="btn btn-primary waves-effect"><i class="material-icons">delete</i></a></td>
                                                        <form id="excluirAula{{ $aulaTurma->id }}"
                                                              action="{{ route('aulaTurma.destroy', $aulaTurma->hash_id)  }}"
                                                              method="POST">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="_method" value="DELETE"/>
                                                        </form>
                                                    </tr>
                                                @empty
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                        @endif
                        <form id="form_advanced_validation" action="{{ route('aulaTurma.store') }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="turma_id" value="{{ $id }}">
                            <div class="col-xs-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <p><b>Aula</b></p>
                                        <select name="aula_id" id="aula" class="form-control show-tick" data-live-search="true" required>
                                            <option value="0" selected disabled>Selecione a aula</option>
                                            @foreach($aulas as $aula)
                                                <option value="{{ $aula->id }}">{{ $aula->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <p><b>Professor</b></p>
                                        <select name="user_id" id="professor" class="form-control show-tick" data-live-search="true" required>
                                            <option value="0" selected disabled>Selecione o professor</option>
                                            @foreach($profesores as $professor)
                                                <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <p><b>Dia da semana</b></p>
                                        <select name="dia_semana" class="form-control show-tick" required>
                                            <option value="0" selected disabled>Selecione o dia da semana</option>
                                            <option value="segunda">Segunda</option>
                                            <option value="terca">Terça</option>
                                            <option value="quarta">Quarta</option>
                                            <option value="quinta">Quinta</option>
                                            <option value="sexta">Sexta</option>
                                            <option value="sabado">Sábado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control hour" name="hora_inicio" required>
                                        <label class="form-label">Hora Início</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control hour" name="hora_fim" required>
                                        <label class="form-label">Hora Fim</label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-primary btn-block waves-effect" type="submit">CADASTRAR</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

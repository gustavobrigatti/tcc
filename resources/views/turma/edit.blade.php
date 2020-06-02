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
                        <div class="col-md-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <p><b>Professores</b></p>
                                    <select name="professores[]" id="professores" class="form-control show-tick"
                                            data-live-search="true" multiple>
                                        @foreach($profesores as $profesor)
                                            <option value="{{ $profesor->id }}" {{ in_array($profesor->id, $turma->professores->pluck('id')->all()) ? 'selected':'' }}>{{ $profesor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
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

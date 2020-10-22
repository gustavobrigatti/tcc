@extends('templates.master')

@push('head')
    <!-- JQuery DataTable Css -->
    <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- Jquery DataTable Plugin Js -->
    <script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/tables/jquery-datatable.js"></script>
@endpush

@section('content')
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ \Illuminate\Support\Facades\Auth::user()->role == 600 && !isset($_GET['al']) ? 'ALUNOS':'GRADE DE HORÁRIOS' }}
                    </h2>
                </div>
                <div class="body">
                    @if(\Illuminate\Support\Facades\Auth::user()->role == 600 && !isset($_GET['al']))
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable" style="white-space: nowrap;">
                                <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th class="col-xs-3">Ações</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Aula</th>
                                    <th>Ações</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @forelse(\Illuminate\Support\Facades\Auth::user()->alunos as $aluno)
                                    <tr>
                                        <td>{{ $aluno->name }}</td>
                                        <td><a href="/grade?al={{ $aluno->hash_id }}" class="btn btn-primary btn-block waves-effect" >VISUALIZAR</a></td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#segunda"
                                                                          aria-controls="settings"
                                                                          role="tab" data-toggle="tab">Segunda</a>
                                </li>
                                <li role="presentation"><a href="#terca" aria-controls="settings"
                                                           role="tab" data-toggle="tab">Terça</a></li>
                                <li role="presentation"><a href="#quarta" aria-controls="settings"
                                                           role="tab" data-toggle="tab">Quarta</a></li>
                                <li role="presentation"><a href="#quinta" aria-controls="settings"
                                                           role="tab" data-toggle="tab">Quinta</a></li>
                                <li role="presentation"><a href="#sexta" aria-controls="settings"
                                                           role="tab" data-toggle="tab">Sexta</a></li>
                                <li role="presentation"><a href="#sabado" aria-controls="settings"
                                                           role="tab" data-toggle="tab">Sábado</a></li>
                            </ul>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="segunda">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable" style="white-space: nowrap;">
                                            <thead>
                                            <tr>
                                                <th>Aula</th>
                                                <th>{{ \Illuminate\Support\Facades\Auth::user()->role == '400' ? 'Turma':'Professor' }}</th>
                                                <th>Horário</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>Aula</th>
                                                <th>{{ \Illuminate\Support\Facades\Auth::user()->role == '400' ? 'Turma':'Professor' }}</th>
                                                <th>Horário</th>
                                            </tr>
                                            </tfoot>
                                            <tbody>
                                            @if(\Illuminate\Support\Facades\Auth::user()->role == '400')
                                                @forelse(\Illuminate\Support\Facades\Auth::user()->aulaTurma->where('dia_semana', 'segunda')->sortBy('hora_inicio') as $aulaTurma)
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            {{ $aulaTurma->aula->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ $aulaTurma->turma->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ substr($aulaTurma->hora_inicio, 0, 5) }} às {{ substr($aulaTurma->hora_fim, 0, 5) }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                @endforelse
                                            @else
                                                @forelse(\Illuminate\Support\Facades\Auth::user()->role == 500 ? \Illuminate\Support\Facades\Auth::user()->aluno_turmas : \App\Models\User::where('id', \Vinkla\Hashids\Facades\Hashids::decode($_GET['al']))->first()->aluno_turmas as $turma)
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
                                                        </tr>
                                                    @empty
                                                    @endforelse
                                                @empty
                                                @endforelse
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade in" id="terca">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable" style="white-space: nowrap;">
                                            <thead>
                                            <tr>
                                                <th>Aula</th>
                                                <th>{{ \Illuminate\Support\Facades\Auth::user()->role == '400' ? 'Turma':'Professor' }}</th>
                                                <th>Horário</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>Aula</th>
                                                <th>{{ \Illuminate\Support\Facades\Auth::user()->role == '400' ? 'Turma':'Professor' }}</th>
                                                <th>Horário</th>
                                            </tr>
                                            </tfoot>
                                            <tbody>
                                            @if(\Illuminate\Support\Facades\Auth::user()->role == '400')
                                                @forelse(\Illuminate\Support\Facades\Auth::user()->aulaTurma->where('dia_semana', 'terca')->sortBy('hora_inicio') as $aulaTurma)
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            {{ $aulaTurma->aula->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ $aulaTurma->turma->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ substr($aulaTurma->hora_inicio, 0, 5) }} às {{ substr($aulaTurma->hora_fim, 0, 5) }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                @endforelse
                                            @else
                                                @forelse(\Illuminate\Support\Facades\Auth::user()->role == 500 ? \Illuminate\Support\Facades\Auth::user()->aluno_turmas : \App\Models\User::where('id', \Vinkla\Hashids\Facades\Hashids::decode($_GET['al']))->first()->aluno_turmas as $turma)
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
                                                        </tr>
                                                    @empty
                                                    @endforelse
                                                @empty
                                                @endforelse
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade in" id="quarta">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable" style="white-space: nowrap;">
                                            <thead>
                                            <tr>
                                                <th>Aula</th>
                                                <th>{{ \Illuminate\Support\Facades\Auth::user()->role == '400' ? 'Turma':'Professor' }}</th>
                                                <th>Horário</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>Aula</th>
                                                <th>{{ \Illuminate\Support\Facades\Auth::user()->role == '400' ? 'Turma':'Professor' }}</th>
                                                <th>Horário</th>
                                            </tr>
                                            </tfoot>
                                            <tbody>
                                            @if(\Illuminate\Support\Facades\Auth::user()->role == '400')
                                                @forelse(\Illuminate\Support\Facades\Auth::user()->aulaTurma->where('dia_semana', 'quarta')->sortBy('hora_inicio') as $aulaTurma)
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            {{ $aulaTurma->aula->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ $aulaTurma->turma->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ substr($aulaTurma->hora_inicio, 0, 5) }} às {{ substr($aulaTurma->hora_fim, 0, 5) }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                @endforelse
                                            @else
                                                @forelse(\Illuminate\Support\Facades\Auth::user()->role == 500 ? \Illuminate\Support\Facades\Auth::user()->aluno_turmas : \App\Models\User::where('id', \Vinkla\Hashids\Facades\Hashids::decode($_GET['al']))->first()->aluno_turmas as $turma)
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
                                                        </tr>
                                                    @empty
                                                    @endforelse
                                                @empty
                                                @endforelse
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade in" id="quinta">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable" style="white-space: nowrap;">
                                            <thead>
                                            <tr>
                                                <th>Aula</th>
                                                <th>{{ \Illuminate\Support\Facades\Auth::user()->role == '400' ? 'Turma':'Professor' }}</th>
                                                <th>Horário</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>Aula</th>
                                                <th>{{ \Illuminate\Support\Facades\Auth::user()->role == '400' ? 'Turma':'Professor' }}</th>
                                                <th>Horário</th>
                                            </tr>
                                            </tfoot>
                                            <tbody>
                                            @if(\Illuminate\Support\Facades\Auth::user()->role == '400')
                                                @forelse(\Illuminate\Support\Facades\Auth::user()->aulaTurma->where('dia_semana', 'quinta')->sortBy('hora_inicio') as $aulaTurma)
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            {{ $aulaTurma->aula->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ $aulaTurma->turma->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ substr($aulaTurma->hora_inicio, 0, 5) }} às {{ substr($aulaTurma->hora_fim, 0, 5) }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                @endforelse
                                            @else
                                                @forelse(\Illuminate\Support\Facades\Auth::user()->role == 500 ? \Illuminate\Support\Facades\Auth::user()->aluno_turmas : \App\Models\User::where('id', \Vinkla\Hashids\Facades\Hashids::decode($_GET['al']))->first()->aluno_turmas as $turma)
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
                                                        </tr>
                                                    @empty
                                                    @endforelse
                                                @empty
                                                @endforelse
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade in" id="sexta">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable" style="white-space: nowrap;">
                                            <thead>
                                            <tr>
                                                <th>Aula</th>
                                                <th>{{ \Illuminate\Support\Facades\Auth::user()->role == '400' ? 'Turma':'Professor' }}</th>
                                                <th>Horário</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>Aula</th>
                                                <th>{{ \Illuminate\Support\Facades\Auth::user()->role == '400' ? 'Turma':'Professor' }}</th>
                                                <th>Horário</th>
                                            </tr>
                                            </tfoot>
                                            <tbody>
                                            @if(\Illuminate\Support\Facades\Auth::user()->role == '400')
                                                @forelse(\Illuminate\Support\Facades\Auth::user()->aulaTurma->where('dia_semana', 'sexta')->sortBy('hora_inicio') as $aulaTurma)
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            {{ $aulaTurma->aula->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ $aulaTurma->turma->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ substr($aulaTurma->hora_inicio, 0, 5) }} às {{ substr($aulaTurma->hora_fim, 0, 5) }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                @endforelse
                                            @else
                                                @forelse(\Illuminate\Support\Facades\Auth::user()->role == 500 ? \Illuminate\Support\Facades\Auth::user()->aluno_turmas : \App\Models\User::where('id', \Vinkla\Hashids\Facades\Hashids::decode($_GET['al']))->first()->aluno_turmas as $turma)
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
                                                        </tr>
                                                    @empty
                                                    @endforelse
                                                @empty
                                                @endforelse
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade in" id="sabado">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable" style="white-space: nowrap;">
                                            <thead>
                                            <tr>
                                                <th>Aula</th>
                                                <th>{{ \Illuminate\Support\Facades\Auth::user()->role == '400' ? 'Turma':'Professor' }}</th>
                                                <th>Horário</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>Aula</th>
                                                <th>{{ \Illuminate\Support\Facades\Auth::user()->role == '400' ? 'Turma':'Professor' }}</th>
                                                <th>Horário</th>
                                            </tr>
                                            </tfoot>
                                            <tbody>
                                            @if(\Illuminate\Support\Facades\Auth::user()->role == '400')
                                                @forelse(\Illuminate\Support\Facades\Auth::user()->aulaTurma->where('dia_semana', 'sabado')->sortBy('hora_inicio') as $aulaTurma)
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            {{ $aulaTurma->aula->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ $aulaTurma->turma->nome }}
                                                        </td>
                                                        <td style="vertical-align: middle;text-align: center;">
                                                            {{ substr($aulaTurma->hora_inicio, 0, 5) }} às {{ substr($aulaTurma->hora_fim, 0, 5) }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                @endforelse
                                            @else
                                                @forelse(\Illuminate\Support\Facades\Auth::user()->role == 500 ? \Illuminate\Support\Facades\Auth::user()->aluno_turmas : \App\Models\User::where('id', \Vinkla\Hashids\Facades\Hashids::decode($_GET['al']))->first()->aluno_turmas as $turma)
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
                                                        </tr>
                                                    @empty
                                                    @endforelse
                                                @empty
                                                @endforelse
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
@endsection

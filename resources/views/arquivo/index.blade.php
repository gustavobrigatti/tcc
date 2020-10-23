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
                        {{ isset($_GET['t']) ? 'AULAS - '.$turma->nome:'TURMAS' }}
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
                                    <th>Aluno</th>
                                    <th>Ações</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @forelse(\Illuminate\Support\Facades\Auth::user()->alunos as $aluno)
                                    <tr>
                                        <td>{{ $aluno->name }}</td>
                                        <td><a href="/arquivo?al={{ $aluno->hash_id }}" class="btn btn-primary btn-block waves-effect" >VISUALIZAR</a></td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="white-space: nowrap;">
                                @if(isset($_GET['t']))
                                    <thead>
                                    <tr>
                                        <th>Aula</th>
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
                                    @forelse($aulas as $aula)
                                        <tr>
                                            <td>{{ $aula->nome }}</td>
                                            <td><a href="/arquivo/{{ $turma->hash_id }}?a={{ $aula->hash_id }}" class="btn btn-primary btn-block waves-effect" >VISUALIZAR</a></td>
                                        </tr>
                                    @empty
                                    @endforelse
                                    </tbody>
                                @else
                                    <thead>
                                    <tr>
                                        <th>Turma</th>
                                        <th class="col-xs-3">Ações</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Turma</th>
                                        <th>Ações</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    @forelse($turmas as $turma)
                                        <tr>
                                            <td>{{ $turma->nome }}</td>
                                            @if(\Illuminate\Support\Facades\Auth::user()->role == 600)
                                                <td><a href="/arquivo?t={{ $turma->hash_id }}&al={{ $_GET['al'] }}" class="btn btn-primary btn-block waves-effect" >VISUALIZAR</a></td>
                                            @else
                                                <td><a href="/arquivo?t={{ $turma->hash_id }}" class="btn btn-primary btn-block waves-effect" >VISUALIZAR</a></td>
                                            @endif
                                        </tr>
                                    @empty
                                    @endforelse
                                    </tbody>
                                @endif
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
@endsection

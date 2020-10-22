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
                        TURMAS
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="white-space: nowrap;">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Período</th>
                                <th>Escolaridade</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Nome</th>
                                <th>Período</th>
                                <th>Escolaridade</th>
                                <th>Ações</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @forelse($turmas as $turma)
                                <tr>
                                    <td style="vertical-align: middle;">
                                        {{ $turma->nome }}
                                    </td>
                                    <td style="vertical-align: middle;text-align: center;">
                                        {{ $turma->periodo }}
                                    </td>
                                    <td style="vertical-align: middle;text-align: center;">
                                        {{ $turma->escolaridade }}
                                    </td>
                                    <td style="width: 33%; vertical-align: middle;text-align: center;">
                                        @if(\Illuminate\Support\Facades\Auth::user()->role == 200)
                                            <a href="/nota?t={{ $turma->hash_id }}" class="btn btn-primary waves-effect">NOTAS</a>
                                            <a href="/tarefa?t={{ $turma->hash_id }}" class="btn btn-primary waves-effect">TAREFAS</a>
                                            <a href="/arquivo?t={{ $turma->hash_id }}" class="btn btn-primary waves-effect">ARQUIVOS</a>
                                        @endif
                                        <a href="{{ route('turma.edit', $turma->hash_id) }}" class="btn btn-primary waves-effect {{ \Illuminate\Support\Facades\Auth::user()->role == 300 ? 'btn-block':'' }}">EDITAR</a>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
@endsection

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
                        AULAS
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="white-space: nowrap;">
                            <thead>
                            <tr>
                                <th>Aula</th>
                                <th>Turma</th>
                                <th class="col-xs-3">Ações</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Aula</th>
                                <th>Turma</th>
                                <th>Ações</th>
                            </tr>
                            </tfoot>
                            <tbody>
                                @forelse($arquivos as $arquivo)
                                    <tr>
                                        <td>{{ $arquivo->nome }}</td>
                                        <td>{{ $arquivo->turma->nome }}</td>
                                        <td><a href="{{ route('arquivo.show', $arquivo->hash_id) }}" class="btn btn-primary waves-effect" style="width: 100%" >VISUALIZAR</a></td>
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

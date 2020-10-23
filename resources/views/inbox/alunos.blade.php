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
                        ALUNOS
                    </h2>
                </div>
                <div class="body">
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
                            @forelse($alunos as $aluno)
                                <tr>
                                    <td>{{ $aluno->name }}</td>
                                    <td><a href="/inbox/alunos?al={{ $aluno->hash_id }}" class="btn btn-primary btn-block waves-effect" >VISUALIZAR</a></td>
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

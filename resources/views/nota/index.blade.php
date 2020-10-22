@extends('templates.master')

@push('head')
    <!-- JQuery DataTable Css -->
    <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="plugins/fab/fab.css" rel="stylesheet">
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
    @if(\Illuminate\Support\Facades\Auth::user()->role == 400)
        <div class="fab">
            <button class="main" data-toggle="modal" data-target="#defaultModal">
                <a href="{{ (route('nota.edit', 0)) }}" style="width: 100%; height: 100%">
                    <i class="material-icons" style="padding-top: 8px; color: #fff">add</i>
                </a>
            </button>
        </div>
    @endif
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
                                        <td><a href="/nota/{{ $turma->hash_id }}?a={{ $aula->hash_id }}" class="btn btn-primary btn-block waves-effect" >VISUALIZAR</a></td>
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
                                        <td><a href="/nota?t={{ $turma->hash_id }}" class="btn btn-primary btn-block waves-effect" >VISUALIZAR</a></td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
@endsection

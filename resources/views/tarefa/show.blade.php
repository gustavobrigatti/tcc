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
                <a href="{{ (route('tarefa.edit', isset($_GET['a']) && isset($_GET['t']) ? $tarefa->hash_id:0)) }}" style="width: 100%; height: 100%">
                    <i class="material-icons" style="padding-top: 8px; color: #fff">{{ (isset($_GET['a']) && isset($_GET['t']) ? 'edit':'add') }}</i>
                </a>
            </button>
        </div>
    @endif
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    @if(isset($_GET['a']) && isset($_GET['t']))
                        <h2>
                            <div class="col-xs-6">{{ $tarefa->nome }}</div>
                            <div class="col-xs-6" style="text-align: right">Data de Entrega: {{ $tarefa->data_entrega->format('d/m/Y') }}</div>
                            <br>
                        </h2>
                    @else
                        <h2>
                            TAREFAS - {{$aula->nome}}({{ $turma->nome }})
                        </h2>
                    @endif
                </div>
                <div class="body">
                    <div class="table-responsive">
                        @if(isset($_GET['a']) && isset($_GET['t']))
                            <p><b style="font-size: large">DESCRIÇÃO: </b></p>
                            <p style="text-align: justify">{!! nl2br($tarefa->descricao) !!}</p>
                        @endif
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="white-space: nowrap;">
                            @if(isset($_GET['a']) && isset($_GET['t']))

                            @else
                                <thead>
                                <tr>
                                    <th>Tarefa</th>
                                    <th>Criada em</th>
                                    <th>Data de entrega</th>
                                    <th class="col-xs-3">Ações</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Tarefa</th>
                                    <th>Criada em</th>
                                    <th>Data de entrega</th>
                                    <th>Ações</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @forelse($tarefas as $tarefa)
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $tarefa->nome }}</td>
                                        <td style="vertical-align: middle;text-align: center">{{ $tarefa->created_at->format('d/m/Y') }}</td>
                                        <td style="vertical-align: middle;text-align: center">{{ $tarefa->data_entrega->format('d/m/Y') }}</td>
                                        <td style="vertical-align: middle;text-align: center"><a href="/tarefa/{{ $tarefa->hash_id }}?t={{ $turma->hash_id }}&a={{ $aula->hash_id }}" class="btn btn-primary btn-block waves-effect" >VISUALIZAR</a></td>
                                    </tr>
                                @empty
                                @endforelse
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
@endsection

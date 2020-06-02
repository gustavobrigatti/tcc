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
                        INSTITUIÇÕES
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="white-space: nowrap;">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Contato</th>
                                <th>Localização</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Nome</th>
                                <th>Contato</th>
                                <th>Localização</th>
                                <th>Ações</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @forelse($instituicoes as $instituicao)
                            <tr>
                                <td style="vertical-align: middle;">
                                    {{ $instituicao->nome }}<br>
                                    {{ $instituicao->cnpj }}
                                </td>
                                <td style="vertical-align: middle;text-align: center;">
                                    {{ $instituicao->telefone }}<br>
                                    {{ $instituicao->email }}
                                </td>
                                <td style="vertical-align: middle;text-align: center;">
                                    {{ $instituicao->cidade }}/{{ $instituicao->estado }}
                                </td>
                                <td style="vertical-align: middle;text-align: center;"><a href="{{ route('instituicao.edit', $instituicao->hash_id) }}" class="btn btn-primary waves-effect" style="width: 100%" >EDITAR</a></td>
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

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
                        USUÁRIOS
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="white-space: nowrap;">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Contato</th>
                                <th>{{ \Illuminate\Support\Facades\Auth::user()->role == 100 ? 'Escola':'Tipo' }}</th>
                                <th>Localização</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Nome</th>
                                <th>Contato</th>
                                <th>Escola</th>
                                <th>Localização</th>
                                <th>Ações</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td style="vertical-align: middle;">
                                        {{ $user->name }}<br>
                                        {{ $user->cpf }}
                                    </td>
                                    <td style="vertical-align: middle;text-align: center;">
                                        {{ $user->telefone }}<br>
                                        {{ $user->email }}
                                    </td>
                                    <td style="vertical-align: middle;text-align: center;">{{ \Illuminate\Support\Facades\Auth::user()->role == 100 ? ($user->escola_id > 0 ? $user->instituicao->nome:'Não pertence a nenhuma instituição') : ($user->role == 100 ? 'Administrador' : ($user->role == 200 ? 'Direção/Coordenação': ($user->role == 300 ? ($user->genero == 'M' ? 'Blibliotecário':'Blibliotecária') : ($user->role == 400 ? ($user->genero == 'M' ? 'Professor':'Professora') : ($user->role == 500 ? ($user->genero == 'M' ? 'Aluno':'Aluna'):'Responsável'))))) }}</td>
                                    <td style="vertical-align: middle;text-align: center;">{{ $user->cidade }}/{{ $user->estado }}</td>
                                    <td style="vertical-align: middle;text-align: center;"><a href="{{ route('user.edit', $user->hash_id) }}" class="btn btn-primary waves-effect" style="width: 100%" >EDITAR</a></td>
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

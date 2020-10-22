@extends('templates.master')

@push('head')
    <!-- Sweet Alert Css -->
    <link href="plugins/sweetalert/sweetalert.css" rel="stylesheet"/>

    <!-- Bootstrap Select Css -->
    <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet"/>

    <!-- JQuery DataTable Css -->
    <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="plugins/fab/fab.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- Jquery DataTable Plugin Js -->
    <script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <!-- Jquery Validation Plugin Css -->
    <script src="plugins/jquery-validation/jquery.validate.js"></script>
    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/tables/jquery-datatable.js"></script>
    <script src="js/pages/forms/form-validation.js"></script>
    <script src="js/pages/forms/advanced-form-elements.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            $('.nota').mask("00,0", {reverse: true});
        });
    </script>
@endpush

@section('content')
    @if(\Illuminate\Support\Facades\Auth::user()->role == 400)
        <div class="fab">
            <button class="main">
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
                    @if(\Illuminate\Support\Facades\Auth::user()->role == 400 && (isset($_GET['a']) && isset($_GET['al'])))
                        <h2>
                            {{ $aluno->name }}
                        </h2>
                    @elseif(\Illuminate\Support\Facades\Auth::user()->role == 400)
                        <h2>
                            ALUNOS
                        </h2>
                    @elseif(\Illuminate\Support\Facades\Auth::user()->role == 500)
                        <h2>
                            NOTAS
                        </h2>
                    @endif
                </div>
                <div class="body">
                    @if(\Illuminate\Support\Facades\Auth::user()->role == 400 && (isset($_GET['a']) && isset($_GET['al'])))
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable" style="white-space: nowrap;">
                                <thead>
                                <tr>
                                    <th class="col-xs-6">Nome</th>
                                    <th class="col-xs-4">Nota</th>
                                    <th class="col-xs-2">Ações</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Nome</th>
                                    <th>Nota</th>
                                    <th>Ações</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @forelse($aluno->notas->where('aula_id', \Vinkla\Hashids\Facades\Hashids::decode($_GET['a'])[0])->where('turma_id', $id) as $nota)
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $nota->nome }}</td>
                                        <td style="vertical-align: middle;text-align: center">{{ number_format($nota->nota, 1, ',', '.') }}</td>
                                        <td style="vertical-align: middle;text-align: center">
                                            <a href="{{ route('nota.edit', $nota->hash_id) }}" class="btn btn-primary waves-effect"><i class="material-icons">edit</i></a>
                                            <a href="javascript:document.getElementById('{{$nota->hash_id}}').submit();" class="btn btn-primary waves-effect"><i class="material-icons">delete</i></a>
                                            <form id="{{$nota->hash_id}}" action="{{ route('nota.destroy', $nota->hash_id) }}" method="POST">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="_method" value="DELETE"/>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">Sem dados disponíveis na tabela</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <br><br>
                        <form id="form_advanced_validation" action="{{ route('nota.media') }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ $id }}" name="turma_id">
                            <input type="hidden" value="{{ \Vinkla\Hashids\Facades\Hashids::decode($_GET['a'])[0] }}" name="aula_id">
                            <input type="hidden" value="{{ \Vinkla\Hashids\Facades\Hashids::decode($_GET['al'])[0] }}" name="user_id">
                            <div class="col-xs-12">
                                <div class="col-xs-12">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control nota" name="media"
                                                   value="{{number_format(old('media', $media), 1, ',', '.')}}"
                                                   required>
                                            <label class="form-label">Media</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-primary waves-effect btn-block" type="submit" style="width: 100%">CADASTRAR
                                </button>
                            </div>
                        </form>
                    @elseif(\Illuminate\Support\Facades\Auth::user()->role == 400)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable" style="white-space: nowrap;">
                                <thead>
                                <tr>
                                    <th class="col-xs-9">Aluno</th>
                                    <th class="col-xs-3">Ação</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Ação</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @forelse($turma->alunos as $aluno)
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $aluno->name }}</td>
                                        <td style="vertical-align: middle;text-align: center"><a href="/nota/{{ $turma->hash_id }}?a={{ $_GET['a'] }}&al={{ $aluno->hash_id }}" class="btn btn-primary btn-block waves-effect" >VISUALIZAR</a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">Sem dados disponíveis na tabela</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    @elseif(\Illuminate\Support\Facades\Auth::user()->role == 500)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable" style="white-space: nowrap;">
                                <thead>
                                <tr>
                                    <th class="col-xs-8">Nome</th>
                                    <th class="col-xs-4">Nota</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Nome</th>
                                    <th>Nota</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @forelse(\Illuminate\Support\Facades\Auth::user()->notas->where('aula_id', \Vinkla\Hashids\Facades\Hashids::decode($_GET['a'])[0])->where('turma_id', $id) as $nota)
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $nota->nome }}</td>
                                        <td style="vertical-align: middle;text-align: center">{{ number_format($nota->nota, 1, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">Sem dados disponíveis na tabela</td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <td style="vertical-align: middle;"><b>MÉDIA</b></td>
                                    <td style="vertical-align: middle;text-align: center"><b>{{ count(\Illuminate\Support\Facades\Auth::user()->media->where('aula_id', \Vinkla\Hashids\Facades\Hashids::decode($_GET['a'])[0])->where('turma_id', $id)) == 0 ? 'INDISPONÍVEL':number_format(\Illuminate\Support\Facades\Auth::user()->media->where('aula_id', \Vinkla\Hashids\Facades\Hashids::decode($_GET['a'])[0])->where('turma_id', $id)->first()->media, 1, ',', '.') }}</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
@endsection

@extends('templates.master')

@push('head')
    <!-- JQuery DataTable Css -->
    <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    @if(isset($_GET['a']) && isset($_GET['t']))
        <!-- Dropzone Css -->
        <link href="plugins/dropzone/dropzone.css" rel="stylesheet">
    @endif
    <link href="plugins/fab/fab.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- Jquery DataTable Plugin Js -->
    <script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>

    @if(isset($_GET['a']) && isset($_GET['t']))
        <!-- Dropzone Plugin Js -->
        <script src="plugins/dropzone/dropzone.js"></script>
    @endif

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/tables/jquery-datatable.js"></script>

    @if(isset($_GET['a']) && isset($_GET['t']))
        <script type="text/javascript">
            Dropzone.options.myAwesomeDropzone = {
                renameFile: function (file) {
                    var dt = new Date();
                    var time = dt.getTime();
                    return time + file.name;
                },
                maxFiles: 10,
                addRemoveLinks: true,
                timeout: 5000,
                dictRemoveFile: 'Remover arquivo',
                dictFallbackMessage: "Seu navegador não suporta arrastar e soltar arquivos",
                dictFallbackText: "Por favor, use o botão para selecionar arquivos",
                dictInvalidFileType: "Você não pode carregar arquivos deste tipo.",
                dictCancelUpload: "Cancelar upload",
                dictCancelUploadConfirmation: "Tem certeza de que deseja cancelar este upload?",
                dictMaxFilesExceeded: "Você não pode carregar mais arquivos.",
                autoProcessQueue: false,
                accept: function (file, done) {
                    console.log("uploaded");
                    console.log(file);
                    done();
                },
                init: function () {
                    var myDropzone = this;

                    // Update selector to match your button
                    $("#button").click(function (e) {
                        e.preventDefault();
                        myDropzone.processQueue();
                        location.reload()
                    });

                    this.on('sending', function (file, xhr, formData) {
                        // Append all form inputs to the formData Dropzone will POST
                        var data = $('#myAwesomeDropzone').serializeArray();
                        $.each(data, function (key, el) {
                            formData.append(el.name, el.value);
                        });
                    });

                    this.on("maxfilesexceeded", function (file) {
                        this.removeFile(file);
                        alert("Você não pode carregar mais arquivos!");
                    });
                },
            };
        </script>
    @endif
@endpush

@section('content')
    @if(\Illuminate\Support\Facades\Auth::user()->role == 400 && !(isset($_GET['a']) && isset($_GET['t'])))
        <div class="fab">
            <button class="main">
                <a href="{{ (route('tarefa.edit', 0)) }}" style="width: 100%; height: 100%">
                    <i class="material-icons" style="padding-top: 8px; color: #fff">add</i>
                </a>
            </button>
        </div>
    @endif
    @if(isset($_GET['a']) && isset($_GET['t']) && (\Illuminate\Support\Facades\Auth::user()->role == 400 || \Illuminate\Support\Facades\Auth::user()->role == 500))
        <div class="fab">
            <button class="main" data-toggle="modal" data-target="#defaultModal">
                @if(isset($_GET['a']) && isset($_GET['t']))
                    <a style="width: 100%; height: 100%">
                        <i class="material-icons" style="padding-top: 8px; color: #fff">file_upload</i>
                    </a>
                @else
                    <a href="{{ (route('tarefa.edit', 0)) }}" style="width: 100%; height: 100%">
                        <i class="material-icons" style="padding-top: 8px; color: #fff">add</i>
                    </a>
                @endif
            </button>
        </div>
        <!-- Default Size -->
        <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="defaultModalLabel">Enviar arquivos</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('tarefa.upload', $tarefa->hash_id) }}" id="my-awesome-dropzone" class="dropzone" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="dz-message">
                                <div class="drag-icon-cph">
                                    <i class="material-icons">touch_app</i>
                                </div>
                                <h3>Solte seus arquivos aqui ou clique para fazer o upload.</h3>
                                <em>(O upload é limitado em <strong>10 arquivos</strong>).</em>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="button" type="button" class="btn btn-link waves-effect">ENVIAR</button>
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">FECHAR</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    @if(isset($_GET['a']) && isset($_GET['t']))
                        <h2>
                            {{ $tarefa->nome }}
                        </h2>
                        @if(\Illuminate\Support\Facades\Auth::user()->role == 400)
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
                                       role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="{{ route('tarefa.edit', $tarefa->hash_id) }}">Editar</a></li>
                                    </ul>
                                </li>
                            </ul>
                        @endif
                    @else
                        <h2>
                            TAREFAS - {{$aula->nome}}({{ $turma->nome }})
                        </h2>
                    @endif
                </div>
                <div class="body">
                    @if(isset($_GET['a']) && isset($_GET['t']))
                        <div class="col-xs-6"><p><b style="font-size: large">DESCRIÇÃO: </b></p></div>
                        <div class="col-xs-6" style="text-align: right"><p><b style="font-size: large">Data de Entrega: {{ $tarefa->data_entrega->format('d/m/Y') }}</b></p></div>
                        <div class="col-xs-12"><p style="text-align: justify">{!! nl2br($tarefa->descricao) !!}</p></div>
                        <br>
                        <p><b style="font-size: large">MATERIAIS DE APOIO: </b></p>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover {{ (isset($_GET['a']) && isset($_GET['t']) ? '':'js-basic-example') }} dataTable" style="white-space: nowrap;">
                            @if(isset($_GET['a']) && isset($_GET['t']))
                                <thead>
                                <tr>
                                    <th class="col-xs-8">Arquivo</th>
                                    <th class="col-xs-2">Data de envio</th>
                                    <th class="col-xs-2">Ações</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Arquivo</th>
                                    <th>Data de envio</th>
                                    <th>Ações</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @forelse($tarefa->itens->where('tipo', 'professor') as $item)
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $item->nome }}</td>
                                        <td style="vertical-align: middle;text-align: center">{{ $item->created_at->format('d/m/Y') }}</td>
                                        <td style="vertical-align: middle;text-align: center">
                                            <a href="{{ route('tarefa.download', $item->hash_id) }}" class="btn btn-primary waves-effect"><i class="material-icons">file_download</i></a>
                                            @if($item->user_id == \Illuminate\Support\Facades\Auth::user()->id)
                                                <a href="{{ route('tarefa.delete', $item->hash_id) }}" class="btn btn-primary waves-effect"><i class="material-icons">delete</i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">Sem dados disponíveis na tabela</td>
                                    </tr>
                                @endforelse
                                </tbody>
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
                                        @if(\Illuminate\Support\Facades\Auth::user()->role == 600)
                                            <td style="vertical-align: middle;text-align: center"><a href="/tarefa/{{ $tarefa->hash_id }}?t={{ $turma->hash_id }}&a={{ $aula->hash_id }}&alR={{ $_GET['alR'] }}" class="btn btn-primary btn-block waves-effect" >VISUALIZAR</a></td>
                                        @else
                                            <td style="vertical-align: middle;text-align: center"><a href="/tarefa/{{ $tarefa->hash_id }}?t={{ $turma->hash_id }}&a={{ $aula->hash_id }}" class="btn btn-primary btn-block waves-effect" >VISUALIZAR</a></td>
                                        @endif
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            @endif

                        </table>
                    </div>

                    @if(isset($_GET['a']) && isset($_GET['t']) && (\Illuminate\Support\Facades\Auth::user()->role == 200 || \Illuminate\Support\Facades\Auth::user()->role == 400))
                        <br>
                        <p><b style="font-size: large">{{ \Illuminate\Support\Facades\Auth::user()->role == 400 ? 'MEUS ':'' }}ALUNOS: </b></p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable" style="white-space: nowrap;">
                                <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th class="col-xs-3">Ações</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Nome</th>
                                    <th>Ações</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @forelse($tarefa->turma->alunos as $aluno)
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $aluno->name }}</td>
                                        <td><a href="/tarefa/{{ $tarefa->hash_id }}?t={{ $tarefa->turma->hash_id }}&a={{ $tarefa->aula->hash_id }}&al={{ $aluno->hash_id }}" class="btn btn-primary btn-block waves-effect" >VISUALIZAR</a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">Sem dados disponíveis na tabela</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if((isset($_GET['a']) && isset($_GET['t']) && \Illuminate\Support\Facades\Auth::user()->role == 500) || (isset($_GET['a']) && isset($_GET['t']) && isset($_GET['al']) && (\Illuminate\Support\Facades\Auth::user()->role == 200 || \Illuminate\Support\Facades\Auth::user()->role == 400)) || (isset($_GET['a']) && isset($_GET['t']) && isset($_GET['alR']) && \Illuminate\Support\Facades\Auth::user()->role == 600))
                        <br>
                        <p><b style="font-size: large">{{ isset($_GET['al']) ? ($user->genero == 'M' ? 'ENVIOS DO ALUNO ':'ENVIOS DA ALUNA ').strtoupper(strstr($user->name, ' ', true)):(\Illuminate\Support\Facades\Auth::user()->role == 500 ? 'MEUS ENVIOS:':(\App\Models\User::where('id', \Vinkla\Hashids\Facades\Hashids::decode($_GET['alR']))->first()->genero == 'M' ? 'ENVIOS DO ALUNO ':'ENVIOS DA ALUNA ').strtoupper(strstr(\App\Models\User::where('id', \Vinkla\Hashids\Facades\Hashids::decode($_GET['alR']))->first()->name, ' ', true))) }} </b></p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable" style="white-space: nowrap;">
                                <thead>
                                <tr>
                                    <th class="col-xs-8">Arquivo</th>
                                    <th class="col-xs-2">Data de envio</th>
                                    <th class="col-xs-2">Ações</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Arquivo</th>
                                    <th>Data de envio</th>
                                    <th>Ações</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                    @forelse(isset($_GET['al']) ? $tarefa->itens->where('tipo', 'aluno')->where('user_id', $user->id) : $tarefa->itens->where('tipo', 'aluno')->where('user_id', \Illuminate\Support\Facades\Auth::user()->role == 500 ? \Illuminate\Support\Facades\Auth::user()->id:\App\Models\User::where('id', \Vinkla\Hashids\Facades\Hashids::decode($_GET['alR']))->first()->id) as $item)
                                        <tr>
                                            <td style="vertical-align: middle;">{{ $item->nome }}</td>
                                            <td style="vertical-align: middle;text-align: center">{{ $item->created_at->format('d/m/Y') }}</td>
                                            <td style="vertical-align: middle;text-align: center">
                                                <a href="{{ route('tarefa.download', $item->hash_id) }}" class="btn btn-primary waves-effect"><i class="material-icons">file_download</i></a>
                                                @if($item->user_id == \Illuminate\Support\Facades\Auth::user()->id)
                                                    <a href="{{ route('tarefa.delete', $item->hash_id) }}" class="btn btn-primary waves-effect"><i class="material-icons">delete</i></a>
                                                @endif
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
                        @if(count(\Illuminate\Support\Facades\Auth::user()->role == 200 || \Illuminate\Support\Facades\Auth::user()->role == 400 ? $tarefa->comentarios->where('user_id', $user->id) : $tarefa->comentarios->where('user_id', \Illuminate\Support\Facades\Auth::user()->role == 500 ? \Illuminate\Support\Facades\Auth::user()->id:\App\Models\User::where('id', \Vinkla\Hashids\Facades\Hashids::decode($_GET['alR']))->first()->id)) > 0)
                            <br>
                            <div class="col-xs-12"><p><b style="font-size: large">COMENTÁRIOS: </b></p></div>
                        @endif
                        @forelse(\Illuminate\Support\Facades\Auth::user()->role == 200 || \Illuminate\Support\Facades\Auth::user()->role == 400 ? $tarefa->comentarios->where('user_id', $user->id) : $tarefa->comentarios->where('user_id', \Illuminate\Support\Facades\Auth::user()->role == 500 ? \Illuminate\Support\Facades\Auth::user()->id:\App\Models\User::where('id', \Vinkla\Hashids\Facades\Hashids::decode($_GET['alR']))->first()->id) as $comentario)
                            @if(!$loop->first)
                            <div class="col-xs-12" style="border-top: 1px solid rgba(204, 204, 204, 0.35);"></div>
                            @endif
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <a href="{{ route('user.perfil.show', $tarefa->user->hash_id) }}" style=" width: 100%; border: 0px solid #fff; border-radius: 8px; background-color: #fff !important;">
                                        <div style="position: absolute; border-radius: 50%; overflow: hidden; box-shadow: 3px 3px 5px #555;">
                                            <img src="/storage/users/{{ $tarefa->user->id }}.png" width="50" height="50"/>
                                        </div>
                                    </a>
                                    <h2 style="margin-left: 70px; margin-top: 5px;" class="card-inside-title">
                                        <b>{{ $tarefa->user->name }}</b>
                                    </h2>
                                    <div style="margin-left: 70px; margin-top: 5px;" >
                                        {{ $comentario->created_at->format('d/m/Y \á\s H:i') }}
                                    </div>
                                </div>
                                <div class="col-xs-6" style="text-align: right">
                                    @if(\Illuminate\Support\Facades\Auth::user()->role == 400)
                                        <a href="{{ route('tarefa.deleteComentario', $comentario->hash_id) }}" style="color: #2196F3"><i class="material-icons">delete</i></a>
                                    @endif
                                </div>
                            </div>
                            <p style="text-align: justify; padding-left: 100px">
                                @php
                                    /** @var TYPE_NAME $item */
                                    /** @var TYPE_NAME $comentario */
                                    $texto = nl2br($comentario->comentario);
                                    $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
                                    if(preg_match($reg_exUrl, $texto, $url)) {
                                       // make the urls hyper links
                                       echo preg_replace($reg_exUrl, '<a style="color:#2196F3;" target="_blank" href="'.$url[0].'" rel="nofollow">'.$url[0].'</a>', $texto);
                                    } else {
                                       // if no urls in the text just return the text
                                        echo $texto;
                                    }
                                @endphp
                            </p>
                        @empty
                        @endforelse
                        @if(\Illuminate\Support\Facades\Auth::user()->role == 400)
                            <br><br>
                            <form id="form_advanced_validation" action="{{ route('tarefa.storeComentario', $tarefa->hash_id) }}" method="post">
                                {{ csrf_field() }}
                                <div class="col-md-12">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <textarea rows="4" name="comentario" class="form-control no-resize" placeholder="Comentário sobre a tarefa do aluno..." required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                </div>
                                <div style="width: 100%">
                                    <button class="btn btn-primary waves-effect" type="submit" style="width: 100%">CADASTRAR
                                    </button>
                                </div>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
@endsection

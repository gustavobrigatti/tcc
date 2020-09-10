@extends('templates.master')

@push('head')
    <!-- JQuery DataTable Css -->
    <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <!-- Dropzone Css -->
    <link href="plugins/dropzone/dropzone.css" rel="stylesheet">
    <link href="plugins/fab/fab.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- Jquery DataTable Plugin Js -->
    <script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>

    <!-- Dropzone Plugin Js -->
    <script src="plugins/dropzone/dropzone.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/tables/jquery-datatable.js"></script>
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
@endpush

@section('content')
    <div class="fab">
        <button class="main" data-toggle="modal" data-target="#defaultModal">
            <a style="width: 100%; height: 100%">
                <i class="material-icons" style="padding-top: 8px; color: #fff">file_upload</i>
            </a>
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
                    <form action="{{ route('arquivo.upload', $arquivo->hash_id) }}" id="my-awesome-dropzone" class="dropzone" method="post" enctype="multipart/form-data">
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
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        ARQUIVOS
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="white-space: nowrap;">
                            <thead>
                            <tr>
                                <th class="col-xs-6">Arquivo</th>
                                <th class="col-xs-2">Data de envio</th>
                                <th class="col-xs-2">Enviado por</th>
                                <th class="col-xs-2">Ações</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Arquivo</th>
                                <th>Data de envio</th>
                                <th>Enviado por</th>
                                <th>Ações</th>
                            </tr>
                            </tfoot>
                            <tbody>
                                @forelse($arquivo->itens->sortBy('user_id') as $item)
                                    @if(\Illuminate\Support\Facades\Auth::user()->role == 500)
                                        @if($item->user_id != \Illuminate\Support\Facades\Auth::user()->id && $item->user_id != $arquivo->user_id)
                                            @continue
                                        @endif
                                    @endif
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $item->nome }}</td>
                                        <td style="vertical-align: middle;text-align: center">{{ $item->created_at->format('d/m/Y') }}</td>
                                        <td style="vertical-align: middle;text-align: center">{{ $item->user->name }}</td>
                                        <td style="vertical-align: middle;text-align: center">
                                            <a href="{{ route('arquivo.download', $item->hash_id) }}" class="btn btn-primary waves-effect"><i class="material-icons">file_download</i></a>
                                            @if($item->user_id == \Illuminate\Support\Facades\Auth::user()->id)
                                                <a href="{{ route('arquivo.delete', $item->hash_id) }}" class="btn btn-primary waves-effect"><i class="material-icons">delete</i></a>
                                            @endif
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

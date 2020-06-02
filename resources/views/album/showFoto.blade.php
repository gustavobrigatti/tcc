@extends('templates.master')

@push('head')
    <!-- Dropzone Css -->
    <link href="plugins/dropzone/dropzone.css" rel="stylesheet">
    <link href="plugins/fab/fab.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- Dropzone Plugin Js -->
    <script src="plugins/dropzone/dropzone.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>

    <script type="text/javascript">
        Dropzone.options.myAwesomeDropzone = {
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            maxFiles: 10,
            addRemoveLinks: true,
            timeout: 5000,
            parallelUploads: 10,
            uploadMultiple: true,
            dictRemoveFile: 'Remover arquivo',
            dictFallbackMessage: "Seu navegador não suporta arrastar e soltar fotos",
            dictFallbackText: "Por favor, use o botão para selecionar fotos",
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
                    window.location.href = "{{ route('album.show', $album->hash_id)}}";
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            {{ $album->nome }}
                            <small>{{ $album->descricao }}</small>
                        </h2>
                    </div>
                    <div class="body">
                        <center>
                            <form action="{{ route('album.foto', $album->hash_id) }}"
                                  id="my-awesome-dropzone"
                                  class="dropzone" method="post" enctype="multipart/form-data"
                                  style="width: 95%">
                                {{ csrf_field() }}
                                <div class="dz-message">
                                    <div class="drag-icon-cph">
                                        <i class="material-icons">touch_app</i>
                                    </div>
                                    <h3>Solte suas foto aqui ou clique para fazer o upload.</h3>
                                    <em>(Deve ser enviado no máximo <strong>10</strong>
                                        fotos a cada upload.)</em>
                                </div>
                            </form>
                        </center>
                        &nbsp;
                        <div class="col-sm-12">
                            <button type="button" id="button" class="btn btn-block btn-danger">ADICIONAR FOTOS</button>
                        </div>
                        <br><br><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

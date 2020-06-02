@extends('templates.master')

@push('head')
    <!-- Sweet Alert Css -->
    <link href="plugins/sweetalert/sweetalert.css" rel="stylesheet"/>

    <!-- Bootstrap Select Css -->
    <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet"/>
@endpush

@push('scripts')
    <!-- Select Plugin Js -->
    <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Jquery Validation Plugin Css -->
    <script src="plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/forms/form-validation.js"></script>
    <script src="js/pages/forms/advanced-form-elements.js"></script>
@endpush

@section('content')
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        MENSAGEM
                    </h2>
                </div>
                <div class="body">
                    <form method="post" id="form_advanced_validation" action="{{ route('inbox.store') }}">
                        {{ csrf_field() }}
                        <div class="form-group form-float">
                            <div class="form-line">
                                <p><b>Destinatário</b></p>
                                <select name="users[]" class="form-control show-tick"
                                        data-live-search="true" data-actions-box="true" multiple required>
                                    @foreach($users as $grupoKey => $grupo)
                                        @foreach($grupo as $user)
                                            @if($loop->first)
                                                @if($user->role == 100)
                                                    <optgroup label="Administradores">
                                                @elseif($user->role == 200)
                                                    <optgroup label="Direção/Coordenação">
                                                @elseif($user->role == 300)
                                                    <optgroup label="Bibliotecários(as)">
                                                @elseif($user->role == 400)
                                                    <optgroup label="Professores(as)">
                                                @elseif($user->role == 500)
                                                    <optgroup label="Alunos(as)">
                                                @elseif($user->role == 600)
                                                    <optgroup label="Responsáveis">
                                                @endif
                                            @endif
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @if($loop->last)
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="help-info">Selecione para quem a mensagem será enviada</div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input class="form-control" name="assunto" required>
                                <label class="form-label">Assunto</label>
                            </div>
                            <div class="help-info">Escreva o assunto da mensagem</div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <textarea name="mensagem" cols="30" rows="7"  class="form-control no-resize" required></textarea>
                                <label class="form-label">Mensagem</label>
                            </div>
                            <div class="help-info">Escreva a mensagem</div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-block bg-blue waves-effect">
                            ENVIAR MENSAGEM
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
@endsection

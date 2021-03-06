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
    <!-- Advanced Select -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        ÁLBUM
                    </h2>
                    @if($id > 0)
                        <form id="excluir"
                              action="{{ route('album.destroy', $album->hash_id)  }}"
                              method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE"/>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
                                       role="button"
                                       aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="javascript:document.getElementById('excluir').submit();">Excluir</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </form>
                    @endif
                </div>
                <div class="body">
                    <form id="form_advanced_validation"
                          action="{{ route($id > 0 ? 'album.update' : 'album.store', \Vinkla\Hashids\Facades\Hashids::encode($id)) }}"
                          method="post">
                        {{ $id > 0 ? method_field('PUT') : '' }}
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="nome"
                                           value="{{ old('nome', $album->nome) }}" required>
                                    <label class="form-label">Nome do Álbum</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="descricao"
                                           value="{{ old('descricao', $album->descricao) }}" required>
                                    <label class="form-label">Descrição</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <p><b>Turmas</b></p>
                                    <select name="turmas[]" id="turmas" class="form-control show-tick"
                                            data-live-search="true" multiple>
                                        @foreach($turmas as $turma)
                                            <option value="{{ $turma->id }}" {{ $album->turmas->contains($turma->id) ? 'selected':'' }}>{{ $turma->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div style="width: 100%">
                            <button class="btn btn-primary waves-effect" style="width: 100%" type="submit">CRIAR ÁLBUM</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Advanced Select -->
@endsection

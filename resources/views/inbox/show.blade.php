@extends('templates.master')

@push('scripts')
    <!-- Custom Js -->
    <script src="js/admin.js"></script>
@endpush

@section('content')

    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ $item->mensagem->assunto }}
                    </h2>
                    @if($item->user_id == Auth::user()->id)
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
                                   role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    @if($item->mensagem->user->id != Auth::user()->id)
                                        <li><a href="{{ route('inbox.arquivar', $item->hash_id) }}">{{ $item->arquivado == null ? 'Arquivar':'Desarquivar' }}</a></li>
                                        <li><a href="{{ route('inbox.favoritar', $item->hash_id) }}">{{ $item->favorito == null ? 'Favoritar':'Desfavoritar' }}</a></li>
                                    @endif
                                    <li><a href="{{ route('inbox.excluir', $item->hash_id) }}">{{ $item->deleted_at == null ? 'Excluir':'Restaurar' }}</a></li>
                                </ul>
                            </li>
                        </ul>
                    @endif
                </div>
                <div class="body">
                    <a href="{{ route('user.perfil.show', $item->mensagem->user->hash_id) }}" style=" width: 100%; border: 0px solid #fff; border-radius: 8px; background-color: #fff !important;" name="confirma" type="submit" value="Favoritar">
                        <div style="position: absolute; border-radius: 50%; overflow: hidden; box-shadow: 3px 3px 5px #555;">
                            <img src="/storage/users/{{ $item->mensagem->user->id }}.png" width="50" height="50"/>
                        </div>
                    </a>
                    <h2 style="margin-left: 70px; margin-top: 5px;" class="card-inside-title">
                        <b>{{ $item->mensagem->user->name }}</b>
                    </h2>
                    <div style="margin-left: 70px; margin-top: 5px;" >
                        {{ $item->mensagem->created_at->format('d/m/Y \á\s H:i') }}
                    </div>


                    <br><br>
                    <p style="text-align: justify">
                        @php
                            /** @var TYPE_NAME $item */
                            $texto = nl2br($item->mensagem->mensagem);
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
                    <br>
                    @if($item->mensagem->user->id != Auth::user()->id && $item->user_id == Auth::user()->id)
                        <form method="POST" action="">
                            <input name="id" type="hidden">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <textarea name="mensagem" cols="30" rows="2" class="form-control no-resize" required></textarea>
                                    <label class="form-label">Responder...</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-block bg-blue waves-effect">ENVIAR RESPOSTA</button>
                        </form>
                    @elseif($item->mensagem->user->id == Auth::user()->id || \Illuminate\Support\Facades\Auth::user()->role == 200)
                        <p style="text-align: justify">
                            <b>
                                Enviado para:

                            @foreach($item->mensagem->itens->where('id', '!=', $item->mensagem->user_id) as $user)
                                <a style="color:#2196F3;" rel="nofollow" href="{{ route('user.perfil.show', $user->hash_id) }}">{{ $user->name }}</a>@if(!$loop->last), @endif
                            @endforeach
                            </b>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->

@endsection

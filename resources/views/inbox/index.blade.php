@extends('templates.master')

@push('head')
    <link href="plugins/fab/fab.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- Custom Js -->
    <script src="js/admin.js"></script>
@endpush

@section('content')
    <div class="fab">
        <button class="main">
            <a href="{{ route('inbox.edit', 0) }}" style="width: 100%; height: 100%">
                <i class="material-icons" style="padding-top: 8px; color: #fff">add</i>
            </a>
        </button>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        @if(\Request::route()->getName() == 'inbox.index')
                            CAIXA DE ENTRADA
                        @elseif(\Request::route()->getName() == 'inbox.arquivadas')
                            CAIXA DE ENTRADA - ARQUIVADAS
                        @elseif(\Request::route()->getName() == 'inbox.favoritas')
                            CAIXA DE ENTRADA - FAVORITAS
                        @elseif(\Request::route()->getName() == 'inbox.excluidas')
                            CAIXA DE ENTRADA - EXCLUÍDAS
                        @elseif(\Request::route()->getName() == 'inbox.enviadas')
                            CAIXA DE ENTRADA - ENVIADAS
                        @endif
                    </h2>
                </div>
                <div class="body">
                    <div class="list-group">
                        @foreach($itens as $item)
                            @if($item->mensagem->user->id != Auth::user()->id && \Request::route()->getName() == 'inbox.enviadas')
                                @continue
                            @elseif($item->mensagem->user->id == Auth::user()->id && \Request::route()->getName() != 'inbox.enviadas')
                                @continue
                            @endif
                            <a href="{{ route('inbox.show', $item->hash_id) }}" class="list-group-item"
                               style="{{ $item->viewed_at == null || $item->mensagem->user->id == Auth::user()->id  ? '':'background: #ededed;' }}">
                                <div style="position: absolute; margin-top: 11px; margin-left: 12px;
                                        border-radius: 50%; overflow: hidden; box-shadow: 3px 3px 5px #555;
                                        width: 60px; height: 60px;">
                                    <img width="60px" height="60px"
                                         src="/storage/users/{{ $item->mensagem->user->id }}.png">
                                </div>
                                <table style="width: 100%">
                                    <tr>
                                        <td>
                                            <p style="margin-left: 100px; font-size: 18px;">
                                                <b>
                                                    {{ $item->mensagem->user->name }}
                                                </b>
                                            </p>
                                        </td>
                                        <td style="text-align: right;">
                                            {{ $item->mensagem->created_at->format('d/m/Y \á\s H:i') }}
                                            &nbsp;
                                        </td>
                                        @if($item->mensagem->user->id != Auth::user()->id)
                                            <td style="text-align: left;">
                                                <i class="material-icons" style="color: #FBC02D;">
                                                    {{ $item->favorito == null ? 'star_border':'star' }}
                                                </i>
                                            </td>
                                        @endif
                                    </tr>
                                </table>
                                <p style="margin-left: 100px; font-size: 14px;">
                                    <b>{{ $item->mensagem->assunto }}</b>
                                    <br>
                                    {{ mb_strimwidth($item->mensagem->mensagem, 0, 130, '...') }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->

@endsection

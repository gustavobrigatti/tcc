@extends('templates.master')

@push('head')
    <link href="plugins/fab/fab.css" rel="stylesheet">
    <!-- Light Gallery Plugin Css -->
    <link href="../../plugins/light-gallery/css/lightgallery.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- Light Gallery Plugin Js -->
    <script src="../../plugins/light-gallery/js/lightgallery-all.js"></script>

    <!-- Custom Js -->
    <script src="../../js/pages/medias/image-gallery.js"></script>
    <script src="../../js/admin.js"></script>
@endpush

@section('content')
    @if(\Illuminate\Support\Facades\Auth::user()->role <= 300)
        <div class="fab">
            <button class="main">
                <a href="{{ route('album.foto', $album->hash_id) }}" style="width: 100%; height: 100%">
                    <i class="material-icons" style="padding-top: 8px; color: #fff">add</i>
                </a>
            </button>
        </div>
    @endif
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            {{ $album->nome }}
                            <small>{{ $album->descricao }}</small>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="{{ route('album.edit', $album->hash_id) }}">Editar</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div id="aniimated-thumbnials" class="list-unstyled row clearfix">
                            @foreach($fotos as $foto)
                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="height: 128px; overflow: hidden;">
                                    <a href="/storage/albuns/{{ $album->id }}/{{ basename($foto) }}" data-sub-html="{{ $album->descricao }}">
                                        <img class="img-responsive thumbnail" style="object-fit: cover; height: 100%; width: 100%" src="/storage/albuns/{{ $album->id }}/{{ basename($foto) }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

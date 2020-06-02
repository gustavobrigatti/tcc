@extends('templates.master')

@push('head')
    <link href="plugins/fab/fab.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- Custom Js -->
    <script src="js/admin.js"></script>
@endpush

@section('content')
    @if(\Illuminate\Support\Facades\Auth::user()->role <= 300)
        <div class="fab">
            <button class="main">
                <a href="{{ route('album.edit', 0) }}" style="width: 100%; height: 100%">
                    <i class="material-icons" style="padding-top: 8px; color: #fff">add</i>
                </a>
            </button>
        </div>
    @endif
    <div class="row clearfix">
        <!-- Basic Example -->
        @foreach($albuns as $album)
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <a href="{{ route('album.show', $album->hash_id) }}"><h2>{{ $album->nome }}</h2></a>
                        @if(\Illuminate\Support\Facades\Auth::user()->role <= 300)
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
                                       role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="{{ route('album.edit', $album->hash_id) }}">Editar</a></li>
                                    </ul>
                                </li>
                            </ul>
                        @endif
                    </div>
                    <div class="body">
                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner" role="listbox">
                                <div class="item active" style="height: 256px; overflow: hidden;">
                                    <a href="{{ route('album.show', $album->hash_id) }}">
                                        <img style="object-fit: cover; height: 100%; width: 100%" src="/storage/albuns/{{ $album->id }}/{{ basename($fotos[$album->id]) }}"/>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    @endforeach
    <!-- #END# Basic Example -->
    </div>
@endsection

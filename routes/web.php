<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Traits\Hashid;

//Carrega a tela de login
Route::get('/login', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return view('login.login');
})->name('login');
//Leva para o metódo que faz a verificação do login
Route::post('/login', ['as' => 'user.login', 'uses' => 'DashboardController@auth']);
//Faz o logout do usuário
Route::get('/logout', function () {
    return redirect()->route('login');
})->name('logout');

//Rotas acessadas apenas por usuários logados
Route::group(['middleware' => ['auth']], function () {

    //Carrega a página principal
    Route::get('/', ['as' => 'index', 'uses' => 'DashboardController@index']);

    //Leva para o método que exibe a página de perfil
    Route::get('/user/{useer}', ['as' => 'user.perfil.show', 'uses' => 'UsersController@show']);
    //Leva para o metódo que faz a edição do perfil de usuário
    Route::post('/user/{user}/perfil', ['as' => 'user.perfil', 'uses' => 'UsersController@perfil']);
    //Leva para o metódo que faz a edição da senha do usuário
    Route::post('/user/{user}/senha', ['as' => 'user.senha', 'uses' => 'UsersController@senha']);

    Route::get('/inbox/enviadas', ['as' => 'inbox.enviadas', 'uses' => 'InboxController@enviadas']);
    Route::get('/inbox/favoritas', ['as' => 'inbox.favoritas', 'uses' => 'InboxController@favoritas']);
    Route::get('/inbox/arquivadas', ['as' => 'inbox.arquivadas', 'uses' => 'InboxController@arquivadas']);
    Route::get('/inbox/excluidas', ['as' => 'inbox.excluidas', 'uses' => 'InboxController@excluidas']);
    Route::resource('inbox', 'InboxController');
    Route::get('/inbox/{inbox}/arquivar', ['as' => 'inbox.arquivar', 'uses' => 'InboxController@arquivar']);
    Route::get('/inbox/{inbox}/favoritar', ['as' => 'inbox.favoritar', 'uses' => 'InboxController@favoritar']);
    Route::get('/inbox/{inbox}/excluir', ['as' => 'inbox.excluir', 'uses' => 'InboxController@excluir']);

    //Leva para o método que faz a busca de todos os albuns que o usuário pode visualizar
    Route::get('/album', ['as' => 'album.index', 'uses' => 'AlbunsController@index']);
    //Leva para o métpdp que exibe um álbum específico
    Route::get('/album/{album}' , ['as' => 'album.show', 'uses' => 'AlbunsController@show']);

    //Rotas acessadas apenas por usuários logados do tipo 100 ou 200
    Route::group(['middleware' => ['CheckUserRole:100,200']], function () {
        //Rotas para usuário
        Route::resource('user', 'UsersController');
        //Leva para o metódo que redefine a senha do usuário
        Route::get('/user/{user}/resetarSenha', ['as' => 'user.resetarSenha', 'uses' => 'UsersController@resetarSenha']);
    });
    //Rotas acessadas apenas por usuários logados do tipo 100, 200 ou 300
    Route::group(['middleware' => ['CheckUserRole:100,200,300']], function () {
        //Rota para página de adição/edição de album
        Route::get('/album/{album}/edit', ['as' => 'album.edit', 'uses' => 'AlbunsController@edit']);
        //Rota para o método de adição de álbum
        Route::post('/album', ['as' => 'album.store', 'uses' => 'AlbunsController@store']);
        //Rota para o método de edição de álbum
        Route::put('/album/{album}', ['as' => 'album.update', 'uses' => 'AlbunsController@update']);
        //Rota para a exclusão de álbum
        Route::delete('/album/{album}', ['as' => 'album.destroy', 'uses' => 'AlbunsController@destroy']);
        //Rota para a página de adição de fotos
        Route::get('/album/{album}/foto' , ['as' => 'album.foto', 'uses' => 'AlbunsController@showFoto']);
        //Rota para o método de adição de fotos
        Route::post('/album/{album}/foto' , ['as' => 'album.foto', 'uses' => 'AlbunsController@storeFoto']);
    });
    //Rotas acessadas apenas por usuários logados do tipo 100
    Route::group(['middleware' => ['CheckUserRole:100']], function () {
        //Rotas para instituição
        Route::resource('instituicao', 'InstituicoesController');
    });
    //Rotas acessadas apenas por usuários logados do tipo 200
    Route::group(['middleware' => ['CheckUserRole:200']], function () {
        //Rotas para instituição
        Route::resource('turma', 'TurmasController');
        //Rotas para aulas
        Route::resource('aula', 'AulaController');
        //Rotas para aula_turma
        Route::resource('aulaTurma', 'AulaTurmaController');
    });
    //Rotas acessadas apenas por usuários logados do tipo 400 e 500
    Route::group(['middleware' => ['CheckUserRole:400,500']], function () {
        //Rotas para grade de horários
        Route::get('/grade', function (){
            return view('grade.index');
        })->name('grade');
    });
});




<?php


namespace App\Http\Controllers;


use App\Models\Album;
use App\Models\Turma;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

class AlbunsController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;
        if ($role == 100) {
            $albuns = Album::all();
        } else if ($role <= 300) {
            $albuns = Album::where('escola_id', Auth::user()->escola_id)->orderBy('created_at')->get();
        } else if ($role <= 500) {
            if ($role == 400) {
                $turmas = [];
                foreach(Auth::user()->aulaTurma as $aulaTurma){
                    $turmas[] = $aulaTurma->turma->id;
                }
                $turmas = array_unique($turmas);
                $albuns_id = DB::table('album_turmas')->whereIn('turma_id', $turmas)->pluck('album_id')->all();
            } else {
                $albuns_id = DB::table('album_turmas')->whereIn('turma_id', Auth::user()->aluno_turmas->pluck('id')->all())->pluck('album_id')->all();
            }
            $albuns = Album::whereIn('id', $albuns_id)->orderBy('created_at')->get();
        } else {
            $turmas_id = DB::table('alunos_turma')->whereIn('user_id', Auth::user()->alunos->pluck('id')->all())->pluck('turma_id')->all();
            $albuns_id = DB::table('album_turmas')->whereIn('turma_id', $turmas_id)->pluck('album_id')->all();
            $albuns = Album::whereIn('id', $albuns_id)->orderBy('created_at')->get();
        }

        $fotos = [];

        foreach ($albuns as $album) {
            $foto = glob(storage_path("app/public/albuns") . "/{$album->id}/*.*");
            $rand = rand(0, count($foto) - 1);
            $fotos[$album->id] = $foto[$rand];
        }

        return view('album.index', [
            'albuns' => $albuns,
            'fotos' => $fotos
        ]);
    }

    public function show($id)
    {
        $album = Album::findOrFail($id);
        $fotos = glob(storage_path("app/public/albuns") . "/{$id}/*.*");
        return view('album.show', [
            'album' => $album,
            'fotos' => $fotos
        ]);
    }

    public function edit($id)
    {
        $turmas = Turma::where('escola_id', Auth::user()->escola_id)->orderBy('nome')->get();
        $album = $id > 0 ? Album::findOrFail($id) : new Album();
        return view('album.edit', [
            'album' => $album,
            'turmas' => $turmas,
            'id' => $id
        ]);
    }

    public function store(Request $request)
    {
        $album = new Album();
        $validator = $this->validator($request);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->input());
        }

        $album = $this->setValue($album, $request);
        $album->user_id = Auth::user()->id;
        $album->escola_id = Auth::user()->escola_id;
        $album->save();
        $album->turmas()->sync($request->turmas);
        return redirect()->route('album.index');
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validator($request);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->input());
        }

        $album = Album::findOrFail($id);
        $album = $this->setValue($album, $request);
        $album->save();
        $album->turmas()->sync($request->turmas);
        return redirect()->route('album.index');
    }

    public function destroy($id)
    {
        $album = Album::findOrFail($id);

        $album->delete();

        return redirect()->route('album.index');
    }

    public function showFoto($id)
    {
        $album = Album::findOrFail($id);
        return view('album.showFoto', [
            'album' => $album
        ]);
    }

    public function storeFoto(Request $request, $id)
    {
        $this->validate($request, [
            'file[]' => 'bail|image|mimes:jpeg,png,jpg'
        ]);

        foreach ($request->file('file') as $file) {

            $nameFile = (int)Carbon::now()->getPreciseTimestamp(4) . '.png';

            $upload = $file->storeAs('public/albuns/' . $id, $nameFile);
        }

        if (!$upload) {
            return redirect()
                ->back()
                ->withErrors('error', 'Falha ao fazer o upload da imagem.');
        }
    }

    public function validator($request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|max:100',
            'descricao' => 'required|max:200'
        ]);

        return $validator;
    }

    public function setValue($album, $request)
    {
        $album->nome = $request->nome;
        $album->descricao = $request->descricao;
        return $album;
    }
}

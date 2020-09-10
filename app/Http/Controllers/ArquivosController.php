<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Arquivo;
use App\Models\Item_Arquivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArquivosController extends Controller
{
    public function index(){

        if (Auth::user()->role == 400){
            $arquivos = Arquivo::where('user_id', Auth::user()->id)->orderBy('turma_id')->orderBy('nome')->get();
        }elseif (Auth::user()->role == 500){
            $turmas_id = [];
            foreach(Auth::user()->aluno_turmas as $turma){
                $turmas_id[] = $turma->id;
            }
            $turmas_id = array_unique($turmas_id);
            $arquivos = Arquivo::whereIn('turma_id', $turmas_id)->orderBy('turma_id')->orderBy('nome')->get();
        }
        return view('arquivo.index', [
            'arquivos' => $arquivos
        ]);
    }

    public function show($id){
        $arquivo = Arquivo::findOrFail($id);
        return view('arquivo.show', [
            'arquivo' => $arquivo,
            'id' => $id
        ]);
    }

    public function download($id){
        $item = Item_Arquivo::findOrFail($id);
        return Storage::download($item->path . '/' . $item->nome);
    }

    public function delete($id){
        $item = Item_Arquivo::findOrFail($id);
        Storage::delete($item->path . '/' . $item->nome);
        $item->delete();
        return redirect()->back();
    }

    public function upload(Request $request, $id){
        $this->validate($request, [
            'file' => 'bail|required',
        ],
        [
            'file.required' => 'O campo arquivo é obrigatório.'
        ]);

        $arquivo = Arquivo::findOrFail($id);
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file')->getClientOriginalName();
            $item = new Item_Arquivo();
            $item->arquivo_id = $id;
            $item->user_id = Auth::user()->id;
            $item->nome = $file;
            if (Auth::user()->role == 400){
                $item->path = '/arquivos/' . $arquivo->turma->id . '/' . $id . '/professor';
            }elseif (Auth::user()->role == 500){
                $item->path = '/arquivos/' . $arquivo->turma->id . '/' . $id . '/aluno';
            }
            $upload = $request->file('file')->storeAs($item->path, $file);
            $item->save();
        }
        return;
    }
}

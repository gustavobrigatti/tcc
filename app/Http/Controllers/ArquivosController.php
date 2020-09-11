<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Arquivo;
use App\Models\Aula_Turma;
use App\Models\Item_Arquivo;
use App\Models\Turma;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Vinkla\Hashids\Facades\Hashids;

class ArquivosController extends Controller
{
    public function index(){
        if (isset($_GET['t'])){
            if (Hashids::decode($_GET['t']) == null){
                return view('errors.404');
            }
            $turma = Turma::where('id', Hashids::decode($_GET['t']))->get()->first();
            if (Auth::user()->role == 400){
                $aulasTurma = Aula_Turma::where('turma_id', Hashids::decode($_GET['t']))->where('user_id', Auth::user()->id)->get();
                $aulas = [];
                foreach ($aulasTurma as $aulaTurma){
                    $aulas[] = $aulaTurma->aula;
                }
            }elseif (Auth::user()->role == 500){
                $aulas = [];
                foreach ($turma->aulas as $aula){
                    $aulas[] = $aula->aula;
                }
            }
            $aulas = array_unique($aulas);
            return view('arquivo.index', [
                'aulas' => $aulas,
                'turma' => $turma
            ]);
        }else{
            $turmas_id = [];
            if (Auth::user()->role == 400){
                foreach (Auth::user()->aulaTurma as $aulaTurma){
                    $turmas_id[] = $aulaTurma->turma_id;
                }
                $turmas_id = array_unique($turmas_id);
            }elseif (Auth::user()->role == 500){
                foreach (Auth::user()->aluno_turmas as $turma){
                    $turmas_id[] = $turma->id;
                }
            }
            $turmas = Turma::whereIn('id', $turmas_id)->get();
            return view('arquivo.index', [
                'turmas' => $turmas
            ]);
        }
    }

    public function show($id){
        if (isset($_GET['a'])){
            if (Hashids::decode($_GET['a']) == null){
                return redirect()->back();
            }
            $arquivo = Arquivo::where('turma_id', $id)->where('aula_id', Hashids::decode($_GET['a']))->get()->first();
        }else{
            return redirect()->back();
        }
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

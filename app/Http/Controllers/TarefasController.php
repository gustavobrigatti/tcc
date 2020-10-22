<?php


namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Aula_Turma;
use App\Models\Comentario_Tarefa;
use App\Models\Item_Tarefa;
use App\Models\Tarefa;
use App\Models\Turma;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\Request;

class TarefasController extends Controller
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
            }elseif (Auth::user()->role == 200 || Auth::user()->role == 500 || Auth::user()->role == 600){
                $aulas = [];
                foreach ($turma->aulas as $aula){
                    $aulas[] = $aula->aula;
                }
            }
            $aulas = array_unique($aulas);
            return view('tarefa.index', [
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
            }elseif (Auth::user()->role == 600 && isset($_GET['al'])){
                if (Hashids::decode($_GET['al']) == null){
                    return redirect()->back();
                }
                foreach (User::where('id', Hashids::decode($_GET['al']))->first()->aluno_turmas as $turma){
                    $turmas_id[] = $turma->id;
                }
            }
            $turmas = Turma::whereIn('id', $turmas_id)->get();
            return view('tarefa.index', [
                'turmas' => $turmas
            ]);
        }
    }

    public function show($id){
        if (isset($_GET['a']) && isset($_GET['t']) && isset($_GET['al'])){
            if (Hashids::decode($_GET['a']) == null || Hashids::decode($_GET['t']) == null || Hashids::decode($_GET['al']) == null){
                return redirect()->back();
            }
            $tarefa = Tarefa::findOrFail($id);
            $user = User::where('id', Hashids::decode($_GET['al']))->get()->first();
            return view('tarefa.show', [
                'tarefa' => $tarefa,
                'user' => $user,
                'id' => $id
            ]);
        }elseif (isset($_GET['a']) && isset($_GET['t'])){
            if (Hashids::decode($_GET['a']) == null || Hashids::decode($_GET['t']) == null){
                return redirect()->back();
            }
            $tarefa = Tarefa::findOrFail($id);
            return view('tarefa.show', [
                'tarefa' => $tarefa,
                'id' => $id
            ]);
        }elseif (isset($_GET['a'])){
            if (Hashids::decode($_GET['a']) == null){
                return redirect()->back();
            }
            $turma = Turma::findOrFail($id);
            $aula = Aula::where('id', Hashids::decode($_GET['a']))->get()->first();
            $tarefas = Tarefa::where('turma_id', $id)->where('aula_id', Hashids::decode($_GET['a']))->get();
            return view('tarefa.show', [
                'turma' => $turma,
                'tarefas' => $tarefas,
                'aula' => $aula,
                'id' => $id
            ]);
        }else{
            return redirect()->back();
        }
    }

    public function edit($id){
        if (Auth::user()->role != 400){
            return redirect()->back();
        }
        $tarefa = $id > 0 ? Tarefa::findOrFail($id) : new Tarefa();
        $turmas = [];
        foreach (Auth::user()->aulaTurma as $aulaTurma){
            $turmas[] = $aulaTurma->turma;
        }
        $turmas = array_unique($turmas);
        return view('tarefa.edit', [
            'tarefa' => $tarefa,
            'turmas' => $turmas,
            'id' => $id
        ]);
    }

    public function buscaAula(Request $request){
        $success = false;
        $aulasTurma = Aula_Turma::where('turma_id', $request->turma)->where('user_id', Auth::user()->id)->get();
        $aulas = [];
        foreach ($aulasTurma as $aulaTurma){
            $aulas[] = $aulaTurma->aula;
        }
        $aulas = array_unique($aulas);
        if (count($aulas) > 0){
            $success = true;
        }
        return response()->json([
            'success' => $success,
            'aulas' => $aulas
        ]);
    }

    public function save($request, $tarefa){
        $this->validate($request, [
            'nome' => 'bail|required',
            'data_entrega' => 'bail|required|date_format:d/m/Y',
            'descricao' => 'bail|required',
            'turma_id' => 'bail|required',
            'aula_id' => 'bail|required',

        ],
        [
            'data_entrega.required' => 'O campo data de entrega é obrigatório.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'turma_id.required' => 'O campo turma é obrigatório.',
            'aula_id.required' => 'O campo aula é obrigatório.'
        ]);

        $tarefa->fill($request->input());
        $tarefa->data_entrega = Carbon::createFromFormat('d/m/Y', $request->input('data_entrega', date('d/m/Y')))->toDateString();
        $tarefa->save();
    }

    public function store(Request $request){
        $tarefa = new Tarefa();
        $tarefa->user_id = Auth::user()->id;
        $this->save($request, $tarefa);
        return redirect()->route('tarefa.index');
    }

    public function update(Request $request, $id){
        $tarefa = Tarefa::findOrFail($id);
        $this->save($request,$tarefa);
        return redirect()->route('tarefa.index');
    }

    public function destroy($id){
        if (Auth::user()->role != 400){
            return redirect()->back();
        }
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->delete();
        return redirect()->route('tarefa.index');
    }

    public function download($id){
        $item = Item_Tarefa::findOrFail($id);
        return Storage::download($item->path . '/' . $item->nome);
    }

    public function delete($id){
        $item = Item_Tarefa::findOrFail($id);
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
        $tarefa = Tarefa::findOrFail($id);
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file')->getClientOriginalName();
            $item = new Item_Tarefa();
            $item->tarefa_id = $id;
            $item->user_id = Auth::user()->id;
            $item->nome = $file;
            if (Auth::user()->role == 400){
                $item->tipo = 'professor';
                $item->path = '/tarefas/' . $tarefa->turma->id . '/' . $tarefa->aula->id . '/' . $id . '/professor';
            }elseif (Auth::user()->role == 500){
                $item->tipo = 'aluno';
                $item->path = '/tarefas/' . $tarefa->turma->id . '/' . $tarefa->aula->id . '/' . $id . '/aluno';;
            }
            $upload = $request->file('file')->storeAs($item->path, $file);
            $item->save();
        }
        return;
    }

    public function storeComentario(Request $request, $id){
        $comentario = new Comentario_Tarefa();
        $this->validate($request, [
            'comentario' => 'bail|required',

        ],
        [
            'comentario.required' => 'O campo comentário é obrigatório.',
        ]);
        $comentario->fill($request->input());
        $comentario->tarefa_id = $id;
        $comentario->save();
        return redirect()->back();
    }

    public function deleteComentario($id){
        $comentario = Comentario_Tarefa::findOrFail($id);
        $comentario->delete();
        return redirect()->back();
    }
}

<?php


namespace App\Http\Controllers;


use App\Models\Aula;
use App\Models\Aula_Turma;
use App\Models\Tarefa;
use App\Models\Turma;
use Illuminate\Support\Facades\Auth;
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
            }elseif (Auth::user()->role == 500){
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
            }
            $turmas = Turma::whereIn('id', $turmas_id)->get();
            return view('tarefa.index', [
                'turmas' => $turmas
            ]);
        }
    }

    public function show($id){
        $turma = Turma::findOrFail($id);
        if (isset($_GET['a'])){
            if (Hashids::decode($_GET['a']) == null){
                return redirect()->back();
            }
            $tarefas = Tarefa::where('turma_id', $id)->where('aula_id', Hashids::decode($_GET['a']))->get();
            dd($tarefas);
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
            'turma_id' => 'bail|required',
            'aula_id' => 'bail|required'
        ],
        [
            'turma_id.required' => 'O campo turma é obrigatório.',
            'aula_id.required' => 'O campo aula é obrigatório.'
        ]);
        $tarefa->fill($request->input());
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
        $tarefa = $id > 0 ? Tarefa::findOrFail($id) : new Tarefa();
        return redirect()->route('tarefas.index');
    }
}

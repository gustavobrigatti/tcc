<?php


namespace App\Http\Controllers;


use App\Models\Aula_Turma;
use App\Models\Media;
use App\Models\Nota;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;

class NotasController extends Controller
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
            return view('nota.index', [
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
            return view('nota.index', [
                'turmas' => $turmas
            ]);
        }
    }

    public function show($id){
        if (Auth::user()->role == 400){
            if (isset($_GET['a']) && isset($_GET['al'])){
                $aluno = User::where('id', Hashids::decode($_GET['al']))->first();
                $media = $aluno->media->where('aula_id', Hashids::decode($_GET['a'])[0])->where('turma_id', $id)->first();
                if ($media == null){
                    $media = 0;
                }else{
                    $media = $media->media;
                }
                return view('nota.show',[
                    'id' => $id,
                    'aluno' => $aluno,
                    'media' => $media
                ]);
            }elseif (isset($_GET['a'])){
                $turma = Turma::findOrFail($id);
                return view('nota.show',[
                    'id' => $id,
                    'turma' => $turma
                ]);
            }else{
                return redirect()->back();
            }
        }elseif(Auth::user()->role == 500){
            return view('nota.show', [
                'id' => $id
            ]);
        }
    }

    public function edit($id){
        if (Auth::user()->role != 400){
            return redirect()->back();
        }
        $nota = $id > 0 ? Nota::findOrFail($id) : new Nota();
        $turmas = [];
        foreach (Auth::user()->aulaTurma as $aulaTurma){
            $turmas[] = $aulaTurma->turma;
        }
        $turmas = array_unique($turmas);
        return view('nota.edit', [
            'nota' => $nota,
            'turmas' => $turmas,
            'id' => $id
        ]);
    }

    public function save($request, $nota){
        $this->validate($request, [
            'nome' => 'bail|required',
            'nota' => 'bail|required|between:0,10.0',
            'user_id' => 'bail|required',
            'turma_id' => 'bail|required',
            'aula_id' => 'bail|required',

        ],
        [
            'user_id.required' => 'O campo aluno é obrigatório.',
            'turma_id.required' => 'O campo turma é obrigatório.',
            'aula_id.required' => 'O campo aula é obrigatório.'
        ]);

        $nota->fill($request->input());
        $nota->nota = str_replace(',', '.', $nota->nota);
        $nota->save();
    }

    public function store(Request $request){
        $nota = new Nota();
        $this->save($request, $nota);
        return redirect()->route('nota.index');
    }

    public function update(Request $request, $id){
        $nota = Nota::findOrFail($id);
        $this->save($request,$nota);
        return redirect()->route('nota.index');
    }

    public function destroy($id){
        if (Auth::user()->role != 400){
            return redirect()->back();
        }
        $nota = Nota::findOrFail($id);
        $nota->delete();
        return redirect()->route('nota.index');
    }

    public function buscaAluno(Request $request){
        $success = false;
        $turma = Turma::where('id', $request->turma)->first();
        $alunos = $turma->alunos;
        return response()->json([
            'success' => $success,
            'alunos' => $alunos
        ]);
    }

    public function media(Request $request){
        $user = User::findOrFail($request->user_id);
        if (count($user->media->where('aula_id', $request->aula_id)->where('turma_id', $request->turma_id)) == 0){
            $media = new Media();
            $media->user_id = $request->user_id;
            $media->aula_id = $request->aula_id;
            $media->turma_id = $request->turma_id;
        }else{
            $media = $user->media->first();
        }
        $media->media = str_replace(',', '.', $request->media);
        $media->save();
        return redirect()->back();
    }
}

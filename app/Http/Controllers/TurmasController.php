<?php


namespace App\Http\Controllers;


use App\Models\Aula;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TurmasController extends Controller
{
    public function index()
    {
        $turmas = Turma::where('escola_id', Auth::user()->escola_id)->get();

        return view('turma.index', [
            'turmas' => $turmas,
            'ad' => 'ad'
        ]);
    }

    public function edit($id)
    {
        $aulas = Aula::where('escola_id', Auth::user()->escola_id)->orderBy('nome')->get();
        $professores = User::where('escola_id', Auth::user()->escola_id)->where('role', '400')->orderBy('name')->get();
        $alunos = User::where('escola_id', Auth::user()->escola_id)->where('role', '500')->orderBy('name')->get();
        $turma = $id > 0 ? Turma::findOrFail($id) : new Turma();
        return view('turma.edit', [
            'aulas' => $aulas,
            'turma' => $turma,
            'profesores' => $professores,
            'alunos' => $alunos,
            'id' => $id,
            'ad' => 'ad'
        ]);
    }

    public function store(Request $request)
    {
        $turma = new Turma();
        $validator = $this->validator($request, $turma->id);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->input());
        }

        $turma = $this->setValue($turma, $request);
        $turma->escola_id = Auth::user()->escola_id;
        $turma->save();
        $turma->alunos()->sync($request->alunos);
        return redirect()->route('turma.index');
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


        $turma = Turma::findOrFail($id);
        $turma = $this->setValue($turma, $request);
        $turma->alunos()->sync($request->alunos);
        $turma->save();
        return redirect()->route('turma.index');
    }

    public function destroy($id)
    {
        $turma = Turma::findOrFail($id);

        $turma->delete();

        return redirect()->route('turma.index');
    }

    public function validator($request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|max:100',
            'periodo' => 'required',
            'escolaridade' => 'required',
        ]);

        return $validator;
    }

    public function setValue($turma, $request)
    {
        $turma->nome = $request->nome;
        $turma->periodo = $request->periodo;
        $turma->escolaridade = $request->escolaridade;
        return $turma;
    }

}

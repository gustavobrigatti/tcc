<?php


namespace App\Http\Controllers;


use App\Models\Arquivo;
use App\Models\Aula_Turma;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AulaTurmaController extends Controller
{
    public function store(Request $request){
        $validator = $this->validator($request);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->input());
        }

        $user = User::findOrFail($request->user_id);
        foreach ($user->aulaTurma as $aulaTurma){
            if ($request->dia_semana == $aulaTurma->dia_semana){
                if ((($request->hora_inicio > $aulaTurma->hora_inicio) && ($request->hora_inicio < $aulaTurma->hora_fim)) || (($request->hora_fim > $aulaTurma->hora_inicio) && ($request->hora_fim < $aulaTurma->hora_fim))){
                    return redirect()
                        ->back()
                        ->with('alert', 'Professor com horário indisponível.')
                        ->withInput($request->input());
                }
            }
        }

        $turma = Turma::findOrFail($request->turma_id);
        foreach ($turma->aulas as $aula){
            if ($request->dia_semana == $aula->dia_semana){
                if ((($request->hora_inicio > $aula->hora_inicio) && ($request->hora_inicio < $aula->hora_fim)) || (($request->hora_fim > $aula->hora_inicio) && ($request->hora_fim < $aula->hora_fim))){
                    return redirect()
                        ->back()
                        ->with('alert', 'Turma com horário indisponível.')
                        ->withInput($request->input());
                }
            }
        }

        $aulaTurma = new Aula_Turma();
        $aulaTurma = $this->setValue($aulaTurma, $request);
        $aulaTurma->save();

        $arquivo = Arquivo::where('turma_id', $request->turma_id)->where('aula_id', $aulaTurma->aula->id)->get();
        if (count($arquivo) == 0){
            $arquivo = new Arquivo();
            $arquivo->turma_id = $request->turma_id;
            $arquivo->user_id = $request->user_id;
            $arquivo->aula_id = $aulaTurma->aula->id;
            $arquivo->save();
        }
        return redirect()->back();
    }

    public function destroy($id){
        $aulaTurma = Aula_Turma::findOrFail($id);
        $aulaTurma->delete();
        return redirect()->back();
    }

    public function validator($request)
    {
        $validator = Validator::make($request->all(), [
            'aula_id' => 'required',
            'user_id' => 'required',
            'turma_id' => 'required',
            'dia_semana' => 'required',
            'hora_inicio' => 'required',
            'hora_fim' => 'required'
        ]);

        return $validator;
    }

    public function setValue($aulaTurma, $request)
    {
        $aulaTurma->aula_id = $request->aula_id;
        $aulaTurma->user_id = $request->user_id;
        $aulaTurma->turma_id = $request->turma_id;
        $aulaTurma->hora_inicio = $request->hora_inicio;
        $aulaTurma->hora_fim = $request->hora_fim;
        $aulaTurma->dia_semana = $request->dia_semana;
        return $aulaTurma;
    }
}

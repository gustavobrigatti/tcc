<?php


namespace App\Http\Controllers;


use App\Models\Aula_Turma;
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
        $aulaTurma = new Aula_Turma();
        $aulaTurma = $this->setValue($aulaTurma, $request);
        $aulaTurma->save();
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
        return $aulaTurma;
    }
}

<?php


namespace App\Http\Controllers;

use App\Models\Aula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AulaController extends Controller
{
    public function index(){
        $aulas = Aula::where('escola_id', Auth::user()->escola_id)->orderBy('nome')->get();
        return view('aula.index', [
            'aulas' => $aulas,
            'ad' => true
        ]);
    }

    public function edit($id){
        $aula =  $id > 0 ? Aula::findOrFail($id) : new Aula();
        return view('aula.edit', [
            'aula' => $aula,
            'id' => $id,
            'ad' => true
        ]);
    }

    public function store(Request $request){
        $validator = $this->validator($request);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->input());
        }

        $aula = new Aula();
        $aula = $this->setValue($aula, $request);
        $aula->save();
        return redirect()->route('aula.index');
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

        $aula = Aula::findOrFail($id);
        $aula = $this->setValue($aula, $request);
        $aula->save();
        return redirect()->route('aula.index');
    }

    public function destroy($id)
    {
        $aula = Aula::findOrFail($id);

        $aula->delete();

        return redirect()->route('aula.index');
    }

    public function validator($request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|max:100',
        ]);

        return $validator;
    }

    public function setValue($aula, $request)
    {
        $aula->escola_id = Auth::user()->escola_id;
        $aula->nome = $request->nome;
        return $aula;
    }
}

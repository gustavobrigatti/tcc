<?php


namespace App\Http\Controllers;


use App\Models\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;

class InstituicoesController extends Controller
{

    public function index()
    {
        $instituicoes = Instituicao::all();
        return view('instituicao.index', [
            'instituicoes' => $instituicoes,
            'ad' => 'ad',
        ]);
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        $instituicao = $id > 0 ? Instituicao::findOrFail($id) : new Instituicao();
        return view('instituicao.edit', [
            'instituicao' => $instituicao,
            'id' => $id,
            'ad' => 'ad'
        ]);
    }

    public function store(Request $request)
    {
        $instituicao = new Instituicao();
        $validator = $this->validator($request, $instituicao->id);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->input());
        }

        $instituicao = $this->setValue($instituicao, $request);
        $instituicao->save();
        return redirect()->route('instituicao.index');
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validator($request, $id);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->input());
        }


        $instituicao = Instituicao::findOrFail($id);
        $instituicao = $this->setValue($instituicao, $request);
        $instituicao->save();
        return redirect()->route('instituicao.index');
    }

    public function destroy($id)
    {
        $instituicao = Instituicao::findOrFail($id);

        $instituicao->delete();

        return redirect()->route('instituicao.index');
    }

    public function validator($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|max:100',
            'uf' => 'required',
            'cidade' => 'required',
            'endereco' => 'required|max:255',
            'email' => 'required|max:100',
            'cnpj' => 'required|formato_cnpj|cnpj|unique:instituicoes,cnpj,' . $id,
            'telefone' => 'required|telefone_com_ddd'
        ]);

        return $validator;
    }

    public function setValue($instituicao, $request)
    {
        $instituicao->nome = $request->nome;
        $instituicao->email = $request->email;
        $instituicao->telefone = $request->telefone;
        $instituicao->cnpj = $request->cnpj;
        $instituicao->estado = $request->uf;
        $instituicao->cidade = $request->cidade;
        $instituicao->endereco = $request->endereco;
        return $instituicao;
    }
}

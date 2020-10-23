<?php


namespace App\Http\Controllers;


use App\Models\Item_Mensagem;
use App\Models\Mensagem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;

class InboxController extends controller
{
    public function index($filtro = null)
    {
        if ($filtro == null) {
            $itens = Item_Mensagem::where('user_id', Auth::user()->id)->where('arquivado', null)->orderBy('created_at', 'desc')->get();
            return view('inbox.index', [
                'itens' => $itens
            ]);
        } elseif ($filtro == 'favoritas') {
            $itens = Item_Mensagem::where('user_id', Auth::user()->id)->where('favorito', 1)->orderBy('created_at', 'desc')->withTrashed()->get();
            return $itens;
        } elseif ($filtro == 'arquivadas') {
            $itens = Item_Mensagem::where('user_id', Auth::user()->id)->where('arquivado', 1)->orderBy('created_at', 'desc')->withTrashed()->get();
            return $itens;
        } elseif ($filtro == 'excluidas') {
            $itens = Item_Mensagem::where('user_id', Auth::user()->id)->where('deleted_at', '!=', null)->orderBy('created_at', 'desc')->withTrashed()->get();
            return $itens;
        } elseif ($filtro == 'enviadas') {
            $itens = Item_Mensagem::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
            return $itens;
        }
    }

    public function alunos(){
        if (!isset($_GET['al'])){
            $alunos = Auth::user()->alunos;
            return view('inbox.alunos', [
                'alunos' => $alunos
            ]);
        }else{
            if (Hashids::decode($_GET['al']) == null){
                return redirect()->back();
            }
            $itens = Item_Mensagem::where('user_id', Hashids::decode($_GET['al']))->orderBy('created_at', 'desc')->withTrashed()->get();
            return view('inbox.index', [
                'itens' => $itens
            ]);
        }
    }

    public function direcao(){
        if (!isset($_GET['us']) && !isset($_GET['msg'])){
            $user_id = User::where('escola_id', Auth::user()->escola_id)->get()->pluck('id');
            $mensagens = Mensagem::whereIn('user_id', $user_id)->get();
            return view('inbox.direcao', [
                'ad' => 'ad',
                'mensagens' => $mensagens
            ]);
        }else{
            if (Hashids::decode($_GET['us']) == null || Hashids::decode($_GET['msg']) == null){
                return redirect()->back();
            }
            $item = Item_Mensagem::where('mensagem_id', Hashids::decode($_GET['msg']))->where('user_id', Hashids::decode($_GET['us']))->first();
            return $this->show($item->id);
        }
    }

    public function enviadas()
    {
        $itens = $this->index('enviadas');
        return view('inbox.index', [
            'itens' => $itens
        ]);
    }

    public function favoritas()
    {
        $itens = $this->index('favoritas');
        return view('inbox.index', [
            'itens' => $itens
        ]);
    }

    public function excluidas()
    {
        $itens = $this->index('excluidas');
        return view('inbox.index', [
            'itens' => $itens
        ]);
    }

    public function arquivadas()
    {
        $itens = $this->index('arquivadas');
        return view('inbox.index', [
            'itens' => $itens
        ]);
    }

    public function edit($id)
    {
        if ($id != '0') {
            return redirect()->back();
        }
        $role = Auth::user()->role;
        $id = Auth::user()->id;
        $escola_id = Auth::user()->escola_id;
        if ($role == 100) {
            $users = User::all()->where('id', '!=', $id);
        } elseif ($role == 200) {
            $users = User::where('escola_id', $escola_id)->where('id', '!=', $id)->get();
        } elseif ($role == 300) {
            $users = User::where('escola_id', $escola_id)->where('id', '!=', $id)->where('role', '<', '600')->get();
        } elseif ($role == 400) {
            $users = User::where('escola_id', $escola_id)->where('id', '!=', $id)->where('role', '<', '500')->get();
            foreach (Auth::user()->aulaTurma as $aulaTurma) {
                foreach ($aulaTurma->turma->alunos as $aluno) {
                    $users[] = $aluno;
                    foreach ($aluno->responsaveis as $responsavel) {
                        $users[] = $responsavel;
                    }
                }
            }
        } elseif ($role == 500) {
            $users = User::where('escola_id', $escola_id)->where('id', '!=', $id)->where('role', '<', '400')->get();
            foreach (Auth::user()->aluno_turmas as $turma) {
                foreach ($turma->aulas as $aula) {
                    $users[] = $aula->user;
                }
            }
        } elseif ($role == 600) {
            $users = User::where('escola_id', $escola_id)->where('id', '!=', $id)->where('role', '<', '400')->get();
            foreach (Auth::user()->alunos as $aluno) {
                foreach ($aluno->aluno_turmas as $turma) {
                    foreach ($turma->aulas as $aula) {
                        $users[] = $aula->user;
                    }
                }
            }
        }
        $users = $users->unique();
        $users = $users->sortBy('name')->sortBy('role')->groupBy('role');
        return view('inbox.edit', [
            'users' => $users
        ]);
    }

    public function show($id)
    {
        $item = Item_Mensagem::withTrashed()->findOrFail($id);
        if (Auth::user()->role == 600 && Auth::user()->alunos->pluck('id')->contains($item->user_id)){

        }elseif(Auth::user()->role == 200){

        }elseif($item->user_id != Auth::user()->id) {
            return redirect()->route('logout');
        }
        if ($item->user_id == Auth::user()->id && $item->viewed_at == null) {
            $item->viewed_at = Carbon::now();
            $item->save();
        }
        return view('inbox.show', [
            'item' => $item
        ]);
    }

    public function store(Request $request)
    {
        $validator = $this->validator($request);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->input());
        }

        $mensagem = new Mensagem();
        $mensagem->user_id = Auth::user()->id;
        $mensagem->assunto = $request->assunto;
        $mensagem->mensagem = $request->mensagem;
        $mensagem->save();
        $users = $request->users;
        $users[] = Auth::user()->id;
        $mensagem->itens()->sync($users);
        return redirect()->route('inbox.index');
    }

    public function validator($request)
    {
        $validator = Validator::make($request->all(), [
            'users' => 'required',
            'assunto' => 'required',
            'mensagem' => 'required',
        ]);

        return $validator;
    }

    public function arquivar($id)
    {
        $item = Item_Mensagem::withTrashed()->findOrFail($id);
        if ($item->arquivado == null) {
            $item->arquivado = 1;
            $item->deleted_at = null;
            $item->save();
            return redirect()->route('inbox.arquivadas');
        } else {
            $item->arquivado = null;
            $item->save();
            return redirect()->route('inbox.index');
        }
    }

    public function favoritar($id)
    {
        $item = Item_Mensagem::withTrashed()->findOrFail($id);
        if ($item->favorito == null) {
            $item->favorito = 1;
        } else {
            $item->favorito = null;
        }
        $item->save();
        return redirect()->back();
    }

    public function excluir($id)
    {
        $item = Item_Mensagem::withTrashed()->findOrFail($id);
        if ($item->deleted_at == null && $item->mensagem->user->id == Auth::user()->id){
            $item->delete();
            return redirect()->route('inbox.enviadas');
        } elseif ($item->deleted_at == null) {
            $item->arquivado = null;
            $item->save();
            $item->delete();
            return redirect()->route('inbox.excluidas');
        } elseif ($item->deleted_at != null) {
            $item->deleted_at = null;
            $item->save();
            return redirect()->route('inbox.index');
        }
    }
}

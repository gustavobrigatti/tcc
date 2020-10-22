<?php


namespace App\Http\Controllers;


use App\Models\Instituicao;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 100) {
            $users = User::all();
        } elseif (Auth::user()->role == 200 || Auth::user()->role == 300) {
            $users = User::where('escola_id', Auth::user()->escola_id)->get();
        }
        return view('user.index', [
            'users' => $users,
            'ad' => 'ad',
        ]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('user.perfil', [
            'user' => $user,
            'id' => $id,
        ]);
    }

    public function edit($id)
    {
        $user = $id > 0 ? User::findOrFail($id) : new User();
        $instituicoes = Instituicao::all();
        if (Auth::user()->role == 100) {
            $responsaveis = User::where('role', 600)->orderBy('name')->get();
            $alunos = User::where('role', 500)->orderBy('name')->get();
        } elseif (Auth::user()->role == 200 || Auth::user()->role == 300) {
            $responsaveis = User::where('role', 600)->where('escola_id', Auth::user()->escola_id)->orderBy('name')->get();
            $alunos = User::where('role', 500)->where('escola_id', Auth::user()->escola_id)->orderBy('name')->get();
        }
        return view('user.edit', [
            'user' => $user,
            'instituicoes' => $instituicoes,
            'responsaveis' => $responsaveis,
            'alunos' => $alunos,
            'id' => $id,
            'ad' => 'ad'
        ]);
    }

    public function store(Request $request)
    {
        $user = new User();
        $validator = $this->validator($request, $user->id);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->input());
        }

        $user = $this->setValue($user, $request);
        $user->password = Hash::make(substr(Str::slug($user->name),0, 3).substr($user->cpf, 0,3));
        $user->save();
        if ($user->role == 500) {
            $user->responsaveis()->sync($request->responsaveis);
        } elseif ($user->role == 600) {
            $user->alunos()->sync($request->alunos);
        }
        return redirect()->route('user.index');
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

        $user = User::findOrFail($id);
        $user = $this->setValue($user, $request);
        if ($user->role == 500) {
            $user->responsaveis()->sync($request->responsaveis);
        } elseif ($user->role == 600) {
            $user->alunos()->sync($request->alunos);
        }
        $user->save();
        return redirect()->route('user.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('user.index');
    }

    public function perfil(Request $request, $id)
    {
        if ($request->file('file')) {
            $this->validate($request, [
                'file' => 'bail|image|mimes:jpeg,png,jpg|dimensions:max_width=2048,max_height=2048,ratio=1/1'
            ]);

            $nome = Auth::user()->id;
            $nameFile = "{$nome}.png";

            $upload = $request->file('file')->storeAs('public/users', $nameFile);

            if (!$upload) {
                return redirect()
                    ->back()
                    ->withErrors('error', 'Falha ao fazer o upload da imagem.');
            }
        } else {
            $validator = Validator::make($request->all(), [
                'nome' => 'required|max:100',
                'email' => 'required|max:100|unique:users,email,' . $id,
                'telefone' => 'required|celular_com_ddd',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput($request->input());
            }

            $user = User::findOrFail($id);
            $user->name = $request->nome;
            $user->email = $request->email;
            $user->telefone = $request->telefone;
            $user->save();
            return redirect()->route('user.show', $user->hash_id);
        }
    }

    public function senha(Request $request, $id)
    {
        $newPassword = $request->newPassword;
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required',
            'newPassword' => 'required',
            'newPasswordConfirm' => 'required|in:' . $newPassword,
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->input());
        }
        $user = User::findOrFail($id);

        if (!Hash::check($request->oldPassword, $user->password)) {
            return redirect()
                ->back()
                ->withInput($request->input());
        }

        $user->password = Hash::make($newPassword);
        $user->save();
        return redirect()->route('user.show', $user->hash_id);
    }

    public function resetarSenha($id){
        $user = User::findOrFail($id);
        $user->password = Hash::make(substr(Str::slug($user->name),0, 3).substr($user->cpf, 0,3));
        $user->save();
        return redirect()->route('user.index');
    }
    public function validator($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|max:100',
            'uf' => 'required',
            'cidade' => 'required',
            'endereco' => 'required|max:255',
            'data_nascimento' => 'required|data',
            'role' => 'required',
            'email' => 'required|max:100|unique:users,email,' . $id,
            'cpf' => 'required|formato_cpf|cpf|unique:users,cpf,' . $id,
            'telefone' => 'required|celular_com_ddd',
            'gender' => 'required'
        ]);

        return $validator;
    }

    public function setValue($user, $request)
    {
        $user->name = $request->nome;
        $user->email = $request->email;
        $user->telefone = $request->telefone;
        $user->cpf = $request->cpf;
        $user->data_nascimento = $request->data_nascimento;
        $user->role = $request->role;
        $user->genero = $request->gender;
        $user->estado = $request->uf;
        $user->cidade = $request->cidade;
        $user->endereco = $request->endereco;
        $user->escola_id = Auth::user()->escola_id;
        return $user;
    }
}

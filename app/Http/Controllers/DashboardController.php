<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{

    public function index()
    {
        return \redirect()->route('inbox.index');
    }

    public function auth(Request $request)
    {
        try {
            $user = User::where(['email' => $request->get('email')])->first();

            if (!$user) {
                throw new \Exception("O e-mail informado é inválido!");
            }
            if (!Hash::check($request->get('password'), $user->password)) {
                throw new \Exception("A senha informada é inválida!");
            }

            Auth::login($user);
            return redirect()->route('index');
        } catch (\Exception $e) {
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }
}

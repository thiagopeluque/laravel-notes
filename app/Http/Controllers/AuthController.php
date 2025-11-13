<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

class AuthController extends Controller
{
    public function Login()
    {
        return view('login');
    }

    public function LoginSubmit(Request $request)
    {
        // Form Validation
        $request->validate(
            [
                'text_username' => 'required|email',
                'text_password' => 'required|min:6|max:16'
            ],
            [
                'text_username.required' => 'O username é obrigatório.',
                'text_username.email' => 'O username deve ser um email válido.',
                'text_password.required' => 'A password é obrigatória.',
                'text_password.min' => 'A password deve ter no mínimo :min caracteres.',
                'text_password.max' => 'A password deve ter no máximo :max caracteres.'
            ]
        );

        // GET User Inputs
        $username = $request->input('text_username');
        $password = $request->input('text_password');
        
        // Check if the user exists
        $user = User::where('username', $username)->where('deleted_at', NULL)->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('loginError','Username ou Password incorretos');
        }

        if (!password_verify($password, $user->password)) {
            return redirect()->back()->withInput()->with('loginError','Username ou Password incorretos');
        }

        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        // Session Logged User
        session([
            'user' => [
                'id' => $user->id,
                'username' => $user->username
            ]
        ]);

        return redirect()->to('/');
    }

    public function Logout()
    {
        session()->forget('user');
        return redirect()->to('/login');
    }
}

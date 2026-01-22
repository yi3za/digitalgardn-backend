<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        // Donnees validees
        $data = $request->validated();
        // Conversion de l'email en minuscules
        $data['email'] = strtolower($data['email']);
        // Tentative d'authentification
        if (!Auth::attempt($data)) {
            // Echec d'authentification
            return response()->json([], 401);
        }
        // Authentification reussie
        return response()->json([
            'user' => Auth::user(),
        ], 200);
    }
}

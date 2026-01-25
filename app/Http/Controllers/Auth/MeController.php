<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * Retourne les informations de l'utilisateur connecte
     */
    public function me(Request $request)
    {
        return response()->json(
            [
                'user' => $request->user(),
            ],
            200,
        );
    }
}

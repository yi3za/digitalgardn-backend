<?php

namespace App\Http\Controllers\Profil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    /**
     * Retourne les informations du profil de l'utilisateur connecte
     */
    public function show(Request $request)
    {
        return response()->json(
            [
                'profil' => $request->user()->profil,
            ],
            200,
        );
    }
    /**
     * Modifier les informations du profil de l'utilisateur connecte
     */
    public function update()
    {
        //
    }
}

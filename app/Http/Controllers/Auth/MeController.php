<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{
    public function me(){
        // Retourne les informations de l'utilisateur qui connecte
        return Auth::user();
    }
}

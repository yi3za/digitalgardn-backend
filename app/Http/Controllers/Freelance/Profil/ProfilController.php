<?php

namespace App\Http\Controllers\Freelance\Profil;

use App\Http\Controllers\Controller;
use App\Http\Requests\Freelance\Profil\UpdateRequest;
use Illuminate\Http\Request;

/**
 * Gestion des informations du profil de l'utilisateur
 */
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
     * Modifie les informations du profil de l'utilisateur connecte
     */
    public function update(UpdateRequest $request)
    {
        // Recupere l'utilisateur qui est connecte
        $user = $request->user();
        // Recupere les donnees validees
        $data = $request->validated();
        // Verifie si une image de couverture a ete envoyee
        if ($request->hasFile('image_couverture')) {
            // Stocke l'image de couverture envoyee dans le dossier 'images_couvertures' du disque 'public'
            $data['image_couverture'] = $request->file('image_couverture')->store('images_couvertures', 'public');
        }
        // Modifie les informations envoyees
        $user->profil()->update($data);
        // Retourne statut 200 avec le profil mis a jour
        return response()->json(['profil' => $user->profil], 200);
    }
}

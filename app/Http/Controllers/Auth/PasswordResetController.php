<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Password\ResetRequest;
use App\Http\Requests\Auth\Password\SendRequest;
use App\Mail\ResetPasswordCodeMail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

use function Symfony\Component\Clock\now;

class PasswordResetController extends Controller
{
    public function sendCode(SendRequest $request)
    {
        // Valider l'email
        $email = $request->validated()['email'];
        // Chercher l'utilisateur par email
        $user = User::where('email', $email)->first();
        // Si l'utilisateur n'existe pas, retourner 404
        if (!$user) {
            return response()->json([], 404);
        }
        // Sinon, creer un code de verification
        $code_verification = rand(111111, 999999);
        // Enregistrer ou mettre a jour le code dans la table password_reset_tokens
        PasswordReset::updateOrCreate(
            ['email' => $email] // Condition pour trouver l'enregistrement
            ,
            [
                'token' => $code_verification,
                'created_at' => now(),
            ]
        );
        // Envoyer le mail avec le code de reinitialisation
        Mail::to($email)->send(new ResetPasswordCodeMail($code_verification));
        // Retourner une reponse de succes
        return response()->json([], 200);
    }
    public function resetPassword(ResetRequest $request)
    {
        // Recuperation des donnees
        $email = $request->input('email');
        $code = $request->input('code');
        $password = $request->input('password');
        // Verification de l'existance
        $exists = PasswordReset::where(['email' => $email, 'token' => $code])->first();
        // Verification de validite de code
        if (!$exists || $exists->created_at->addMinutes(10)->isPast()) {
            // Echec : Retourner un code HTTP 422 donnees non valides
            return response()->json([], 422);
        }
        // Succes : mise a jour de mot de passe de l'utilisateur
        $user = User::where('email', $email)->first();
        $user->update([
            'password' => $password
        ]);
        // Supprimer le code pour de raisons de securite
        $exists->delete();
        // Retourner succes avec code HTTP 200
        return response()->json([], 200);
    }
}

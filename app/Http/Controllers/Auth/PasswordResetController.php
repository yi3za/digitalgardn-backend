<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Password\ResetRequest;
use App\Http\Requests\Auth\Password\SendRequest;
use App\Mail\ResetPasswordCodeMail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

use function Symfony\Component\Clock\now;

/**
 * Gestion de la recuperation et de la reinitialisation du mot de passe
 */
class PasswordResetController extends Controller
{
    /**
     * Envoie un code de reinitialisation du mot de passe a l'utilisateur
     */
    public function sendCode(SendRequest $request)
    {
        // Recuperation de l'email de l'utilisateur
        $email = $request->validated()['email'];
        // Creer un code de verification
        $code_verification = (string) rand(111111, 999999);
        // Enregistrer ou mettre a jour le code dans la table password_reset_tokens
        PasswordReset::updateOrCreate(
            ['email' => $email], // Condition pour trouver l'enregistrement
            [
                'token' => $code_verification,
                'created_at' => now(),
            ],
        );
        // Envoyer le mail avec le code de reinitialisation
        Mail::to($email)->send(new ResetPasswordCodeMail($code_verification));
        // Retourne une reponse du succes
        return ApiResponse::send(ApiCodes::SUCCESS, 200);
    }
    /**
     * Reinitialise le mot de passe de l'utilisateur en utilisant le code de verification
     */
    public function resetPassword(ResetRequest $request)
    {
        // Recuperation des donnees
        $email = $request->input('email');
        $code = $request->input('code');
        $password = $request->input('password');
        // Succes : mise a jour de mot de passe de l'utilisateur
        $user = User::where('email', $email)->first();
        $user->update([
            'password' => $password,
        ]);
        // Supprimer le code pour de raisons de securite
        PasswordReset::where(['email' => $email, 'token' => $code])->delete();
        // Retourne une reponse du succes
        return ApiResponse::send(ApiCodes::SUCCESS, 200);
    }
}

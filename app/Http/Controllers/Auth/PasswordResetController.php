<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
}

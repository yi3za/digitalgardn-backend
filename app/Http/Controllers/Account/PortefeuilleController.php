<?php

namespace App\Http\Controllers\Account;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\RechargePortefeuilleRequest;
use App\Http\Resources\PortefeuilleResource;
use App\Http\Resources\TransactionResource;
use App\Models\Portefeuille;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Gestion du portefeuille de l'utilisateur connecte
 */
class PortefeuilleController extends Controller
{
    /**
     * Retourne le portefeuille de l'utilisateur avec son historique de transactions
     */
    public function show(Request $request)
    {
        // Recupere l'utilisateur connecte
        $user = $request->user();
        // Recupere ou cree le portefeuille de l'utilisateur
        $portefeuille = Portefeuille::firstOrCreate(['user_id' => $user->id]);
        // Retourne le portefeuille; les transactions sont preparees dans la resource
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['portefeuille' => new PortefeuilleResource($portefeuille)]);
    }
    /**
     * Retourne l'historique des transactions du portefeuille de l'utilisateur
     */
    public function transactions(Request $request)
    {
        // Recupere l'utilisateur connecte
        $user = $request->user();
        // Recupere ou cree le portefeuille de l'utilisateur
        $portefeuille = Portefeuille::firstOrCreate(['user_id' => $user->id]);
        // Recupere les transactions du plus recent au plus ancien
        $transactions = $portefeuille->transactions()->orderBy('created_at', 'desc')->get();
        // Retourne les transactions
        return ApiResponse::send(ApiCodes::SUCCESS, 200, [
            'transactions' => TransactionResource::collection($transactions),
        ]);
    }
    /**
     * Recharge le portefeuille de l'utilisateur d'un montant donne (simulation)
     */
    public function recharge(RechargePortefeuilleRequest $request)
    {
        // Recupere l'utilisateur connecte
        $user = $request->user();
        // Recupere le montant valide
        $montant = $request->validated('montant');
        // Utilise une transaction DB pour garantir l'integrite des donnees
        $portefeuille = DB::transaction(function () use ($user, $montant) {
            // Recupere ou cree le portefeuille
            $portefeuille = Portefeuille::firstOrCreate(['user_id' => $user->id]);
            // Incremente le solde disponible
            $portefeuille->increment('solde_disponible', $montant);
            // Cree la transaction de recharge
            $portefeuille->transactions()->create([
                'type' => 'recharge',
                'montant' => $montant,
            ]);
            return $portefeuille;
        });
        // Retourne le portefeuille les transactions sont preparees dans la resource
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['portefeuille' => new PortefeuilleResource($portefeuille)]);
    }
}

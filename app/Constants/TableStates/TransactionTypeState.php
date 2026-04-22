<?php

namespace App\Constants\TableStates;

/**
 * Types possibles d'une transaction de portefeuille
 */
class TransactionTypeState
{
    public const RECHARGE = 'recharge';
    public const GAIN = 'gain';
    public const ACHAT = 'achat';
    public const REMBOURSEMENT = 'remboursement';
}

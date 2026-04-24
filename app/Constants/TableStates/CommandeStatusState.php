<?php

namespace App\Constants\TableStates;

/**
 * Etats possibles du statut commande
 */
class CommandeStatusState
{
    public const EN_ATTENTE = 'en_attente';
    public const EN_COURS = 'en_cours';
    public const LIVREE = 'livree';
    public const EN_REVISION = 'en_revision';
    public const TERMINEE = 'terminee';
    public const ANNULEE = 'annulee';

    /**
     * Transitions autorisees pour le freelance
     */
    public static function freelanceTransitions(): array
    {
        return [
            self::EN_ATTENTE => [self::EN_COURS],
            self::EN_COURS => [self::LIVREE],
            self::EN_REVISION => [self::LIVREE],
        ];
    }
    /**
     * Transitions autorisees pour le client
     */
    public static function clientTransitions(): array
    {
        return [
            self::EN_ATTENTE => [self::ANNULEE],
            self::EN_COURS => [self::ANNULEE],
            self::LIVREE => [self::TERMINEE, self::EN_REVISION],
            self::EN_REVISION => [self::TERMINEE],
        ];
    }
    /**
     * Etats cibles autorises pour le freelance
     */
    public static function freelanceTargetStatuses(): array
    {
        return [
            self::EN_COURS,
            self::LIVREE,
        ];
    }
    /**
     * Etats cibles autorises pour le client
     */
    public static function clientTargetStatuses(): array
    {
        return [
            self::TERMINEE,
            self::EN_REVISION,
            self::ANNULEE,
        ];
    }
}

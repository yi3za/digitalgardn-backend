<?php

namespace App\Constants\TableStates;

/**
 * Etats possibles du statut service
 */
class ServiceStatusState
{
    public const BROUILLON = 'brouillon';
    public const EN_ATTENTE_APPROBATION = 'en_attente_approbation';
    public const PUBLIE = 'publie';
    public const EN_PAUSE = 'en_pause';
    public const REJETE = 'rejete';

    /**
     * Transitions autorisees pour les actions freelance
     */
    public static function freelanceTransitions(): array
    {
        return [
            self::BROUILLON => [self::EN_ATTENTE_APPROBATION],
            self::REJETE => [self::EN_ATTENTE_APPROBATION],
            self::EN_PAUSE => [self::EN_ATTENTE_APPROBATION],
            self::PUBLIE => [self::EN_PAUSE],
            self::EN_ATTENTE_APPROBATION => [self::EN_PAUSE],
        ];
    }
    /**
     * Etats cibles autorises dans la requete de changement de statut
     */
    public static function freelanceTargetStatuses(): array
    {
        return [
            self::EN_PAUSE,
            self::EN_ATTENTE_APPROBATION,
        ];
    }
}

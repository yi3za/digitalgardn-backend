<?php

namespace App\Traits;

/**
 * Trait pour gerer les relations hierarchiques et recuperer les services
 */
trait HasHierarchicalChildren
{
    /**
     * Private
     * Function helper : recupere les services avec leurs details (user, fichierPrincipale)
     */
    private function fetchServicesForEnfant($enfant, $whereServices, $whereUser)
    {
        // Recuperer les services
        $queryServices = $enfant->services();
        // Appliquer des conditions sur les services si fournies
        if ($whereServices) {
            $queryServices->where($whereServices);
        }
        // Appliquer des conditions sur les utilisateurs si fournies
        if ($whereUser) {
            $queryServices->whereHas('user', fn($q) => $q->where($whereUser));
        }
        // Charger les relations user et fichierPrincipale
        $queryServices->with('user', 'fichierPrincipale');
        // Retourner les services
        return $queryServices->get();
    }
    /**
     * Relation : recupere tous les services des enfants
     */
    public function servicesAvecDetails($whereEnfants = [], $whereServices = [], $whereUser = [])
    {
        // Si enfant, retourner ses services directement
        if ($this->parent_id !== null) {
            return $this->fetchServicesForEnfant($this, $whereServices, $whereUser);
        }
        // Sinon parent, recuperer les services de tous les enfants
        // Recuperer la relation enfants
        $queryEnfants = $this->enfants();
        // Appliquer des conditions sur les enfants si fournies
        if ($whereEnfants) {
            $queryEnfants->where($whereEnfants);
        }
        // Executer la requete pour obtenir les enfants
        $enfants = $queryEnfants->get();
        // Parcourir chaque enfant pour recuperer ses services
        return $enfants->flatMap(fn($enfant) => $this->fetchServicesForEnfant($enfant, $whereServices, $whereUser));
    }
}

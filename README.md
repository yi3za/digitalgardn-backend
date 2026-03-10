# Digitalgardn API

Backend de l'application Digitalgardn, une plateforme de mise en relation entre des milliers de freelances et des clients du monde entier. Cette API est construite avec Laravel.

---

## Technologies utilisees

| Categorie               | Technologie / Librairie |
| ----------------------- | ----------------------- |
| **Framework & Langage** | Laravel, PHP            |
| **Base de donnees**     | MySQL                   |
| **Authentification**    | Laravel Sanctum         |

---

## Fonctionnalites Implementees

- ✅ **API Standardisee et Robuste**
    - Reponses API coherentes et gestionnaire d'exceptions global.
    - Utilisation de codes d'API personnalises pour la communication avec le frontend.

- ✅ **Systeme d'Authentification Complet et Securise**
    - Inscription, connexion, deconnexion et reinitialisation de mot de passe.
    - Protection des routes via des middlewares (auth, guest, role).

- ✅ **Validation Avancee et Gestion des Roles**
    - Validation robuste des donnees via des Form Requests dedies.
    - Middleware de gestion des roles flexible pour proteger les ressources.

- ✅ **Gestion des Comptes et Profils**
    - CRUD complet pour les comptes utilisateurs et les profils freelances.
    - Synchronisation des competences pour les profils.

- ✅ **Gestion des Services et Catalogue Public**
    - CRUD complet pour les services geres par les freelances.
    - API publique pour l'affichage des categories, services et competences.

- ✅ **Qualite du Code et Organisation**
    - Utilisation de Traits PHP pour un code propre et reutilisable.
    - Structure de projet claire et code source commente.

---

## Installation et Lancement

Suivez ces etapes pour configurer et lancer le projet sur votre machine locale.

**Cloner le projet**

```bash
git clone https://github.com/yi3za/digitalgardn-backend.git
cd digitalgardn-backend
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

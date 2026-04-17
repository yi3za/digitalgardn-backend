# Digitalgardn API

Backend de l'application Digitalgardn, une plateforme de mise en relation entre des milliers de freelances et des clients du monde entier. Cette API est construite avec Laravel.

---

## Technologies utilisees

| Categorie                | Technologie / Librairie |
| ------------------------ | ----------------------- |
| **Framework & Langage**  | Laravel, PHP            |
| **Base de donnees**      | MySQL                   |
| **Authentification**     | Laravel Sanctum         |
| **WebSocket / Realtime** | Laravel Reverb          |

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

- ✅ **Systeme de Messagerie en Temps Reel**
    - Conversations bidirectionnelles entre utilisateurs.
    - Envoi et reception de messages avec synchronisation en temps reel via WebSocket (Reverb).
    - Broadcasting prive sur les canaux `conversations.{id}`.
    - Resources dedies pour la normalisation des donnees de messagerie.
    - Validation robuste des messages et creation de conversations.

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

**Demarrer le serveur WebSocket (Reverb)**

Pour les fonctionnalites en temps reel de l'application (messagerie, notifications et autres evenements broadcast), demarrez le serveur Reverb dans un terminal distinct :

```bash
php artisan reverb:start
```

Le serveur Reverb fonctionnera sur `127.0.0.1:8080` par defaut.

Reverb est la couche WebSocket temps reel globale du projet, et ne se limite pas uniquement a la messagerie.

---

## Endpoints API

### Authentification

- `POST /api/auth/register` - Inscription
- `POST /api/auth/login` - Connexion
- `POST /api/auth/logout` - Deconnexion
- `POST /api/auth/forgot-password` - Reinitialisation du mot de passe

### Gestion du Compte

- `GET /api/me/account` - Recuperer les informations du compte
- `PATCH /api/me/account/info` - Mettre a jour les informations
- `PATCH /api/me/account/avatar` - Telecharger un avatar
- `PATCH /api/me/account/password` - Changer le mot de passe
- `PATCH /api/me/account/activate` - Activer le compte freelance
- `PATCH /api/me/account/deactivate` - Desactiver le compte freelance
- `DELETE /api/me/account` - Supprimer le compte

### Profil Freelance

- `GET /api/me/profil` - Consulter le profil
- `PATCH /api/me/profil` - Mettre a jour le profil
- `POST /api/me/profil/competences` - Synchroniser les competences

### Services (Freelance)

- `GET /api/me/services` - Lister les services
- `POST /api/me/services` - Creer un service
- `GET /api/me/services/{slug}` - Consulter un service
- `PATCH /api/me/services/{slug}` - Modifier un service
- `DELETE /api/me/services/{slug}` - Supprimer un service

### Catalogue Public

- `GET /api/categories` - Lister les categories
- `GET /api/services` - Lister tous les services publies
- `GET /api/services/{slug}` - Consulter un service public
- `GET /api/competences` - Lister les competences

### Messagerie

- `GET /api/me/conversations` - Lister les conversations de l'utilisateur
- `POST /api/me/conversations` - Creer une nouvelle conversation
- `GET /api/me/conversations/{conversation}/messages` - Recuperer les messages d'une conversation
- `POST /api/me/conversations/{conversation}/messages` - Envoyer un message

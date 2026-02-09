# Digitalgardn API

Backend de l'application Digitalgardn, une plateforme de mise en relation entre des milliers de freelances et des clients du monde entier. Cette API est construite avec Laravel.

---

## Technologies utilisees

- **PHP 8.2+**
- **Laravel 12**
- **MySQL**
- **Laravel Sanctum (API authentication via cookies)**

---

## Fonctionnalites implementees

- ✅ **Systeme d'authentification complet**
    - Inscription, Connexion et Deconnexion securisees.
    - Processus complet de reinitialisation de mot de passe par email.
    - Protection des routes d'inscription/connexion pour les utilisateurs non connectes (middleware `guest`).

- ✅ **Gestion du compte utilisateur**
    - Recuperation des informations de l'utilisateur connecte (`/api/me`).
    - Mise a jour des informations du compte (email, ...).
    - Changement de mot de passe securise.
    - Mise a jour du statut du compte.
    - Suppression du compte utilisateur.
    - Suivi automatique de la derniere activite de l'utilisateur.

- ✅ **Gestion du profil (pour les freelances)**
    - Creation automatique d'un profil vide a l'inscription.
    - Recuperation et mise a jour complete du profil (titre, ...).
    - Protection des routes du profil pour qu'elles soient accessibles uniquement aux freelances.

- ✅ **Securite et autorisations**
    - Authentification via Laravel Sanctum (API stateful).
    - Middleware de gestion des roles (`role:freelance`, ...) pour un controle d'acces precis.
    - Validation robuste de toutes les donnees entrantes via des Form Requests dedies.

- ✅ **Qualite du code et organisation**
    - Code source propre, commente et bien organise.
    - Regroupement logique des routes par controleur pour une meilleure lisibilite.
    - Normalisation des donnees d'entree (ex: email en minuscules) directement dans les Form Requests.

- ✅ **Catalogue public (API pour les visiteurs)**
    - Affichage de la liste de toutes les categories principales.
    - Affichage d'une categorie principale avec la liste de ses sous-categories.
    - Affichage de la liste de tous les services publies et actifs.
    - Affichage des details d'un service specifique.
    - Logique avancee pour lister tous les services d'une categorie (si categorie parente, agrege les services de toutes ses sous-categories).

- ✅ **Gestion des services (pour les freelances)**
    - CRUD complet pour la gestion des services par leur proprietaire (Creer, Lire, Mettre a jour, Supprimer).
    - Generation automatique d'un `slug` unique lors de la creation ou de la mise a jour d'un service.
    - Le statut par defaut des nouveaux services est automatiquement defini sur "brouillon".
    - Synchronisation des categories associees a un service.
    - Gestion complete de la galerie d'images d'un service (remplacement, definition de l'image principale, suppression automatique des fichiers physiques).

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
php artisan serve
```

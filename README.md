# Digitalgardn API

Backend de l'application Digitalgardn, une plateforme de mise en relation entre des milliers de freelances et des clients du monde entier. Cette API est construite avec Laravel.

---

## Technologies utilisees

- **PHP 8.2+**
- **Laravel 12**
- **MySQL**

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

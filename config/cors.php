<?php

#-----------------------------------------------------------------------------------------
# Configuration de CORS (Cross Origin Resource Sharing) pour l'authentification Stateful
#-----------------------------------------------------------------------------------------
#
# 1. 'allowed_origins': On autorise explicitement notre application frontend
#    a envoyer des requetes.
#
# 2. 'supports_credentials': On met cette valeur a 'true' pour permettre
#    l'echange de cookies entre le frontend et le backend. C'est essentiel
#    pour que l'authentification par session de Sanctum fonctionne.
#

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [env('FRONTEND_URL')],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];

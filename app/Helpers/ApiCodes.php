<?php

namespace App\Helpers;

/**
 * ApiCodes contient tous les codes logiques utilises pour les reponses API
 */
class ApiCodes
{
    const SUCCESS = 'SUCCESS';
    const VALIDATION_ERROR = 'VALIDATION_ERROR';
    const UNAUTHENTICATED = 'UNAUTHENTICATED';
    const INVALID_CREDENTIALS = 'INVALID_CREDENTIALS';
    const FORBIDDEN = 'FORBIDDEN';
    const NOT_FOUND = 'NOT_FOUND';
    const CSRF_TOKEN_MISMATCH = 'CSRF_TOKEN_MISMATCH';
    const SERVER_ERROR = 'SERVER_ERROR';
    const METHOD_NOT_ALLOWED = 'METHOD_NOT_ALLOWED';
    const TOO_MANY_REQUESTS = 'TOO_MANY_REQUESTS';
}

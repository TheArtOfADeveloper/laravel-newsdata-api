<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Le URI che devono essere escluse dalla verifica CSRF.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Aggiungi qui le rotte che non devono richiedere il token
        'api/country/*/category/*',
    ];
}

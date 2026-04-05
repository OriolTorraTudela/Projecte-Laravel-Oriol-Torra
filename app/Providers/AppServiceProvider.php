<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

/**
 * AppServiceProvider — proveïdor de serveis principal de l'aplicació.
 *
 * Configuracions globals:
 *  - Paginació amb Tailwind CSS (en lloc dels components Bootstrap per defecte)
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra serveis de l'aplicació.
     */
    public function register(): void
    {
        //
    }

    /**
     * Arranca els serveis de l'aplicació.
     *
     * Aquí configurem que la paginació de Laravel
     * utilitzi les vistes de Tailwind CSS, que és el framework
     * CSS que ve integrat amb Laravel 12 + Breeze.
     */
    public function boot(): void
    {
        // Utilitza les vistes de paginació de Tailwind CSS
        // Alternativa: Paginator::useBootstrapFive() si s'usés Bootstrap
        Paginator::useTailwind();
    }
}

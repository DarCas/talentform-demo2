<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware per la verifica dell'autenticazione dell'utente
 */
class Authenticator
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    function handle(Request $request, Closure $next): Response
    {
        if (Facades\Cookie::has('logged')) {
            // Il valore del cookie è nella forma [id]:[hash]
            // Esplodo per ":" e assegno a $id e $hash le due parti
            [$id, $hash] = explode(':', Facades\Cookie::get('logged'));

            /**
             * @var \Illuminate\Database\Eloquent\Builder $builder
             */
            $builder = User::where('id', $id);
            /**
             * @var User|null $user
             */
            $user = $builder->first();

            // Se l'ID dell'utente esiste e l'hash del record è uguale a $hash
            // allora l'utente è autorizzato a continuare le operazioni.
            if (!is_null($user) && sha1(http_build_query($user->toArray())) === $hash) {
                return $next($request);
            }
        }

        Facades\Session::put('no-login', true);

        return redirect('/backend');
    }
}

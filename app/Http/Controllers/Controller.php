<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\View\AlertView;
use Illuminate\Http\Response;
use Illuminate\Support\Facades;

abstract class Controller
{
    protected string $year = '2025';

    function __construct()
    {
        $y = date('Y');

        if ($y !== '2025') {
            /**
             * Se l'anno è successivo al 2025, siccome questo script l'ho realizzato nel 2025,
             * allora imposto il copyright dal 2025 all'anno corrente, mettendoci un trattino (-) in mezzo.
             */
            $this->year = "{$this->year}&minus;{$y}";
        }
    }

    /**
     * Elaboro il template principale del backend
     *
     * @param string $content Il contenuto dell'area principale del template
     * @param string $title Il titolo della pagina
     * @param AlertView|null $alertTemplate Messaggio di alert nella pagina
     * @param string|null $refresh Imposto un refresh all'occorrenza
     */
    protected function renderContent(
        string     $content,
        string     $title = 'Guestbook Backend',
        ?AlertView $alertTemplate = null,
        ?string    $refresh = null
    ): Response
    {
        $view = response()
            ->view('back.default', [
                'alertTemplate' => $alertTemplate ?? null,
                'css' => [
                    "//cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css",
                    "//cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css",
                    '/css/style.css',
                ],
                'js' => [
                    "//cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js",
                ],
                'errors' => Facades\Session::get('errors'),
                'year' => $this->year,
                'title' => $title,
                'content' => $content,
                'user' => $this->imLogged() ? $this->imLogged()->toArray() : null,
            ]);

        /**
         * Cancello dalla SESSION gli "errors"
         */
        Facades\Session::forget('errors');

        if (!is_null($refresh)) {
            $view->withHeaders(['Refresh' => $refresh]);
        }

        return $view;
    }

    /**
     * Verifica se sono loggato o meno
     *
     * @return User|false
     */
    protected function imLogged(): User|false
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
            // allora l'utente è autorizzato ad accedere
            if (!is_null($user) && sha1(http_build_query($user->toArray())) === $hash) {
                return $user;
            }
        }

        return false;
    }
}

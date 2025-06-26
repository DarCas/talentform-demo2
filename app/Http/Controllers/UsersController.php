<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    /**
     * Controller per la pagina Utenti di backend
     *
     * @param Request $request
     * @return Response
     */
    function index(Request $request): Response
    {
        return $this->renderContent(
            content: $this->renderDatagrid($request),
            alertTemplate: $alertTemplate ?? null,
            refresh: isset($alertTemplate) ? "5; url=/backend/users" : null,
        );
    }

    /**
     * Elaboro il datagrid dei messaggi
     *
     * @param Request $request
     * @return string
     */
    private function renderDatagrid(Request $request): string
    {
        /**
         * Colonna per la quale ordinare i risultati della tabella
         */
        $column = $request->query('sort', 'id');

        /**
         * Ordinamento ascendente o discendente
         */
        $desc = $request->query('desc', false);

        /**
         * Elementi per pagina
         */
        $perPage = $request->query('perPage', 10);

        /**
         * Recupero tutti i dati dalla tabella del database "form" ordinati
         * per "id".
         *
         * @var \Illuminate\Database\Eloquent\Builder $builder
         */

        $builder = User::orderBy($column, $desc ? 'desc' : 'asc');

        if ($perPage == -1) {
            $items = $builder->get();
        } else {
            /**
             * Recupero i dati già impaginati da Laravel
             */
            $paginate = $builder->paginate($perPage);

            /**
             * Contiene la navigazione della paginazione fatta in Bootstrap 5.
             * Vedi: ~/app/Providers/AppServiceProvider.php@boot()
             */
            $pagination = $paginate->links()->toHtml();
        }

        $user = $this->imLogged();

        return view('back.users.datagrid', [
            'items' => $items ?? $paginate?->items() ?? [],
            'myId' => $user ? $user->id : null,
            'pagination' => $pagination ?? '',
        ])->render();
    }
}

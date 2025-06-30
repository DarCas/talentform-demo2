<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GuestbookController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    function index(Request $request): JsonResponse
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

        if ($column === 'fullname') {
            $builder = Form::orderBy('cognome', $desc ? 'desc' : 'asc');
            $builder->orderBy('nome', $desc ? 'desc' : 'asc');
        } else {
            $builder = Form::orderBy($column, $desc ? 'desc' : 'asc');
        }

        if ($perPage == -1) {
            $items = $builder->get();
        } else {
            /**
             * Recupero i dati già impaginati da Laravel
             */
            $paginate = $builder->paginate($perPage);
        }

        if (!isset($items)) {
            $items = collect($paginate?->items() ?? []);
        }

        return response()
            ->json([
                'count' => $paginate?->total() ?? $items->count(),
                'items' => $items->map(function ($item) {
                    $data = $item->toArray();
                    $data['data_ricezione'] = $item->getDataRicezioneIso8601();

                    return $data;
                }),
                'page' => (int)$request->get('page', 1),
            ]);
    }
}

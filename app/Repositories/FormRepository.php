<?php

namespace App\Repositories;

use App\Models\Form;

class FormRepository implements FormRepositoryInterface
{
    /**
     * @param int $perPage
     * @param string $column
     * @param bool $desc
     * @return array[Colletion, string]
     */
    function getDatagrid(int $perPage, string $column, bool $desc): array
    {
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
            return [
                $builder->get(),
                '',
            ];
        } else {
            /**
             * Recupero i dati già impaginati da Laravel
             */
            $paginate = $builder->paginate(
                perPage: $perPage, // Definisco quanti messaggi voglio visualizzare per pagina
            );

            return [
                $paginate->items(),
                $paginate->links()->toHtml(),
            ];
        }
    }

    function getOne(int $id): Form|null
    {
        return Form::where('id', $id)
            ->first();
    }

    function upsert(array $payload, ?int $id = null): bool
    {
        try {
            if (is_null($id)) {
                $form = new Form();
            } else {
                $form = $this->getOne($id);
            }

            if (!is_null($form)) {
                $form->nome = $payload['nome'];
                $form->cognome = $payload['cognome'];
                $form->email = $payload['email'];
                $form->messaggio = $payload['messaggio'];
                $form->save();

                return true;
            }
        } catch (\Throwable $th) {
            // Mando l'errore alla telemetria
        }

        return false;
    }
}

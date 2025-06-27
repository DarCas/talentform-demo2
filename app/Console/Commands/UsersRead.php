<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Database\Eloquent\Builder;

class UsersRead extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:read
                            {--id= : Inserisci ID o Username dell\'utente da visualizzare}
                            {--orderBy=id : Ordina la lista in base a una colonna}
                            {--desc : Ordina la lista in ordine decrescente}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando per visualizzare la lista degli utenti di backend';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->clear();

        $headers = ['#', 'Nome completo', 'Username', 'Data inserimento'];

        /**
         * @var Builder $builder
         */
        $builder = User::orderBy($this->option('orderBy'), $this->option('desc') ? 'desc' : 'asc');

        if ($this->option('id')) {
            $id = $this->option('id');

            if (is_numeric($id)) {
                $builder->where('id', $id);
            } else {
                $builder->where('usernm', $id);
            }
        }

        $users = $builder->get();

        $rows = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'usernm' => $user->usernm,
                'insert_date' => $user->formatInsertDate('d/m/Y H:i:s'),
            ];
        });

        $this->table($headers, $rows);

        return Command::SUCCESS;
    }
}

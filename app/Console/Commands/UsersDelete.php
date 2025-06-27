<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UsersDelete extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "users:delete {id : Inserisci ID o Username dell'utente da cancellare}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando per cancellare un utente di backend';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->clear();

        $id = $this->argument('id');

        if (is_numeric($id)) {
            $user = User::where('id', $id)->first();
        } else {
            $user = User::where('usernm', $id)->first();
        }

        if (!is_null($user)) {
            if ($this->confirm("Sei sicuro di voler cancellare l'utente {$user->fullname}?")) {
                $user->delete();

                $this->info("L'utente indicato è stato cancellato");
            } else {
                $this->warn("Operazione annullata");
            }

            $this->info('');

            return Command::SUCCESS;
        } else {
            $this->error('Non ho trovato nessun utente');
            $this->info('');

            return Command::FAILURE;
        }
    }
}

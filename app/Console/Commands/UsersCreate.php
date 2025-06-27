<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsersCreate extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando per aggiungere un utente di backend';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $fullname = $this->ask('Inserisci il nome completo (min 5 caratteri)');
        $usernm = $this->ask('Inserisci lo username (e-mail valida)');
        $passwd = $this->secret('Inserisci la password (min 6 caratteri)');
        $passwd_confirmation = $this->secret('Conferma la password inserita');

        $fields = [
            'fullname' => 'Nome completo',
            'usernm' => 'Username',
            'passwd' => 'Password',
        ];

        $validator = Validator::make([
            'fullname' => $fullname,
            'usernm' => $usernm,
            'passwd' => $passwd,
            'passwd_confirmation' => $passwd_confirmation,
        ], [
            'fullname' => 'required|string|min:5',
            'usernm' => [
                'required',
                'email:rfc',
                Rule::unique('users', 'usernm')
            ],
            'passwd' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);

        if ($validator->fails()) {
            $this->error("Errore nell'inserimento dei dati:");

            $errors = array_reduce($validator->errors()->keys(), function ($carry, $key) use ($validator) {
                $carry[$key] = $validator->errors()->get($key)[0];

                return $carry;
            }, []);

            foreach ($errors as $key => $error) {
                $this->error("- {$fields[$key]}: {$error}");
            }

            $this->info('');

            return Command::FAILURE;
        }

        try {
            $user = new User();
            $user->fullname = $fullname;
            $user->usernm = $usernm;
            $user->passwd = sha1($passwd);
            $user->save();

            $this->info("Utente «{$usernm}» aggiunto");

            return Command::SUCCESS;
        } catch (UniqueConstraintViolationException $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}

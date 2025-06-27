<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsersUpdate extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "users:update {id : Inserisci ID o Username dell'utente da modificare}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando per modificare un utente di backend';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->clear();

        $id = $this->argument('id');

        if (is_numeric($id)) {
            $user = User::where('id', $id)->first();
        } else {
            $user = User::where('usernm', $id)->first();
        }

        if (!is_null($user)) {
            $fullname = $this->ask('Inserisci il nome completo (min 5 caratteri)', $user->fullname);
            $usernm = $this->ask('Inserisci lo username (e-mail valida)', $user->usernm);

            $fields = [
                'fullname' => 'Nome completo',
                'usernm' => 'Username',
                'passwd' => 'Password',
            ];

            $listParams = [
                'fullname' => $fullname,
                'usernm' => $usernm,
            ];

            $listValidations = [
                'fullname' => 'required|string|min:5',
                'usernm' => [
                    'required',
                    'email:rfc',
                    Rule::unique('users', 'usernm')
                        ->where('id', '<>', $user->id),
                ],
            ];

            if ($this->confirm('Vuoi modificare la password?')) {
                $passwd = $this->secret('Inserisci la password (min 6 caratteri)');
                $passwd_confirmation = $this->secret('Conferma la password inserita');

                $listParams['passwd'] = $passwd;
                $listParams['passwd_confirmation'] = $passwd_confirmation;

                $listValidations['passwd'] = [
                    'required',
                    'string',
                    'min:6',
                    'confirmed',
                ];
            }

            $validator = Validator::make($listParams, $listValidations);

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
                $user->fullname = $fullname;
                $user->usernm = $usernm;

                if (isset($passwd)) {
                    $user->passwd = sha1($passwd);
                }

                $user->save();

                $this->info("Utente «{$usernm}» modificato");

                return Command::SUCCESS;
            } catch (UniqueConstraintViolationException $e) {
                $this->error($e->getMessage());

                return Command::FAILURE;
            }

        } else {
            $this->error('Non ho trovato nessun utente');
            $this->info('');

            return Command::FAILURE;
        }
    }
}

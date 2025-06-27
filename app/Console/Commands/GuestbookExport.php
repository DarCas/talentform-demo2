<?php

namespace App\Console\Commands;

use App\Models\Form;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Esporto la tabella "form" in CSV
 */
class GuestbookExport extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guestbook:export
                            {--orderBy=id : Ordina la lista in base a una colonna}
                            {--desc : Ordina la lista in ordine decrescente}
                            {--separator=, : Carattere utilizzato per separare i campi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando per esportare i dati del guestbook in CSV';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->clear();

        $disk = Storage::disk('local');

        if (!$disk->exists('guestbook')) {
            $disk->makeDirectory('guestbook');
        }

        $oDateTime = new \DateTime('now', new \DateTimeZone('Europe/Rome'));

        $filepath = storage_path("app/private/guestbook/{$oDateTime->format('YmdHi')}.csv");

        try {
            /**
             * @var Collection<Form> $guestbook
             */
            $guestbook = Form::orderBy($this->option('orderBy'), $this->option('desc') ? 'desc' : 'asc')
                ->get();

            $file = fopen($filepath, 'w');

            $header = array_keys($guestbook[0]->getAttributes());

            fputcsv($file, $header, $this->option('separator'));

            $guestbook->map(function ($form) use ($file) {
                fputcsv($file, [
                    $form->id,
                    $form->nome,
                    $form->cognome,
                    $form->email,
                    $form->messaggio,
                    $form->data_ricezione,
                ], $this->option('separator'));
            });

            fclose($file);

            $this->info("Tabella del guestbook esportata in «{$filepath}»");

            return Command::SUCCESS;
        } catch (\Throwable $th) {
            $this->error($th->getMessage());

            return Command::FAILURE;
        }
    }
}

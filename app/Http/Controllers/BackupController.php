<?php

namespace App\Http\Controllers;

use App\View\AlertView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BackupController extends Controller
{
    /**
     * Metodo per gestire la visualizzazione dei file di backup
     *
     * @return mixed
     */
    function index(): mixed
    {
        if (Session::has('deleted')) {
            $alertTemplate = new AlertView('File eliminato con successo!');

            Session::forget('deleted');
        }

        return $this->renderContent(
            content: $this->renderDatagrid(),
            alertTemplate: $alertTemplate ?? null,
            refresh: isset($alertTemplate) ? "5; url=/backend/backup" : null,
        );
    }

    /**
     * Cancello un singolo file
     *
     * @param string $filename
     * @return mixed
     */
    function delete(string $filename): mixed
    {
        /**
         * Mi collego al driver "guestbook" dichiarato nel file
         * ~/config/filesystems.php
         */
        $disk = Storage::disk('guestbook');

        /**
         * Verifico se il file esiste
         */
        if ($disk->exists($filename)) {
            /**
             * Cancello il file
             */
            $disk->delete($filename);

            Session::put('deleted', true);

            return redirect('/backend/backup');
        } else {
            return response()
                ->noContent(404);
        }
    }

    /**
     * Con questo metodo gestisco la cancellazione multipla dei file
     *
     * @param Request $request
     * @return mixed
     */
    function deleteMultiple(Request $request): mixed
    {
        $files = $request->post('files');

        if (count($files) > 0) {
            $disk = Storage::disk('guestbook');

            foreach ($files as $file) {
                if ($disk->exists($file)) {
                    $disk->delete($file);
                }
            }
        }

        Session::put('deleted', true);

        return redirect('/backend/backup');
    }

    /**
     * Con questo metodo forzo il download del file.
     *
     * @param string $filename
     * @return mixed
     */
    function dwl(string $filename): mixed
    {
        $disk = Storage::disk('guestbook');

        if ($disk->exists($filename)) {
            return response()
                ->download($disk->path($filename));

            /**
             * Di seguito quello che avviene in background su Laravel quando
             * eseguo la funzione "download" utilizzata sopra.
             *
             * header('Content-Type: application/octet-stream');
             * header('Content-Disposition: attachment; filename="' . $filename . '"');
             * header('Content-Length: ' . $disk->size($filename));
             * header('Expires: 0');
             * header('Cache-Control: must-revalidate');
             * header('Pragma: public');
             * return readfile($disk->path($filename));
             */
        } else {
            return response()
                ->noContent(404);
        }
    }

    /**
     * Elaboro il datagrid dei files
     *
     * @return string
     */
    private function renderDatagrid(): string
    {
        $disk = Storage::disk('guestbook');

        /**
         * Trasformo l'array dei files in una collection di Laravel
         */
        $items = collect($disk->files())
            // Elimino tutti i file che non terminano con .csv
            ->filter(fn($file) => Str::endsWith($file, '.csv'))
            // Modifico la collection con i dati già pronti alla visualizzazione
            ->map(function ($file) use ($disk) {
                $oDateTime = \DateTime::createFromFormat('U', $disk->lastModified($file));

                return [
                    'filename' => $file,
                    'filesize' => $this->filesizeVerbose($disk->size($file), 1),
                    'filetype' => $disk->mimeType($file),
                    'lastModified' => $oDateTime->format('d/m/Y H:i:s'),
                ];
            })
            // Ordino i dati per ultima modifica decrescente
            ->sortBy(fn($file) => $file['lastModified'], SORT_DESC, true)
            // Converso la collection in un array
            ->values();

        return view('back.backup.datagrid', [
            'items' => $items,
        ])->render();
    }

    /**
     * Funzione per stampare in maniera human-readable la dimensione del file.
     *
     * @param int $bytes Dimensione in bytes del file
     * @param int $decimals Approssimazione del valore finale
     * @param bool $iec true se voglio il sistema di valore binario
     * @return string
     */
    private function filesizeVerbose(int $bytes, int $decimals = 2, bool $iec = false): string
    {
        if ($iec) {
            /**
             * Dimensioni con sistema binario
             */
            $size = ['B', 'kiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
        } else {
            /**
             * Dimensioni con sistema decimale
             */
            $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        }

        /**
         * Setto il dividendo in funzione del paramentro $iec
         * che mi indica se voglio il valore con il sistema decimale
         * o il sistema binario
         */
        $divider = $iec ? 1024 : 1000;

        $factor = 0;

        while ($bytes >= $divider) {
            $bytes /= $divider;

            ++$factor;
        }

        return number_format($bytes, $decimals) .
            " {$size[$factor]}";
    }
}

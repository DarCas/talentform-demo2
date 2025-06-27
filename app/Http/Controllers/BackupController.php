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
        if (!$this->imLogged()) {
            return redirect('/backend/');
        }

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
        $disk = Storage::disk('guestbook');
        if ($disk->exists($filename)) {
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

//            Questo è quello che avviene in Laravel per iniziare un download
//            header('Content-Type: application/octet-stream');
//            header('Content-Disposition: attachment; filename="' . $filename . '"');
//            header('Content-Length: ' . $disk->size($filename));
//            header('Expires: 0');
//            header('Cache-Control: must-revalidate');
//            header('Pragma: public');
//
//            return readfile($disk->path($filename));
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

        $items = collect($disk->files())
            ->filter(fn($file) => Str::endsWith($file, '.csv'))
            ->map(function ($file) use ($disk) {
                $oDateTime = \DateTime::createFromFormat('U', $disk->lastModified($file));

                return [
                    'filename' => $file,
                    'filesize' => $this->filesizeVerbose($disk->size($file), 1),
                    'filetype' => $disk->mimeType($file),
                    'lastModified' => $oDateTime->format('d/m/Y H:i:s'),
                ];
            })
            ->sortBy(fn($file) => $file['lastModified'], SORT_DESC, true)
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

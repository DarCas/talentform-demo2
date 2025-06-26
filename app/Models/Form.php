<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Form
 *
 * @property int $id
 * @property string $nome
 * @property string $cognome
 * @property string $email
 * @property string $messaggio
 * @property Carbon $data_ricezione
 *
 * @package App\Models
 */
class Form extends Model
{
    protected $table = 'form';
    public $timestamps = false;

    protected $casts = [
        'data_ricezione' => 'datetime'
    ];

    protected $fillable = [
        'nome',
        'cognome',
        'email',
        'messaggio',
        'data_ricezione'
    ];

    /**
     * Helper per modificare la data di ricezione nel formato desiderato
     *
     * @param string $format https://www.php.net/manual/en/datetime.format.php
     * @return string
     */
    function formatDataRicezione(string $format = 'd/m/Y H:i'): string
    {
        return $this->data_ricezione
            // Correggiamo il timezone (fuso orario) della data con quello italiano
            ->setTimezone('Europe/Rome')
            // Stampiamo la data nel formato desiderato
            ->format($format);
    }

    /**
     * Stampo la data in formato ISO-8601
     *
     * @return string
     */
    function getDataRicezioneIso8601(): string
    {
        return $this->formatDataRicezione('c');
    }
}

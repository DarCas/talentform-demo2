<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @property string $fullname
 * @property int $id
 * @property string $usernm
 * @property string $passwd
 * @property Carbon $insert_date
 *
 * @package App\Models
 */
class User extends Model
{
	protected $table = 'users';
	public $timestamps = false;

	protected $casts = [
		'insert_date' => 'datetime'
	];

	protected $fillable = [
		'fullname',
		'usernm',
		'passwd',
		'insert_date'
	];

    /**
     * Helper per modificare la data di ricezione nel formato desiderato
     *
     * @param string $format https://www.php.net/manual/en/datetime.format.php
     * @return string
     */
    function formatInsertDate(string $format = 'd/m/Y H:i'): string
    {
        return $this->insert_date
            // Correggiamo il timezone (fuso orario) della data con quello italiano
            ->setTimezone('Europe/Rome')
            // Stampiamo la data nel formato desiderato
            ->format($format);
    }
}

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
}

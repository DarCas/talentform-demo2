<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UsersLogin
 *
 * @property int $user_id
 * @property Carbon $last_login
 *
 * @property User $user
 *
 * @package App\Models
 */
class UsersLogin extends Model
{
    protected $table = 'users_logins';
    protected $primaryKey = 'user_id';

    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'user_id' => 'int',
        'last_login' => 'datetime'
    ];

    protected $fillable = [
        'user_id',
        'last_login'
    ];

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

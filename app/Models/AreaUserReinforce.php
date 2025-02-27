<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property int $area_id
 * @property int $user_id
 */
class AreaUserReinforce extends Model
{
    protected static string $table = 'area_user_reinforce';
    protected static array $columns = ['area_id', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function validates(): void
    {
        Validations::uniqueness(['area_id', 'user_id'], $this);
    }
}

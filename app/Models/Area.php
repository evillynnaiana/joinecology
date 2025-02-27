<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\BelongsToMany;
use Core\Database\ActiveRecord\HasMany;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use App\Enums\AreaStatus;

/**
 * @property int $id
 * @property string $title
 * @property string $street
 * @property string $city
 * @property string $state
 * @property string $zipcode
 * @property string $number
 * @property int $user_id
 * @property int $status
 * @property User $user
 * @property User[] $reinforced_by_users
 * @property AreaApproval[] $approvals
 */
class Area extends Model
{
    protected static string $table = 'areas';
    protected static array $columns = ['title', 'street', 'city', 'state', 'zipcode', 'number', 'user_id', 'status'];

    public function __construct($params = [])
    {
        parent::__construct($params);
        $this->status = $this->status ?? AreaStatus::PENDING;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reinforcedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'area_user_reinforce', 'area_id', 'user_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(AreaApproval::class, 'area_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('title', $this);
        Validations::notEmpty('street', $this);
        Validations::notEmpty('city', $this);
        Validations::notEmpty('state', $this);
        Validations::notEmpty('zipcode', $this);
        Validations::notEmpty('number', $this);
    }

    public function isSupportedByUser(User $user): bool
    {
        return AreaUserReinforce::exists(['area_id' => $this->id, 'user_id' => $user->id]);
    }

    public function getLatestApproval(): ?AreaApproval
    {
        return AreaApproval::findLatestByAreaId($this->id);
    }

    public function getApprovalUser(): ?User
    {
        $approval = $this->getLatestApproval();
        return $approval ? $approval->user : null;
    }

    public function getStatus(): string
    {
        return AreaStatus::getStatus($this->status);
    }
}

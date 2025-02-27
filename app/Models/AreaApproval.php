<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use Core\Database\Database;
use PDO;

/**
 * @property int $id
 * @property int $area_id
 * @property int $user_id
 * @property int $status_id
 * @property string $created_at
 */
class AreaApproval extends Model
{
    protected static string $table = 'area_approvals';
    protected static array $columns = ['area_id', 'user_id', 'status_id', 'created_at'];

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
        Validations::notEmpty('status_id', $this);
    }
    
    public static function findLatestByAreaId(int $areaId): ?self
    {
        $pdo = Database::getDatabaseConn();
        $table = static::$table;
        
        $sql = "SELECT * FROM {$table} WHERE area_id = :area_id ORDER BY created_at DESC LIMIT 1";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':area_id', $areaId);
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            return null;
        }
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new static($row);
    }
}

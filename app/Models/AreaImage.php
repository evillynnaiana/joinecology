<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property int $area_id
 * @property string $image_name
 * @property string $created_at
 */
class AreaImage extends Model
{
    protected static string $table = 'area_images';
    protected static array $columns = ['area_id', 'image_name', 'created_at'];

    public function save(): bool
    {
        if (!$this->created_at) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        return parent::save();
    }

    /**
     * @return array<int, AreaImage>
     */
    public static function allByArea(int $areaId): array
    {
        return self::where(['area_id' => $areaId]);
    }
}
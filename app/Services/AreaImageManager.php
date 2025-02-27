<?php

namespace App\Services;

use App\Models\AreaImage;
use App\Models\Area;
use Core\Constants\Constants;

class AreaImageManager
{
    /**
     * @var array<string, mixed>
     */
    private $image;
    private Area $area;

    public function __construct(Area $area)
    {
        $this->area = $area;
    }

    /**
     * @param array<string, mixed> $image
     */
    public function upload(array $image): bool
    {
        $this->image = $image;
        $destination = Constants::rootPath()
                         ->join('public/assets/uploads/areas/' . $this->area->id . '/history/');
        $fileName = ImageManager::upload($this->image, $destination, 'area_');
        if ($fileName) {
            $data = [
                'area_id'    => $this->area->id,
                'image_name' => $fileName
            ];
            $areaImage = new AreaImage($data);
            return $areaImage->save();
        }
        return false;
    }
}


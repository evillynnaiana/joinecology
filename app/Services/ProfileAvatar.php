<?php

namespace App\Services;

use Core\Constants\Constants;
use Core\Database\ActiveRecord\Model;

class ProfileAvatar
{
    /**
     * @var array<string, mixed>
     */
    private $image;

    public function __construct(
        private Model $model
    ) {
    }

    public function path(): string
    {
        if ($this->model->avatar_name) {
            return $this->baseDir() . $this->model->avatar_name;
        }

        return "/assets/images/defaults/avatar.png";
    }

    /**
     * @param array<string, mixed> $image
     */
    public function update(array $image): void
    {
        $this->image = $image;
        $destination = Constants::rootPath()
                         ->join('public' . $this->baseDir());
        if ($this->model->avatar_name) {
            $oldPath = $destination . $this->model->avatar_name;
            ImageManager::remove($oldPath);
        }
        $fileName = ImageManager::upload($this->image, $destination, 'avatar.');
        if ($fileName) {
            $this->model->update(['avatar_name' => $fileName]);
        }
    }

    private function baseDir(): string
    {
        return "/assets/uploads/{$this->model::table()}/{$this->model->id}/";
    }
}

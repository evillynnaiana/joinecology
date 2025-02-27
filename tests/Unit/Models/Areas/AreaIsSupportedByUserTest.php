<?php

namespace Tests\Unit\Models\Areas;

use App\Models\Area;
use App\Models\AreaUserReinforce;
use App\Models\User;
use Tests\TestCase;

class AreaIsSupportedByUserTest extends TestCase
{
    private User $user;
    private Area $area;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = new User([
            'name' => 'User 1',
            'email' => 'user1@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'is_admin' => 0
        ]);
        $this->user->save();

        $this->area = new Area([
            'title' => 'Area 1',
            'street' => 'Rua 1',
            'city' => 'Cidade 1',
            'state' => 'Estado 1',
            'zipcode' => '12345-678',
            'number' => '1',
            'user_id' => $this->user->id,
            'status' => 0
        ]);
        $this->area->save();
    }

    public function test_is_supported_by_user(): void
    {
        $areaUserReinforce = new AreaUserReinforce([
            'user_id' => $this->user->id,
            'area_id' => $this->area->id
        ]);
        $areaUserReinforce->save();

        $this->assertTrue($this->area->isSupportedByUser($this->user));
    }
}

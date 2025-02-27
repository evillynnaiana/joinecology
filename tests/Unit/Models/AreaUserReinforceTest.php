<?php

namespace Tests\Unit\Models;

use App\Models\Area;
use App\Models\AreaUserReinforce;
use App\Models\User;
use Tests\TestCase;

class AreaUserReinforceTest extends TestCase
{
    private User $user;
    private Area $area;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
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

    public function test_save_area_user_reinforce(): void
    {
        $areaUserReinforce = new AreaUserReinforce([
            'user_id' => $this->user->id,
            'area_id' => $this->area->id
        ]);

        $this->assertTrue($areaUserReinforce->save());
    }

    public function test_save_area_user_reinforce_with_invalid_data(): void
    {
        $area = new Area([
            'title' => 'Area Test',
            'street' => 'Rua Test',
            'city' => 'Cidade Test',
            'state' => 'Estado Test',
            'zipcode' => '12345-678',
            'number' => '1',
            'user_id' => $this->user->id,
            'status' => 0
        ]);
        $area->save();

        $firstReinforce = new AreaUserReinforce([
            'user_id' => $this->user->id,
            'area_id' => $area->id
        ]);
        $this->assertTrue($firstReinforce->save());

        $duplicateReinforce = new AreaUserReinforce([
            'user_id' => $this->user->id,
            'area_id' => $area->id
        ]);
        
        $this->assertFalse($duplicateReinforce->isValid());
        $this->assertFalse($duplicateReinforce->save());
    }
}

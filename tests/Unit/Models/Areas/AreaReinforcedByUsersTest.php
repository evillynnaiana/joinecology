<?php

namespace Tests\Unit\Models\Areas;

use App\Models\Area;
use App\Models\AreaUserReinforce;
use App\Models\User;
use Tests\TestCase;

class AreaReinforcedByUsersTest extends TestCase
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

    public function test_count_reinforced_users(): void
    {
        $areaUserReinforce = new AreaUserReinforce([
            'user_id' => $this->user->id,
            'area_id' => $this->area->id
        ]);
        $areaUserReinforce->save();

        $this->assertEquals(1, $this->area->reinforcedByUsers()->count());
    }

    public function test_get_all_reinforced_users(): void
    {
        $areaUserReinforce = new AreaUserReinforce([
            'user_id' => $this->user->id,
            'area_id' => $this->area->id
        ]);
        $areaUserReinforce->save();

        $user = new User([
            'name' => 'User 2',
            'email' => 'fulano2@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'is_admin' => 0
        ]);
        $user->save();

        $otherArea = new Area([
            'title' => 'Area 1',
            'street' => 'Rua 1',
            'city' => 'Cidade 1',
            'state' => 'Estado 1',
            'zipcode' => '12345-678',
            'number' => '1',
            'user_id' => $user->id,
            'status' => 0
        ]);
        $otherArea->save();

        $areaUserReinforceByOtherUser = new AreaUserReinforce([
            'area_id' => $otherArea->id,
            'user_id' => $user->id
        ]);
        $areaUserReinforceByOtherUser->save();

        $this->assertCount(2, AreaUserReinforce::all());
        $this->assertEquals(1, $this->area->reinforcedByUsers()->count());

        $this->assertEquals($this->user->id, $this->area->reinforced_by_users[0]->id);
        $this->assertNotEquals($user->id, $this->area->reinforced_by_users[0]->id);
    }
}

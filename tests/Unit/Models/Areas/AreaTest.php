<?php

namespace Tests\Unit\Models\Areas;

use App\Models\Area;
use App\Models\User;
use Tests\TestCase;

class AreaTest extends TestCase
{
    private Area $area;
    private User $user;

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

    public function test_should_create_new_area(): void
    {
        $this->assertTrue($this->area->save());
        $this->assertCount(1, Area::all());
    }

    public function test_all_should_return_all_areas(): void
    {
        $areas[] = $this->area;
        $areas[] = $this->user->areas()->new([
            'title' => 'Area 2',
            'street' => 'Rua 2',
            'city' => 'Cidade 2',
            'state' => 'Estado 2',
            'zipcode' => '12345-678',
            'number' => '2',
            'status' => 0
        ]);
        $areas[1]->save();

        $all = Area::all();
        $this->assertCount(2, $all);
        $this->assertEquals($areas, $all);
    }

    public function test_destroy_should_remove_the_area(): void
    {
        $area2 = $this->user->areas()->new([
            'title' => 'Area 2',
            'street' => 'Rua 2',
            'city' => 'Cidade 2',
            'state' => 'Estado 2',
            'zipcode' => '12345-678',
            'number' => '2',
            'status' => 0
        ]);

        $area2->save();
        $area2->destroy();

        $this->assertCount(1, Area::all());
    }

    public function test_set_title(): void
    {
        $area = $this->user->areas()->new([
            'title' => 'Area 2',
            'street' => 'Rua 2',
            'city' => 'Cidade 2',
            'state' => 'Estado 2',
            'zipcode' => '12345-678',
            'number' => '2',
            'status' => 0
        ]);
        $this->assertEquals('Area 2', $area->title);
    }

    public function test_set_id(): void
    {
        $area = $this->user->areas()->new([
            'title' => 'Area 2',
            'street' => 'Rua 2',
            'city' => 'Cidade 2',
            'state' => 'Estado 2',
            'zipcode' => '12345-678',
            'number' => '2',
            'status' => 0
        ]);
        $area->id = 7;

        $this->assertEquals(7, $area->id);
    }

    public function test_errors_should_return_title_error(): void
    {
        $area = $this->user->areas()->new([
            'title' => 'Area 2',
            'street' => 'Rua 2',
            'city' => 'Cidade 2',
            'state' => 'Estado 2',
            'zipcode' => '12345-678',
            'number' => '2',
            'status' => 0
        ]);
        $area->title = '';

        $this->assertFalse($area->isValid());
        $this->assertFalse($area->save());
        $this->assertFalse($area->hasErrors());

        $this->assertEquals('não pode ser vazio!', $area->errors('title'));
    }

    public function test_find_by_id_should_return_the_area(): void
    {
        $area2 = $this->user->areas()->new([
            'title' => 'Area 2',
            'street' => 'Rua 2',
            'city' => 'Cidade 2',
            'state' => 'Estado 2',
            'zipcode' => '12345-678',
            'number' => '2',
            'status' => 0
        ]);
        $area1 = $this->user->areas()->new([
            'title' => 'Area 1',
            'street' => 'Rua 1',
            'city' => 'Cidade 1',
            'state' => 'Estado 1',
            'zipcode' => '12345-678',
            'number' => '1',
            'status' => 0
        ]);
        $area3 = $this->user->areas()->new([
            'title' => 'Area 3',
            'street' => 'Rua 3',
            'city' => 'Cidade 3',
            'state' => 'Estado 3',
            'zipcode' => '12345-678',
            'number' => '3',
            'status' => 0
        ]);

        $area1->save();
        $area2->save();
        $area3->save();

        $this->assertEquals($area1, Area::findById($area1->id));
    }

    public function test_find_by_id_should_return_null(): void
    {
        $area = $this->user->areas()->new([
            'title' => 'Area 2',
            'street' => 'Rua 2',
            'city' => 'Cidade 2',
            'state' => 'Estado 2',
            'zipcode' => '12345-678',
            'number' => '2',
            'status' => 0
        ]);
        $area->save();

        $this->assertNull(Area::findById(7));
    }

    public function test_is_admin_should_be_int(): void
    {
        $this->assertContains($this->user->is_admin, [0, 1]);
    }
}

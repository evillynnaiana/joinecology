<?php

namespace Tests\Unit\Controllers;

use App\Models\Area;
use App\Models\User;

class AreasControllerTest extends ControllerTestCase
{
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
        $_SESSION['user']['id'] = $this->user->id;
    }

    public function test_list_all_areas(): void
    {
        $areas[] = new Area([
            'title' => 'Area 1',
            'street' => 'Rua 1',
            'city' => 'Cidade 1',
            'state' => 'Estado 1',
            'zipcode' => '12345-678',
            'number' => '1',
            'user_id' => $this->user->id,
            'status' => 0
        ]);
        $areas[] = new Area([
            'title' => 'Area 2',
            'street' => 'Rua 2',
            'city' => 'Cidade 2',
            'state' => 'Estado 2',
            'zipcode' => '12345-678',
            'number' => '2',
            'user_id' => $this->user->id,
            'status' => 0
        ]);

        foreach ($areas as $area) {
            $area->save();
        }

        $response = $this->get(action: 'index', controllerName: 'App\Controllers\AreasController');

        foreach ($areas as $area) {
            $this->assertMatchesRegularExpression("/{$area->title}/", $response);
        }
    }

    public function test_show_area(): void
    {
        $area = new Area([
            'title' => 'Area 1',
            'street' => 'Rua 1',
            'city' => 'Cidade 1',
            'state' => 'Estado 1',
            'zipcode' => '12345-678',
            'number' => '1',
            'user_id' => $this->user->id,
            'status' => 0
        ]);
        $area->save();

        $response = $this->get(
            action: 'show',
            controllerName: 'App\Controllers\AreasController',
            params: ['id' => $area->id]
        );

        $this->assertMatchesRegularExpression("/Visualização da Area #{$area->id}/", $response);
        $this->assertMatchesRegularExpression("/{$area->title}/", $response);
    }

    public function test_successfully_create_area(): void
    {
        $params = ['area' => [
            'title' => 'Area test',
            'street' => 'Rua test',
            'city' => 'Cidade test',
            'state' => 'Estado test',
            'zipcode' => '12345-678',
            'number' => '100',
            'status' => 0
        ]];

        $response = $this->post(
            action: 'create',
            controllerName: 'App\Controllers\AreasController',
            params: $params
        );

        $this->assertMatchesRegularExpression("/Location: \/my-areas/", $response);
    }

    public function test_unsuccessfully_create_area(): void
    {
        $params = ['area' => [
            'title' => '',
            'street' => '',
            'city' => '',
            'state' => '',
            'zipcode' => '',
            'number' => '',
            'status' => 0
        ]];

        $response = $this->post(
            action: 'create',
            controllerName: 'App\Controllers\AreasController',
            params: $params
        );

        $this->assertMatchesRegularExpression("/não pode ser vazio!/", $response);
    }

    public function test_edit_area(): void
    {
        $area = new Area([
            'title' => 'Area 1',
            'street' => 'Rua 1',
            'city' => 'Cidade 1',
            'state' => 'Estado 1',
            'zipcode' => '12345-678',
            'number' => '1',
            'user_id' => $this->user->id,
            'status' => 0
        ]);
        $area->save();

        $response = $this->get(
            action: 'edit',
            controllerName: 'App\Controllers\AreasController',
            params: ['id' => $area->id]
        );

        $this->assertMatchesRegularExpression("/Editar Area #{$area->id}/", $response);

        $regex = '/<input\s[^>]*type=[\'"]text[\'"][^>]*name=[\'"]area\[title\][\'"][^>]*value=[\'"]Area 1[\'"][^>]*>/i';
        $this->assertMatchesRegularExpression($regex, $response);
    }

    public function test_successfully_update_area(): void
    {
        $area = new Area([
            'title' => 'Area 1',
            'street' => 'Rua 1',
            'city' => 'Cidade 1',
            'state' => 'Estado 1',
            'zipcode' => '12345-678',
            'number' => '1',
            'user_id' => $this->user->id,
            'status' => 0
        ]);
        $area->save();
        $params = ['id' => $area->id, 'area' => [
            'title' => 'Area updated',
            'street' => 'Rua updated',
            'city' => 'Cidade updated',
            'state' => 'Estado updated',
            'zipcode' => '12345-678',
            'number' => '100',
            'status' => 0
        ]];

        $response = $this->put(
            action: 'update',
            controllerName: 'App\Controllers\AreasController',
            params: $params
        );

        $this->assertMatchesRegularExpression("/Location: \/my-areas/", $response);
    }

    public function test_unsuccessfully_update_area(): void
    {
        $area = new Area([
            'title' => 'Area 1',
            'street' => 'Rua 1',
            'city' => 'Cidade 1',
            'state' => 'Estado 1',
            'zipcode' => '12345-678',
            'number' => '1',
            'user_id' => $this->user->id,
            'status' => 0
        ]);
        $area->save();
        $params = ['id' => $area->id, 'area' => [
            'title' => '',
            'street' => '',
            'city' => '',
            'state' => '',
            'zipcode' => '',
            'number' => '',
            'status' => 0
        ]];

        $response = $this->put(
            action: 'update',
            controllerName: 'App\Controllers\AreasController',
            params: $params
        );

        $this->assertMatchesRegularExpression("/não pode ser vazio!/", $response);
    }
}

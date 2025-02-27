<?php

namespace Tests\Unit\Lib;

use App\Models\Area;
use App\Models\User;
use Lib\Paginator;
use Tests\TestCase;

class PaginatorTest extends TestCase
{
    private User $user;
    /** @var array<string, array<string>|string> */
    private array $defaultArgs;

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

        for ($i = 1; $i <= 10; $i++) {
            $area = new Area([
                'title' => 'Area ' . $i,
                'street' => 'Rua ' . $i,
                'city' => 'Cidade ' . $i,
                'state' => 'Estado ' . $i,
                'zipcode' => '12345-678',
                'number' => (string)$i,
                'user_id' => $this->user->id,
                'status' => 0
            ]);
            $area->save();
        }

        $this->defaultArgs = [
            'table' => 'areas',
            'attributes' => ['title', 'street', 'city', 'state', 'zipcode', 'number', 'user_id', 'status']
        ];
    }

    public function test_total_of_registers(): void
    {
        $paginator = new Paginator(
            Area::class, 
            1, 
            1,
            $this->defaultArgs['table'],
            $this->defaultArgs['attributes']
        );
        $this->assertEquals(10, $paginator->totalOfRegisters());
    }

    public function test_total_of_pages(): void
    {
        $paginator = new Paginator(
            Area::class, 
            1, 
            5,
            $this->defaultArgs['table'],
            $this->defaultArgs['attributes']
        );
        $this->assertEquals(2, $paginator->totalOfPages());
    }

    public function test_total_of_pages_when_the_division_is_not_exact(): void
    {
        $paginator = new Paginator(
            Area::class, 
            1, 
            4,
            $this->defaultArgs['table'],
            $this->defaultArgs['attributes']
        );
        $this->assertEquals(3, $paginator->totalOfPages());
    }

    public function test_has_next_page(): void
    {
        $paginator = new Paginator(
            Area::class, 
            1, 
            5,
            $this->defaultArgs['table'],
            $this->defaultArgs['attributes']
        );
        $this->assertTrue($paginator->hasNextPage());
    }

    public function test_entries_info(): void
    {
        $paginator = new Paginator(
            Area::class, 
            1, 
            5,
            $this->defaultArgs['table'],
            $this->defaultArgs['attributes']
        );
        $this->assertEquals('Mostrando 1 - 5 de 10', $paginator->entriesInfo());
    }

    public function test_register_return_all(): void
    {
        $paginator = new Paginator(
            Area::class, 
            1, 
            5,
            $this->defaultArgs['table'],
            $this->defaultArgs['attributes']
        );
        $this->assertCount(5, $paginator->registers());
    }
}

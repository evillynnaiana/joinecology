<?php

namespace Database\Populate;

use App\Models\Area;
use App\Models\User;

class AreasPopulate
{
    public static function populate()
    {
        $data =  [
            'title' => 'Jardim',
            'street' => 'Rua das Flores',
            'city' => 'Cidade Jardim',
            'state' => 'Estado Jardim',
            'zipcode' => '12345-678',
            'number' => '100',
            'user_id' => 1,
            'status' => 0
        ];

        $area = new Area($data);
        $area->save();

        echo 'Area created: ' . $area->title . "\n";

        $user = User::findBy(['email' => 'evillyn@email.com']);

        $numberOfAreas = 20;

        for ($i = 0; $i < $numberOfAreas; $i++) {
            $area = new Area([
                'title' => 'Area ' . $i,
                'street' => 'Rua ' . $i,
                'city' => 'Cidade ' . $i,
                'state' => 'Estado ' . $i,
                'zipcode' => '12345-678',
                'number' => (string)$i,
                'user_id' => $user->id,
                'status' => 0
            ]);
            $area->save();
        }

        echo "Areas populated with $numberOfAreas registers\n";
    }
}

<?php

namespace Database\Populate;

use App\Models\User;

class UsersPopulate
{
    public static function populate()
    {
        $data =  [
            [
                'name' => 'Evillyn',
                'email' => 'evillyn@email.com',
                'password' => '123456',
                'password_confirmation' => '123456',
                'is_admin' => 1
            ],
            [
                'name' => 'Andre',
                'email' => 'andre@email.com',
                'password' => '123456',
                'password_confirmation' => '123456',
                'is_admin' => 0
            ]
        ];

        foreach ($data as $user) {
            $user = new User($user);
            $user->save();

            echo 'User created: ' . $user->name . "\n";
        }
    }
}

<?php

namespace Tests\Acceptance\Areas;

use App\Models\Area;
use App\Models\User;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class AreaCest extends BaseAcceptanceCest
{
    public function seeMyAreas(AcceptanceTester $page): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'evillyn@email.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $user->save();

        $area = new Area(['title' => 'Area 1', 'user_id' => $user->id]);
        $area->save();

        $page->login($user->email, $user->password);

        $page->amOnPage('/areas');

        $tableSelector = 'table';

        $page->see('#1', '//table//tr[1]//td[1]');
        $page->see('Area 1', '//table//tr[1]//td[2]');
    }
}

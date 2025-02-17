<?php

namespace App\Containers\AppSection\User\Tests\Unit;

use App\Containers\AppSection\User\Data\Factories\UserFactory;
use App\Containers\AppSection\User\Tasks\FindUserByIdTask;
use App\Containers\AppSection\User\Tests\UnitTestCase;
use App\Ship\Exceptions\NotFoundException;

/**
 * @group user
 * @group unit
 */
class FindUserByIdTaskTest extends UnitTestCase
{
    public function testFindUserById(): void
    {
        $user = UserFactory::new()->createOne();

        $foundUser = app(FindUserByIdTask::class)->run($user->id);

        $this->assertEquals($user->id, $foundUser->id);
    }

    public function testFindUserWithInvalidId(): void
    {
        $this->expectException(NotFoundException::class);

        $noneExistingId = 777777;

        app(FindUserByIdTask::class)->run($noneExistingId);
    }
}

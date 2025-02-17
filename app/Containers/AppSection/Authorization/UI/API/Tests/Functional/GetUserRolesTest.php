<?php

namespace App\Containers\AppSection\Authorization\UI\API\Tests\Functional;

use App\Containers\AppSection\Authorization\Data\Factories\RoleFactory;
use App\Containers\AppSection\Authorization\UI\API\Tests\ApiTestCase;
use App\Containers\AppSection\User\Data\Factories\UserFactory;

/**
 * @group authorization
 * @group api
 */
class GetUserRolesTest extends ApiTestCase
{
    protected string $endpoint = 'get@v1/users/{id}/roles';

    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    public function testGetUserRoles(): void
    {
        $user = UserFactory::new()->createOne();
        $role = RoleFactory::new()->createOne();
        $user->assignRole($role);

        $response = $this->injectId($user->id)->makeCall();

        $response->assertOk();
        $responseContent = $this->getResponseContentObject();
        $this->assertEquals($role->name, $responseContent->data[0]->name);
    }
}

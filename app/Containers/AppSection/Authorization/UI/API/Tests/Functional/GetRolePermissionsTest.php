<?php

namespace App\Containers\AppSection\Authorization\UI\API\Tests\Functional;

use App\Containers\AppSection\Authorization\Data\Factories\PermissionFactory;
use App\Containers\AppSection\Authorization\Data\Factories\RoleFactory;
use App\Containers\AppSection\Authorization\UI\API\Tests\ApiTestCase;

/**
 * @group authorization
 * @group api
 */
class GetRolePermissionsTest extends ApiTestCase
{
    protected string $endpoint = 'get@v1/roles/{id}/permissions';

    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    public function testGetRolePermissions(): void
    {
        $role = RoleFactory::new()->createOne();
        $permission = PermissionFactory::new()->createOne();
        $role->givePermissionTo([$permission]);

        $response = $this->injectId($role->id)->makeCall();

        $response->assertOk();
        $responseContent = $this->getResponseContentObject();
        $this->assertEquals($permission->name, $responseContent->data[0]->name);
    }
}

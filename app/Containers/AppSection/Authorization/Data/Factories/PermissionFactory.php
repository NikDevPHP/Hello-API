<?php

namespace App\Containers\AppSection\Authorization\Data\Factories;

use App\Containers\AppSection\Authorization\Models\Permission;
use App\Ship\Parents\Factories\Factory as ParentFactory;

/**
 * @template TModel of Permission
 *
 * @extends ParentFactory<TModel>
 */
class PermissionFactory extends ParentFactory
{
    /** @var class-string<TModel> */
    protected $model = Permission::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'guard_name' => 'api',
        ];
    }
}

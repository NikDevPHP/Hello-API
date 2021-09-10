<?php

namespace App\Containers\AppSection\User\Tasks;

use App\Containers\AppSection\User\Data\Repositories\UserRepository;
use App\Ship\Parents\Tasks\Task;

class GetAllUsersTask extends Task
{
    public function __construct(
        protected UserRepository $repository
    ) {
    }

    public function run()
    {
        return $this->repository->paginate();
    }
}

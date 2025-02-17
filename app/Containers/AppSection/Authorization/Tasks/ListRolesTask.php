<?php

namespace App\Containers\AppSection\Authorization\Tasks;

use Apiato\Core\Exceptions\CoreInternalErrorException;
use App\Containers\AppSection\Authorization\Data\Repositories\RoleRepository;
use App\Ship\Criterias\ThisLikeThatCriteria;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Prettus\Repository\Exceptions\RepositoryException;

class ListRolesTask extends ParentTask
{
    public function __construct(
        protected readonly RoleRepository $repository,
    ) {
    }

    /**
     * @throws CoreInternalErrorException
     * @throws RepositoryException
     */
    public function run(bool $skipPagination = false): mixed
    {
        $this->repository->addRequestCriteria();

        if ($skipPagination) {
            return $this->repository->all();
        }

        return $this->repository->paginate();
    }

    /**
     * @throws RepositoryException
     */
    public function whereGuard(string|null $guardName): static
    {
        if ($guardName) {
            $this->repository->pushCriteria(new ThisLikeThatCriteria('guard_name', $guardName));
        }

        return $this;
    }
}

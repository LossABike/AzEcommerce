<?php

namespace App\Services\User;

use App\Repositories\User\UserRepositoryInterface;
use App\Services\BaseService;

class UserService extends BaseService implements UserServiceInterface
{
    public function __construct(UserRepositoryInterface $userRepository){
        $this->repository = $userRepository;
    }
    public function checkExistAccount($email,$level){
        return $this->repository->checkExistAccount($email,$level);
    }
    public function getUserClient(){
        return $this->repository->getUserClient();
    }
    public function searchAndPaginate($searchBy ,$keyword, $perPage = 10,$orderBy = 'asc'){
        return $this->repository->searchAndPaginate($searchBy,$keyword,$perPage);
    }

}

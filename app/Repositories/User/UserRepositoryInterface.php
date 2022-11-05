<?php

namespace App\Repositories\User;

use App\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function checkExistAccount($email,$level);
    //public function getUserClient();
    public function searchAndPaginate($searchBy ,$keyword, $perPage = 10);

}

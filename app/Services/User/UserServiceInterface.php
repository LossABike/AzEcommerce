<?php

namespace App\Services\User;

use App\Services\ServiceInterface;

interface UserServiceInterface extends ServiceInterface
{
    public function checkExistAccount($email,$level);
    //public function getUserClient();
    public function searchAndPaginate($searchBy ,$keyword, $perPage = 10);
}

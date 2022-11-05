<?php

namespace App\Services\BlogService;

use App\Services\ServiceInterface;

interface BlogServiceInterface extends ServiceInterface
{
    public function getLastestBlogs($limit = 3);
}

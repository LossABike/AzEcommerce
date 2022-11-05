<?php

namespace App\Repositories\Blog;

use App\Repositories\RepositoryInterface;

interface BlogRepositoryInterface Extends RepositoryInterface
{
    public function getLastestBlogs($limit = 3);
}

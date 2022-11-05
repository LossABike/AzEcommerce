<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Utilities\Constant;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return User::class;
    }
    ///check exist account before create
    public function checkExistAccount($email,$level){
        return $this->model->where('email',$email)->where('level',$level)->exists();
    }

//    public function getUserClient(){
//        return $this->model->whereIn('level',[Constant::user_level_client,Constant::user_level_sealed])->get();
//    }
    //Use only for Admin
    public function searchAndPaginate($searchBy ,$keyword, $perPage = 10){
        return $this->model->whereIn('level',[Constant::user_level_client,Constant::user_level_sealed])
                            ->where($searchBy,'like','%' . $keyword . '%')
                            ->orderBy('id','asc')
                            ->paginate($perPage)
                            ->appends(['search' => $keyword]);
    }


}

<?php


namespace App\Repositories;





use App\Utilities\Constant;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;
    public function __construct(){
        //make()-> resolve $this->getModel() out of singleton
        $this->model = app()->make(
            $this->getModel()
        );

    }

    abstract public function getModel();

    public function all()
    {
        return $this->model->all();
    }
    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }
    public function create(array $data)
    {
        return $this->model->create($data);
    }
    public function update(array $data, $id)
    {
        $object = $this->model->find($id);
        return $object->update($data);
    }

    public function delete($id)
    {
        $object = $this->model->find($id);
        return $object->delete();
    }
    //only use for admin
    public function searchAndPaginate($searchBy ,$keyword, $perPage = 10){
        return $this->model
            ->where($searchBy,'like','%' . $keyword . '%')
            ->orderBy('id','asc')
            ->paginate($perPage)
            ->appends(['search' => $keyword]);
    }

}

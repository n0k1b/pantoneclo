<?php

namespace App\Repositories;

use App\Contracts\BaseContract;
use App\Utilities\Action;
use Illuminate\Database\Eloquent\Model;

class BaseRepository extends Action implements BaseContract
{
    protected $model;

    public function __construct(Model $model){
        $this->model = $model;
    }

    public function getById($id){
        return $this->model->find($id);
    }

    public function active($id){
        Action::setActive($this->getById($id));
    }

    public function inactive($id){
        Action::setInactive($this->getById($id));
    }

    public function destroy($columnName, $id){
        return $this->model::where($columnName, $id)->delete();
    }

    public function bulkAction($type, $columnName, $ids){
        Action::setBulkAction($type, $this->model::whereIn($columnName, $ids));
    }
}

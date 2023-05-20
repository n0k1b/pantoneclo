<?php

namespace App\Repositories\Role;

use App\Contracts\Role\RoleContract;
use App\Repositories\BaseRepository;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository implements RoleContract
{
    public function __construct(Role $model){
        parent::__construct($model);
    }
}

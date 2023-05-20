<?php

namespace App\Repositories\Menu;

use App\Contracts\Menu\MenuContract;
use App\Repositories\BaseRepository;
use Harimayco\Menu\Models\Menus;

class MenuRepository extends BaseRepository implements MenuContract
{
    public function __construct(Menus $model){
        parent::__construct($model);
    }
}

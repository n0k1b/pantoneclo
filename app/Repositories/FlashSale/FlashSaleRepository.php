<?php

namespace App\Repositories\FlashSale;

use App\Contracts\FlashSale\FlashSaleContract;
use App\Models\FlashSale;
use App\Utilities\Action;

class FlashSaleRepository extends Action implements FlashSaleContract
{

    public function getById($id){
        return FlashSale::find($id);
    }

    public function active($id){
        Action::setActive($this->getById($id));
    }

    public function inactive($id){
        Action::setInactive($this->getById($id));
    }

    public function destroy($id){
        $this->getById($id)->delete();
    }

    public function bulkAction($type, $ids){
        Action::setBulkAction($type, FlashSale::whereIn('id',$ids));
    }
}

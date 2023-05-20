<?php

namespace App\Repositories\FlashSale;

use App\Contracts\FlashSale\FlashSaleProductContract;
use App\Models\FlashSaleProduct;
use App\Utilities\Action;

class FlashSaleProductRepository implements FlashSaleProductContract
{
    public function destroy($flash_sale_id){
        FlashSaleProduct::where('flash_sale_id', $flash_sale_id)->delete();
    }

    public function bulkAction($type, $ids){
        return Action::setBulkAction($type, FlashSaleProduct::whereIn('flash_sale_id',$ids));
    }
}

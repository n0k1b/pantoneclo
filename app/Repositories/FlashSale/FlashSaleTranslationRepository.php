<?php

namespace App\Repositories\FlashSale;

use App\Contracts\FlashSale\FlashSaleTranslationContract;
use App\Models\FlashSaleTranslations;
use App\Utilities\Action;

class FlashSaleTranslationRepository extends Action implements FlashSaleTranslationContract
{
    public function destroy($flash_sale_id){
        FlashSaleTranslations::where('flash_sale_id', $flash_sale_id)->delete();
    }

    public function bulkAction($type, $ids){
        return Action::setBulkAction($type, FlashSaleTranslations::whereIn('flash_sale_id',$ids));
    }
}

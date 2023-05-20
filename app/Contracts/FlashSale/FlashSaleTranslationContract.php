<?php

namespace App\Contracts\FlashSale;

interface FlashSaleTranslationContract
{
    public function destroy($flash_sale_id);

    public function bulkAction($type, $ids);
}

<?php

namespace App\Contracts\FlashSale;

interface FlashSaleProductContract
{
    public function destroy($falsh_sale_id);

    public function bulkAction($type, $ids);
}

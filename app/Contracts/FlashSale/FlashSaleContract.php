<?php

namespace App\Contracts\FlashSale;

interface FlashSaleContract
{
    public function getById($id);

    public function active($id);

    public function inactive($id);

    public function destroy($id);

    public function bulkAction($type, $ids);
}

<?php

namespace App\Contracts\Order;

interface OrderContract
{
    public function getById($id);

    public function destroy($id);
}

?>

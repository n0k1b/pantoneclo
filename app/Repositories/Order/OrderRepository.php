<?php

namespace App\Repositories\Order;

use App\Contracts\Order\OrderContract;
use App\Contracts\Order\OrderDetailsContract;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Utilities\Action;

class OrderRepository extends Action implements OrderContract, OrderDetailsContract
{
    public function getById($id){
        return Order::find($id);
    }

    public function destroy($id){
        $this->getById($id)->delete();
        $this->findOrderDetailsByOrderId($id)->delete();

    }

    protected function findOrderDetailsByOrderId($id){
        return OrderDetail::where('order_id',$id);
    }
}

?>

<?php
namespace App\Services;

use App\Contracts\Order\OrderContract;
use App\Utilities\Message;

class OrderService extends Message
{
    private $orderContract;
    public function __construct(OrderContract $orderContract){
        $this->orderContract = $orderContract;
    }

    public function destroy($id)
    {
        $this->orderContract->destroy($id);
        return Message::deleteSuccessMessage();
    }
}


?>

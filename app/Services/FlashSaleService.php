<?php
namespace App\Services;

use App\Contracts\FlashSale\FlashSaleContract;
use App\Contracts\FlashSale\FlashSaleProductContract;
use App\Contracts\FlashSale\FlashSaleTranslationContract;
use App\Utilities\Message;

class FlashSaleService extends Message
{
    private $flashSaleContract, $flashSaleTranslationContract, $flashSaleProductContract;
    public function __construct(FlashSaleContract $flashSaleContract, FlashSaleTranslationContract $flashSaleTranslationContract, FlashSaleProductContract $flashSaleProductContract){
        $this->flashSaleContract = $flashSaleContract;
        $this->flashSaleTranslationContract = $flashSaleTranslationContract;
        $this->flashSaleProductContract = $flashSaleProductContract;
    }

    public function activeById($id){
        if (!auth()->user()->can('flash_sale-action')){
            return Message::getPermissionMessage();
        }
        $this->flashSaleContract->active($id);
        return Message::activeSuccessMessage();
    }

    public function inactiveById($id){
        if (!auth()->user()->can('flash_sale-action')){
            return Message::getPermissionMessage();
        }
        $this->flashSaleContract->inactive($id);
        return Message::inactiveSuccessMessage();
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('flash_sale-action')){
            return Message::getPermissionMessage();
        }
        $this->flashSaleContract->destroy($id);
        $this->flashSaleTranslationContract->destroy($id);
        $this->flashSaleProductContract->destroy($id);
        return Message::deleteSuccessMessage();
    }

    public function bulkActionByTypeAndIds($type, $ids)
    {
        if (!auth()->user()->can('flash_sale-action')){
            return Message::getPermissionMessage();
        }
        if ($type=='delete') {
            $this->flashSaleContract->bulkAction($type, $ids);
            $this->flashSaleTranslationContract->bulkAction($type, $ids);
            $this->flashSaleProductContract->bulkAction($type, $ids);
            return Message::deleteSuccessMessage();
        }else{
            $this->flashSaleContract->bulkAction($type, $ids);
            return $type=='active' ? Message::activeSuccessMessage() : Message::inactiveSuccessMessage();
        }
    }
}

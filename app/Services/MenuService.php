<?php
namespace App\Services;

use App\Contracts\Menu\MenuContract;
use App\Contracts\Menu\MenuTranslationContract;
use App\Utilities\Message;

class MenuService extends Message
{
    private $menuContract;
    private $menuTranslationContract;
    public function __construct(MenuContract $menuContract, MenuTranslationContract $menuTranslationContract){
        $this->menuContract = $menuContract;
        $this->menuTranslationContract = $menuTranslationContract;
    }

    public function activeById($id){
        if (!auth()->user()->can('menu-action')){
            return Message::getPermissionMessage();
        }
        $this->menuContract->active($id);
        return Message::activeSuccessMessage();
    }

    public function inactiveById($id){
        if (!auth()->user()->can('menu-action')){
            return Message::getPermissionMessage();
        }
        $this->menuContract->inactive($id);
        return Message::inactiveSuccessMessage();
    }

    public function destroy($id){
        if (!auth()->user()->can('menu-action')){
            return Message::getPermissionMessage();
        }
        $this->menuContract->destroy('id', $id);
        $this->menuTranslationContract->destroy('menu_id', $id);
        return Message::deleteSuccessMessage();
    }

    public function bulkActionByTypeAndIds($type, $ids){
        if (!auth()->user()->can('menu-action')){
            return Message::getPermissionMessage();
        }

        if ($type=='delete') {
            $this->menuContract->bulkAction($type, 'id', $ids);
            $this->menuTranslationContract->bulkAction($type, 'menu_id', $ids);
            return Message::deleteSuccessMessage();
        }else{
            $this->menuContract->bulkAction($type, 'id', $ids);
            return $type=='active' ? Message::activeSuccessMessage() : Message::inactiveSuccessMessage();
        }
    }
}

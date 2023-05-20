<?php
namespace App\Services;

use App\Contracts\Role\RoleContract;
use App\Utilities\Message;

class RoleService extends Message
{
    private $RoleContract;
    public function __construct(RoleContract $roleContract){
        $this->roleContract = $roleContract;
    }

    public function activeById($id){
        if (!auth()->user()->can('role-action')){
            return Message::getPermissionMessage();
        }
        $this->roleContract->active($id);
        return Message::activeSuccessMessage();
    }

    public function inactiveById($id){
        if (!auth()->user()->can('role-action')){
            return Message::getPermissionMessage();
        }
        $this->roleContract->inactive($id);
        return Message::inactiveSuccessMessage();
    }

    public function destroy($id){
        if (!auth()->user()->can('role-action')){
            return Message::getPermissionMessage();
        }
        $this->roleContract->destroy('id', $id);
        return Message::deleteSuccessMessage();
    }

    public function bulkActionByTypeAndIds($type, $ids){
        if (!auth()->user()->can('role-action')){
            return Message::getPermissionMessage();
        }
        $this->roleContract->bulkAction($type, 'id', $ids);
        if ($type=='delete') {
            return Message::deleteSuccessMessage();
        }else{
            return $type=='active' ? Message::activeSuccessMessage() : Message::inactiveSuccessMessage();
        }
    }
}

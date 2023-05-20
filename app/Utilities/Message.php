<?php
namespace App\Utilities;

class Message
{
    public function setSuccessMessage($message): void
	{
        session()->flash('message',$message);
        session()->flash('type','success');
	}

	public function setErrorMessage($message): void
    {
        session()->flash('message',$message);
		session()->flash('type','danger');
	}

    public static function storeSuccessMessage(){
        return response()->json(['success' => __('Data Stored Successfully')]);
    }

    public static function updateSuccessMessage(){
        return response()->json(['success' => __('Data Updated Successfully')]);
    }

    public static function deleteSuccessMessage(){
        return response()->json(['success' => __('Data Deleted Successfully')]);
    }

    public static function activeSuccessMessage(){
        return response()->json(['success' => 'Data Active Successfully']);
    }

    public static function inactiveSuccessMessage(){
        return response()->json(['success' => 'Data Inactive Successfully']);
    }

    public static function getErrorMessage($data){
        return response()->json(['errors' => [$data]], 422);
    }

    public static function getPermissionMessage(){
        return response()->json(['errors' => __('You are not Authorised')]);
    }


}
?>

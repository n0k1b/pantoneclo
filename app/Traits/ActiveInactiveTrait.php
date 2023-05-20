<?php
namespace App\Traits;

trait ActiveInactiveTrait{

    public function activeData($Model)
    {
        $data = $Model;
        $data->is_active = 1;
        $data->save();

        return response()->json(['success' => 'Data Active Successfully']);
    }

    public function inactiveData($Model)
    {
        $data = $Model;
        $data->is_active = 0;
        $data->save();

        return response()->json(['success' => 'Data Inactive Successfully']);
    }

    public function bulkActionData($action_type,$Model)
    {
        $data = $Model;
        if ($action_type=='active'){
            $data->update(['is_active'=>1]);
            return response()->json(['success' => 'Data Active Successfully']);
        }else if ($action_type=='inactive'){
            $data->update(['is_active'=>0]);
            return response()->json(['success' => 'Data Inactive Successfully']);
        }else if ($action_type=='delete'){
            $data->delete();
            return response()->json(['success' => 'Data Deleted Successfully']);
        }
    }
}

?>

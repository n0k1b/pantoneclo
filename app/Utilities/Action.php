<?php
namespace App\Utilities;

class Action
{

    public static function setActive($Model)
    {
        $data = $Model;
        $data->is_active = 1;
        $data->save();
    }


    public static function setInactive($Model)
    {
        $data = $Model;
        $data->is_active = 0;
        $data->save();
    }


    public static function setBulkAction($action_type, $Model)
    {
        $data = $Model;

        if ($action_type=='active'){
            $data->update(['is_active'=>1]);
        }
        else if ($action_type=='inactive'){
            $data->update(['is_active'=>0]);
        }
        else if ($action_type=='delete'){
            $data->delete();
        }
    }
}

<?php
namespace App\Traits;

trait WordCheckTrait
{
    public function wordCheckInURL($word)
    {
        $url = url()->current();
        $array_data = explode("/",$url);

        // admin adn expected-word check
        $admin_exists = in_array('admin', $array_data);
        $word_exists  = in_array($word, $array_data);
        if($admin_exists && $word_exists){
            return true;
        }else{
            return false;
        }
    }
}

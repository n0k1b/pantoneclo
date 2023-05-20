<?php
namespace App\Services;

use App\Contracts\Review\ReviewContract;
use App\Utilities\Message;

class ReviewService extends Message
{
    private $reviewContract;
    public function __construct(ReviewContract $reviewContract){
        $this->reviewContract = $reviewContract;
    }


    public function destroy($id)
    {
        if (!auth()->user()->can('review-action')){
            return Message::getPermissionMessage();
        }
        $this->reviewContract->destroy($id);
        return Message::deleteSuccessMessage();
    }

}

?>

<?php

namespace App\Contracts\Review;

interface ReviewContract
{
    public function getById($id);

    public function destroy($id);
}

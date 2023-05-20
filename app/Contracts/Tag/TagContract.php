<?php

namespace App\Contracts\Tag;

interface TagContract
{
    public function getAll();

    public function getAllActiveData();

    public function storeData($data);

    public function getById($id);

    public function updateDataById($id, $data);

    public function active($id);

    public function inactive($id);

    public function destroy($id);

    public function bulkAction($type, $ids);
}

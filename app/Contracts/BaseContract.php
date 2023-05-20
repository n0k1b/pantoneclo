<?php

namespace App\Contracts;

interface BaseContract
{
    public function getById($id);

    public function active($id);

    public function inactive($id);

    public function destroy($columnName, $id);

    public function bulkAction($type, $columnName, $ids);
}

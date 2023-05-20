<?php

namespace App\Contracts\Page;

interface PageContract
{
    public function getAll();

    public function store($data);

    public function getById($id);

    public function update($data);

    public function active($id);

    public function inactive($id);

    public function destroy($id);

    public function bulkAction($type, $ids);
}

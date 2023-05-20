<?php

namespace App\Contracts\Tax;

interface TaxContract
{
    public function getAll();

    public function getAllActiveData();

    public function store($data);

    public function getById($id);

    public function update($data);

    public function active($id);

    public function inactive($id);

    public function destroy($id);

    public function bulkAction($type, $ids);
}

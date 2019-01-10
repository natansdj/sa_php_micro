<?php

namespace App\Exports;

use App\Models\ViewProduct;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ViewProductExport implements FromQuery
{
    use Exportable;

    public function __construct(int $store_id)
    {
        $this->store_id = $store_id;
    }

    public function query()
    {
        return ViewProduct::query()->where('store_id', $this->store_id);
    }
}

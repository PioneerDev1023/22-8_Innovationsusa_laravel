<?php

namespace App\Exports;

use App\ProductMaster;
use Maatwebsite\Excel\Concerns\FromArray;

class ProductExport implements FromArray
{
    protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }

    public function array(): array
    {
        return $this->invoices;
    }
}

<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ArrayExport implements WithHeadings, FromArray
{
    protected $array;
    protected $headings;

    public function __construct(array $array)
    {
        // Assume the first row of the array is the header
        $this->headings = array_shift($array);
        $this->array = $array;
    }

    public function array(): array
    {
        return $this->array;
    }

    public function headings(): array
    {
        return $this->headings;
    }
}

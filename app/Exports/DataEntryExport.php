<?php

namespace App\Exports;

use App\Models\DataEntry;
use Maatwebsite\Excel\Concerns\FromCollection;

class DataEntryExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DataEntry::all();
    }
}

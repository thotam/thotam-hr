<?php

namespace Thotam\ThotamHr\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BfoImport implements  WithHeadingRow
{
    use Importable;

    public function headingRow(): int
    {
        return 1;
    }
}

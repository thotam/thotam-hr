<?php

namespace Thotam\ThotamHr\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class BfoDropBoxImport implements WithHeadingRow, WithCalculatedFormulas
{
    use Importable;

    public function headingRow(): int
    {
        return 1;
    }
}

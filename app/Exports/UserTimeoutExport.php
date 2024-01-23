<?php

namespace App\Exports;

use App\Attendance;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class UserTimeoutExport implements FromArray, WithHeadings, ShouldAutoSize
{
    use Exportable;

    protected $attendance;

    public function __construct(array $attendance)
    {
        $this->attendance = $attendance;
    }


    public function headings(): array
    {
        return [
            'Date',
            'Name',
            'Time In',
            'Time Out',
            'Total Working Hour'
        ];
    }

    public function array(): array
    {
        return $this->attendance;
    }


    public function columnFormats(): array
    {

    }
}


<?php

namespace App\Exports;

use App\DocumentRequest;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DocumentRequestExport implements WithHeadings, ShouldAutoSize, FromArray
{
    use Exportable;

    protected $document_request;

    public function __construct(array $document_request)
    {
        $this->document_request = $document_request;
    }

    public function headings(): array
    {
        return [
            'Employee Name',
            'Employee Code',
            'Document Name',
            'Requested Type',
            'Requested Date',
        ];
    }

    public function array(): array
    {
        return $this->document_request;
    }
}

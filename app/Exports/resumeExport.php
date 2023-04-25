<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class resumeExport implements FromArray, WithMultipleSheets, WithTitle {

    protected $sheets;
    protected $labels;
    protected $typeExport;
    protected $title;

    public function __construct(array $sheets, $labels, $typeExport, $title){
        $this->sheets = $sheets;
        $this->labels = $labels;
        $this->typeExport = $typeExport;
        $this->title = $title;
    }

    public function array(): array {

        return $this->sheets;
    }

    public function sheets(): array{
        
        $sheet = array();

        array_push($sheet, new resumeTabExport($this->labels[0], $this->sheets, $this->typeExport));
        array_push($sheet, new payTvTabExport($this->labels[1], $this->sheets, $this->typeExport));
        array_push($sheet, new bvTableTabExport($this->labels[2], $this->sheets, $this->typeExport));

        return $sheet;
    }

    public function title(): string{
        return $this->title;
    }
}

<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class quarterTabExport implements FromView, WithEvents, ShouldAutoSize, WithTitle {
    
    protected $view;
	protected $data;
    protected $type;

	protected $headStyle = [
        'font' => [
            'bold' => true,
            'name' => 'Verdana',
            'size' => 12,
            'color' => array('rgb' => 'FFFFFF')
        ],
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
            'wrapText' => true
        ],
    ];

    protected $BodyCenter = [
        'font' => [
            'name' => 'Verdana',
            'size' => 10,
        ],
        'alignment' => [
            'horizontal' => 'center',
            'vertical' => 'center',
            'wrapText' => true
        ],
    ];

    public function __construct($view, $data, $type){
        $this->view = $view;
        $this->data = $data;
        $this->type = $type;
    }

	public function view(): View{
    	return view($this->view, ['data' => $this->data]);
    }

    /**
    * @return array
    */
    public function registerEvents(): array{
        
        return [
            AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
                
                $c = 0;

                for ($dm=3; $dm < ((sizeof($this->data['mtx'])*6)+2); $dm++) { 
            		$cellRange = "A".$dm.":H".$dm;
            		$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->BodyCenter);

                    if ($this->type != "Excel") {
                        $c++;

                        if ($c == 30) {
                            $cell = "A".($dm-1);
                            $event->sheet->getDelegate()->setBreak($cell, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                            $c = 0;
                        }
                    }
                }

                for ($dm=0; $dm < sizeof($this->data['mtx']); $dm++) { 
                    if ($dm == 0) {
                        $cellRange = "A".($dm+4).":H".($dm+4);
                        $cellRange2 = "A".($dm+5).":H".($dm+5);
                        $cellRange3 = "A".($dm+6).":H".($dm+6);
                        $cellRange4 = "A".($dm+7).":H".($dm+7);

                        $b = 4;
                        $b2 = 5;
                        $b3 = 6;
                        $b4 = 7;
                    }else{
                        $cellRange = "A".($b+6).":H".($b+6);
                        $cellRange2 = "A".($b2+6).":H".($b2+6);
                        $cellRange3 = "A".($b3+6).":H".($b3+6);
                        $cellRange4 = "A".($b4+6).":H".($b4+6);

                        $b = $b+6;
                        $b2 = $b2+6;
                        $b3 = $b3+6;
                        $b4 = $b4+6;
                    }

                    $event->sheet->getStyle($cellRange)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));

                    $event->sheet->getStyle($cellRange2)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));

                    $event->sheet->getStyle($cellRange3)->getNumberFormat()->applyFromArray(array('formatCode' => "0%"));

                    $event->sheet->getStyle($cellRange4)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));
                }

                if ($this->type != "Excel") {

                    $cellRange = "A2:N2";
                    $event->sheet->getDelegate()->mergeCells($cellRange);

                    $event->sheet->getDelegate()->getPageSetup()
                        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                }

            },
        ];
    }

    public function title(): string{
        return "quarter";
    }
}

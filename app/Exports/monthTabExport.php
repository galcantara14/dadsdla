<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class monthTabExport implements FromView, WithEvents, ShouldAutoSize, WithTitle {
    
    protected $view;
	protected $data;

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

    public function __construct($view, $data){
		$this->view = $view;
	    $this->data = $data;
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
               
                for ($dm=3; $dm < ((sizeof($this->data['mtx'])*6)+2); $dm++) { 
            		$cellRange = "A".$dm.":N".$dm;
            		$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->BodyCenter);
                }

                for ($dm=0; $dm < sizeof($this->data['mtx']); $dm++) { 
                    if ($dm == 0) {
                        $cellRange = "A".($dm+4).":N".($dm+4);
                        $cellRange2 = "A".($dm+5).":N".($dm+5);
                        $cellRange3 = "A".($dm+6).":N".($dm+6);
                        $cellRange4 = "A".($dm+7).":N".($dm+7);

                        $b = 4;
                        $b2 = 5;
                        $b3 = 6;
                        $b4 = 7;
                    }else{
                        $cellRange = "A".($b+6).":N".($b+6);
                        $cellRange2 = "A".($b2+6).":N".($b2+6);
                        $cellRange3 = "A".($b3+6).":N".($b3+6);
                        $cellRange4 = "A".($b4+6).":N".($b4+6);

                        $b = $b+6;
                        $b2 = $b2+6;
                        $b3 = $b3+6;
                        $b4 = $b4+6;
                    }

                    $event->sheet->getStyle($cellRange)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));

                    $event->sheet->getStyle($cellRange2)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));

                    $event->sheet->getStyle($cellRange3)->getNumberFormat()->applyFromArray(array('formatCode' => "#0%"));

                    $event->sheet->getStyle($cellRange4)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));
                }

            },
        ];
    }

    public function title(): string{
        return "month";
    }
}
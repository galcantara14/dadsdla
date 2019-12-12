<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class bonusExport implements FromView, WithEvents, ShouldAutoSize, WithTitle {
    
    protected $view;
	protected $data;

	protected $headStyle = [
		'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => '0070c0',
            ],
        ],
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

    protected $t1LineStyle = [
		'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'dce6f1',
            ],
        ],
    ];

    protected $t2LineStyle = [
		'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'c3d8ef',
            ],
        ],
    ];

    protected $bodyCenter = [
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

    public function __construct($view,$data){
    	$this->view = $view;
    	$this->data = $data;
    }

    public function view(): View{
    	return view($this->view, ['data' => $this->data]);
    }

    public function title(): string{
        return "Bonus - ".$this->data['salesRep'][0]['name'];
    }

    /**
    * @return array
    */
    public function registerEvents(): array{


    	return [
    		AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

               	$cellRange = "A3:A6";
               	$event->sheet->getDelegate()->mergeCells($cellRange);

               	$cellRange = "A3:G6";
               	$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->bodyCenter);

               	$cellRange = "A8:A11";
               	$event->sheet->getDelegate()->mergeCells($cellRange);

               	$cellRange = "A8:G11";
               	$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->bodyCenter);

               	$cellRange = "B4:F4";
				$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->t1LineStyle);

				$cellRange = "B5:F5";
				$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->t2LineStyle);

				$cellRange = "B9:F9";
				$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->t1LineStyle);

				$cellRange = "B10:F10";
				$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->t2LineStyle);

				$cellRange = "C4:G6";
				$event->sheet->getStyle($cellRange)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));

				$cellRange = "C8:G11";
				$event->sheet->getStyle($cellRange)->getNumberFormat()->applyFromArray(array('formatCode' => "#,##0"));	
            }
    	];
    }
}
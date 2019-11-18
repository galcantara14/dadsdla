<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class yoyBrandTabExport implements FromView,WithEvents, ShouldAutoSize, WithTitle {
    
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

    public function title(): string{
        return "YoY-Brand";
    }

    /**
    * @return array
    */
    public function registerEvents(): array{


    	return [
    		AfterSheet::class => function(AfterSheet $event){
                $cellRange = "A1";
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);
            	
            	for ($dm=3; $dm < ((sizeof($this->data['mtx'])*6)+1); $dm++) { 
            		$cellRange = "A".$dm.":O".$dm;
            		$cellRange2 = "A".$dm.":A".((sizeof($this->data["mtx"][0]))+$dm);
            		$event->sheet->getDelegate()->mergeCells($cellRange2);
            		$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->BodyCenter);
                }
            }
    	];
    }

}
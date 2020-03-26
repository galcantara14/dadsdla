<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class viewerBaseTabExport implements FromView,WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting {

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
			'horizontal' => 'left',
			'vertical' => 'center',
			'wrapText' => true
		],
	];

	protected $indexStyle = [
		'font' => [
			'bold' => true,
			'name' => 'Verdana',
			'size' => 10,
			'color' => array('rgb' => '000000')
		],
		'alignment' => [
			'horizontal' => 'center',
			'vertical' => 'center',
			'wrapText' => true
		],
	]; 

	protected $totalStyle = [
		'font' => [
			'bold' => true,
			'name' => 'Verdana',
			'size' => 10,
			'color' => array('rgb' => 'FFFFFF')
		],
		'alignment' => [
			'horizontal' => 'center',
			'vertical' => 'center',
			'wrapText' => true
		],
	];

	protected $linePair = [
		'font' => [
			'bold' => true,
			'name' => 'Verdana',
			'size' => 10,
			'color' => array('rgb' => '000000')
		],
		'alignment' => [
			'horizontal' => 'center',
			'vertical' => 'center',
			'wrapText' => true
		],
		'fill' => [
			'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
			'startColor' => [
				'rgb' => 'f9fbfd',
			],
		],
	];

	protected $lineOdd = [
		'font' => [
			'bold' => true,
			'name' => 'Verdana',
			'size' => 10,
			'color' => array('rgb' => '000000')
		],
		'alignment' => [
			'horizontal' => 'center',
			'vertical' => 'center',
			'wrapText' => true
		],
		'fill' => [
			'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
			'startColor' => [
				'rgb' => 'c3d8ef',
			],
		],
	];

	public function __construct($view, $data, $type){
        $this->view = $view;
        $this->data = $data;
        $this->type = $type;
    }

	public function view(): View{
		$c = 0;
		return view($this->view, ['data' => $this->data, 'type' => $this->type, 'c' => $c]);
	}

	public function title(): string{
		return 'Viewer - Base';
	}

	public function registerEvents(): array{

		return [
			AfterSheet::class => function(AfterSheet $event){
				$cellRange = 'A1';
				$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->headStyle);

				$cellRange = 'A2:O2';
				$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->indexStyle);

				$cellRange = 'A3:O3';
				$event->sheet->getdelegate()->getStyle($cellRange)->applyFromArray($this->totalStyle);

				$letter = 'O';

				for ($d = 0; $d < sizeof($this->data['mtx']); $d++) { 
					$cellRange = "A".($d+4).":".$letter.($d+4);
					if (($d+3) % 2 == 0) {
						$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->linePair);
					}else{
						$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($this->lineOdd);
					}
				}

				if ($this->type != "Excel") {

                    $event->sheet->getDelegate()->getPageSetup()
                        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                }
			},
		];
	}

	public function columnFormats(): array{

		return[
			'K' => '0%',
			'N' => '#,##0',
			'O' => '#,##0'
		];
	}
}



<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class rankingNewExport implements FromArray, WithMultipleSheets {
    
    protected $sheets;
	protected $labels;

	public function __construct(array $sheets, $labels){
		$this->sheets = $sheets;
		$this->labels = $labels;
	}

	public function array(): array {
        return $this->sheets;
    }

    public function sheets(): array{
    	
    	$sheets = array();

    	array_push($sheets, new allNewExport($this->labels[0], $this->sheets));

    	if (isset($this->sheets['subMtx'])) {
    		$names = array("region" => $this->sheets['region'], "currency" => $this->sheets['currency'], 'value' => $this->sheets['value'], "head" => $this->sheets['headNames'], 'type' => $this->sheets['type'], 'years' => $this->sheets['years'], 'val' => $this->sheets['val']);

	    	for ($i=0; $i < sizeof($this->sheets['subMtx']); $i++) { 
	    		array_push($sheets, new newExport($this->labels[1], $this->sheets['subMtx'][$i], $this->sheets['subTotal'][$i], $this->sheets['new'][$i], $names));
	    	}	
    	}

    	return $sheets;
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\sql;

class CheckElements extends Model{
    
	public function newValues($conDLA,$con,$table){

		$sql = new sql();

		$regions = $this->checkNewRegions($conDLA,$con,$table,$sql);
		$brands = $this->checkNewBrands($conDLA,$con,$table,$sql);
		$salesReps = $this->checkNewSalesReps($conDLA,$con,$table,$sql);
		$clients = $this->checkNewClients($conDLA,$con,$table,$sql);
		$agencies = $this->checkNewAgencies($conDLA,$con,$table,$sql);
		$currencies = $this->checkNewCurrencies($conDLA,$con,$table,$sql);

		$rtr = array(
				'regions' => $regions,
				'brands' => $brands,
				'salesReps' => $salesReps,
				'clients' => $clients,
				'agencies' => $agencies,
				'currencies' => $currencies
			);
		
		return($rtr);

	}


	public function checkNewRegions($conDLA,$con,$table,$sql){

		$tableDLA = "region";
		
		$somethingDLA = "name";
		$something1 = "campaign_sales_office";
		$something2 = "sales_representant_office";

		$fromDLA = array("name");
		$from1 = array($something1);
		$from2 = array($something2);


		$distinctDLA = $this->getDistinct($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA);
		$distinctFM1 = $this->getDistinct($con,$something1,$table,$sql,$from1);
		$distinctFM2 = $this->getDistinct($con,$something2,$table,$sql,$from2);

		$tmp = array_merge($distinctFM1,$distinctFM2);
		$distinctFM = array_values(array_unique($tmp));

		$new = $this->checkDifferences($distinctDLA,$distinctFM);

		return $new;
		
	}

	public function checkNewBrands($conDLA,$con,$table,$sql){

		$tableDLA = "brand_unit";
		
		$somethingDLA = "name";
		$something = "brand";

		$fromDLA = array("name");
		$from = array($something);

		$distinctDLA = $this->getDistinct($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA);
		$distinctFM = $this->getDistinct($con,$something,$table,$sql,$from);

		$new = $this->checkDifferences($distinctDLA,$distinctFM);

		return $new;

	}

	public function checkNewSalesReps($conDLA,$con,$table,$sql){
		$tableDLA = "sales_rep_unit";
		
		$somethingDLA = "name";
		$something = "sales_rep";

		$fromDLA = array("name");
		$from = array($something);

		$distinctDLA = $this->getDistinct($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA);
		$distinctFM = $this->getDistinct($con,$something,$table,$sql,$from);

		$new = $this->checkDifferences($distinctDLA,$distinctFM);

		return $new;
	}

	public function checkNewClients($conDLA,$con,$table,$sql){
		return false;
	}

	public function checkNewAgencies($conDLA,$con,$table,$sql){
		return false;
	}

	public function checkNewCurrencies($conDLA,$con,$table,$sql){
		$tableDLA = "currency";
		
		$somethingDLA = "name";
		$something = "campaign_currency";

		$fromDLA = array("name");
		$from = array($something);

		$distinctDLA = $this->getDistinct($conDLA,$somethingDLA,$tableDLA,$sql,$fromDLA);
		$distinctFM = $this->getDistinct($con,$something,$table,$sql,$from);

		$new = $this->checkDifferences($distinctDLA,$distinctFM);
		return $new;
	}

	public function getDistinct($con,$something,$table,$sql,$from){

		$select = "SELECT DISTINCT $something FROM $table ORDER BY $something";
		$res = $con->query($select);
		$tmp = $sql->fetch($res,$from,$from);
		
		for ($t=0; $t < sizeof($tmp); $t++) { 
			for ($f=0; $f < sizeof($from); $f++) { 
				$distinct[$t] = $tmp[$t][$from[$f]];
			}
		}
		
		return $distinct;
	}

	public function checkDifferences($dla,$fm){
		$new = array();		

		for ($f=0; $f < sizeof($fm); $f++) { 
			$check = false;
			for ($d=0; $d < sizeof($dla); $d++) { 
				if($fm[$f] == $dla[$d]){
					$check = true;
					break;
				}
			}

			if(!$check){
				$new[] = $fm[$f];
			}
		}

		if(empty($new)){
			$rtr = false;
		}else{
			$rtr = $new;
		}

		return $rtr;
	}/*

	public function insertKeysOnArray($key,$new){

		$rtr = array();

		for ($n=0; $n < sizeof($new); $n++) { 
			$rtr[$key][$n] = $new[$n];
		}

		return $rtr;

	}*/

}

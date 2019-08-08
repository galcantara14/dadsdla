<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;
use App\region;

class rankingBrand extends rank{

	//$con, $tableName, $leftName, $type, $brands, $region, $value, $years, $months, $currency, $order_by, $leftName2=null
	public function getAllResults($con, $r, $region, $brands, $value, $months, $currency, $years) {

		$order_by = " (
							CASE brandID

							WHEN '1'
							THEN 1

							WHEN '2'
							THEN 2

							WHEN '3'
							THEN 3

							WHEN '4'
							THEN 4

							WHEN '5'
							THEN 5

							WHEN '6'
							THEN 6

							WHEN '7'
							THEN 7

							WHEN '8'
							THEN 8

							WHEN '9'
							THEN 9

							WHEN '10'
							THEN 10

							WHEN '11'
							THEN 11	

							WHEN '12'
							THEN 12

							END						
						)";

		$sql = new sql();

		for ($l=0; $l < 2; $l++) {
			
			for ($y=0; $y < sizeof($years); $y++) {

				$info = $this->mountValues($con, $r, $region, $years[$y]);

				for ($b=0; $b < sizeof($brands); $b++) {
				
					if ($l == 0) {
						if ($b == 1) {
							$table = "digital";
						}else{
							$table = $info['table'];
						}
					}else{
						$table = 'plan_by_brand';
					}

					$infoQuery[$b] = $this->getAllValuesUnion($table, $info['leftName'], "brand", $brands[$b], $region, $value, $months, $currency);

					//var_dump("infoQuery", $infoQuery[$b]);
				}

				if (sizeof($brands) > 1) {
					for ($b=0; $b < sizeof($brands); $b++) {
						array_push($infoQuery[$b]['colsValue'], $years[$y]);
						$where[$b] = $sql->where($infoQuery[$b]['columns'], $infoQuery[$b]['colsValue']);
					}

					$values[$y] = $sql->selectWithUnion($con, $where, $infoQuery, $infoQuery[0]['name'], $order_by, "ASC");

					for ($b=0; $b < sizeof($brands); $b++) { 
						array_pop($infoQuery[$b]['colsValue']);
					}
				}else{
					array_push($infoQuery[0]['colsValue'], $years[$y]);
					$where = $sql->where($infoQuery[0]['columns'], $infoQuery[0]['colsValue']);
					$values[$y] = $sql->selectGroupBy($con, $infoQuery[0]['columns'], $infoQuery[0]['table'], $infoQuery[0]['join'], $where, $order_by, $infoQuery[0]['name'], "ASC");
					array_pop($infoQuery[0]['colsValue']);
				}

				$from = $infoQuery[0]['names'];
				$res[$y] = $sql->fetch($values[$y], $from, $from);
			}

			//var_dump($res);
			$line[$l] = $res;

			if ($l != 0) {
				array_pop($line[$l]);
			}

			//var_dump($line[$l]);
		}

		return $line;

	}

	public function mountBrands($brands){
		
		$brandsTV = array();
		$brandsDigital = array();

		for ($b=0; $b < sizeof($brands); $b++) {
			if ($brands[$b][1] == "DC" || $brands[$b][1] == "HH" || $brands[$b][1] == "DK" || $brands[$b][1] == "AP" 
				|| $brands[$b][1] == "TLC"|| $brands[$b][1] == "ID" || $brands[$b][1] == "DT" || $brands[$b][1] == "FN" 
				|| $brands[$b][1] == "OTH" || $brands[$b][1] == "HGTV") {
				array_push($brandsTV, $brands[$b]);
			}else{
				array_push($brandsDigital, $brands[$b]);
			}
		}

		return array($brandsTV, $brandsDigital);
	}

	public function mountValues($con, $r, $regionID, $year){

		$tmp = $r->getRegion($con,array($regionID));

		if(is_array($tmp)){
            $rtr['region'] = $tmp[0]['name'];
        }else{
            $rtr['region'] = $tmp['name'];
        }

        $cYear = intval(date('Y'));

		if ($rtr['region'] == "Brazil" && ($year == $cYear)) {
			$rtr['table'] = "cmaps";
		}else{
			$rtr['table'] = "ytd";
		}

		$rtr['leftName'] = "brand";

		return $rtr;

	}

	public function assembler($values, $years, $brands){
		//var_dump($values[1]);
		$mtx[0][0] = "Brand";
		$mtx[1][0] = "Closed ".$years[0];
		$mtx[2][0] = "Plan ".$years[0];
		$mtx[3][0] = $years[1];
		$mtx[4][0] = "Share Closed";
		$mtx[5][0] = "Share Plan";
		$mtx[6][0] = "Share ".$years[1];
		$mtx[7][0] = "%(Closed / Plan)";
		$mtx[8][0] = "(Closed - Plan)";

		$closed = 0;
		$plan = 0;
		$pClosed = 0;

		for ($b=0; $b < sizeof($brands); $b++) { 
			$mtx[0][$b+1] = $brands[$b][1];

			if ($b < sizeof($values[0][0])) {
				$val = $values[0][0][$b]['total'];
			}else{
				$val = "-";
			}

			$mtx[1][$b+1] = $val;
			
			if ($val != "-") {
				$closed += $val;	
			}

			if ($b < sizeof($values[1][0])) {
				$val = $values[1][0][$b]['total'];
			}else{
				$val = "-";
			}

			$mtx[2][$b+1] = $val;

			if ($val != "-") {
				$plan += $val;	
			}

			if ($b < sizeof($values[0][1])) {
				$val = $values[0][1][$b]['total'];
			}else{
				$val = "-";
			}

			$mtx[3][$b+1] = $val;

			if ($val != "-") {
				$pClosed += $val;	
			}
		}

		array_push($mtx[0], "DN");
		array_push($mtx[1], $closed);
		array_push($mtx[2], $plan);
		array_push($mtx[3], $pClosed);

		for ($b=0; $b < sizeof($brands); $b++) { 
			
			if ($mtx[1][$b+1] != "-") {
				$val = ($mtx[1][$b+1] / $closed)*100;
			}else{
				$val = "-";
			}

			$mtx[4][$b+1] = $val;

			if ($mtx[2][$b+1] != "-") {
				$val = ($mtx[2][$b+1] / $closed)*100;
			}else{
				$val = "-";
			}

			$mtx[5][$b+1] = $val;

			if ($mtx[3][$b+1] != "-") {
				$val = ($mtx[3][$b+1] / $closed)*100;
			}else{
				$val = "-";
			}

			$mtx[6][$b+1] = $val;

			if ($mtx[1][$b+1] != "-" && $mtx[2][$b+1] != "-") {
				$val = ($mtx[1][$b+1] / $mtx[2][$b+1])*100;
				$val2 = ($mtx[1][$b+1] - $mtx[2][$b+1]);
			}else{
				$val = "-";
				$val2 = "-";
			}

			$mtx[7][$b+1] = $val;
			$mtx[8][$b+1] = $val2;
		}

		for ($c=4; $c < 7; $c++) { 
			array_push($mtx[$c], 100);
		}

		$size = sizeof($mtx[0]);

		$val = ($closed / $plan)*100;
		array_push($mtx[7], $val);

		$val = ($closed - $plan);
		array_push($mtx[8], $val);

		return $mtx;
	}

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\sql;
use App\salesRep;

class quarterPerformance extends performance {
    
	public function makeQuarter($con, $regionID, $year, $brands, $currencyID, $value, $months, $salesRepGroupID, $salesRepID, $tiers, $salesRepGroup, $salesRep){

		$sql = new sql();

		$sr = new salesRep();

		$salesRepGroup = $sr->getSalesRepGroupById($con, $salesRepGroupID);
        $salesRep = $sr->getSalesRepById($con, $salesRepID);

		for ($b=0; $b < sizeof($brands); $b++) { 
			for ($m=0; $m < sizeof($months); $m++) { 
				if ($brands[$b][1] != 'ONL' && $brands[$b][1] != 'VIX') {
					$table[$b][$m] = "ytd";
				}else{
					$table[$b][$m] = "digital";
				}

				$where[$b][$m] = $this->generateColumns($value);

				$values[$b][$m] = $this->generateValue($con, $sql, $regionID, $year, $brands[$b], $salesRep, $months[$m][1], $where[$b][$m], $table[$b][$m]);
				$planValues[$b][$m] = $this->generateValue($con, $sql, $regionID, $year, $brands[$b], $salesRep, $months[$m], "value", "plan_by_sales");

			}
		}

		//var_dump($salesRepGroup);
		$mtx = $this->assembler($values, $planValues, $salesRep, $months, $brands, $salesRepGroup, $tiers, $year, $salesRepGroup, $salesRep);

		return $mtx;

	}

	public function remakeArray($brandsTiers){
		
		for ($b=0; $b < sizeof($brandsTiers); $b++) { 
			if (($b == (sizeof($brandsTiers)-1)) && empty($brandsTiers[$b])) {
				array_pop($brandsTiers);
			}elseif (empty($brandsTiers[$b])) {
				for ($b2=$b; $b2 < (sizeof($brandsTiers)-1); $b2++) { 
					//var_dump($b2);
					$brandsTiers[$b2] = $brandsTiers[$b2+1];	
				}
				array_pop($brandsTiers);
			}else{

			}
		}

		return $brandsTiers;
	}

	public function assembler($values, $planValues, $salesRep, $months, $brands, $salesRepGroup, $tiers, $year, $salesRepGroupN, $salesRepN){

		//$mtx["salesRepGroup"] = $salesRepGroupN;
		//$mtx["salesRep"] = $salesRep;

		//separando as marcas por tiers
		$brandsTiers = array(0, 1, 2);
		$newPlanValues = array(0, 1, 2);
		$newValues = array(0, 1, 2);

		for ($b=0; $b < sizeof($brandsTiers); $b++) { 
			$brandsTiers[$b] = array();
			$newPlanValues[$b] = array();
			$newValues[$b] = array();
		}

		for ($b=0; $b < sizeof($brands); $b++) { 
			if ($brands[$b][1] == "DC" || $brands[$b][1] == "HH" || $brands[$b][1] == "DK") {
				array_push($brandsTiers[0], $brands[$b][1]);
				array_push($newPlanValues[0], $planValues[$b]);
				array_push($newValues[0], $values[$b]);
			}elseif ($brands[$b][1] == "AP" 
					|| $brands[$b][1] == "TLC"
					|| $brands[$b][1] == "ID"
					|| $brands[$b][1] == "DT"
					|| $brands[$b][1] == "FN"
					|| $brands[$b][1] == "ONL"
					|| $brands[$b][1] == "VIX"
					|| $brands[$b][1] == "HGTV") {
				array_push($brandsTiers[1], $brands[$b][1]);
				array_push($newPlanValues[1], $planValues[$b]);
				array_push($newValues[1], $values[$b]);
			}else{
				array_push($brandsTiers[2], $brands[$b][1]);
				array_push($newPlanValues[2], $planValues[$b]);
				array_push($newValues[2], $values[$b]);
			}
		}

		//arrumando vetor, caso haja tiers em branco
		for ($b=0; $b < sizeof($brandsTiers); $b++) { 
			$brandsTiers = $this->remakeArray($brandsTiers);
			$newPlanValues = $this->remakeArray($newPlanValues);
			$newValues = $this->remakeArray($newValues);
		}

		/*for ($i=0; $i < sizeof($newValues); $i++) { 
			var_dump($brandsTiers[$i]);
		}*/
		
		//criando valores texto da matriz
		for ($t=0; $t < sizeof($brandsTiers); $t++) { 
			for ($b=0; $b < sizeof($brandsTiers[$t]); $b++) { 
				$mtx[$t][$b][0][0] = $brandsTiers[$t][$b];
				$mtx[$t][$b][1][0] = " ";
				$mtx[$t][$b][1][1] = "Target ".$year;
				$mtx[$t][$b][1][2] = "Actual ".$year;
				$mtx[$t][$b][1][3] = "Var.Abs";
				$mtx[$t][$b][1][4] = "Var(%)";
				$mtx[$t][$b][2][0] = "Q1";
				$mtx[$t][$b][3][0] = "Q2";
				$mtx[$t][$b][4][0] = "S1";
				$mtx[$t][$b][5][0] = "Q3";
				$mtx[$t][$b][6][0] = "Q4";
				$mtx[$t][$b][7][0] = "S2";
				$mtx[$t][$b][8][0] = "Total";
			}

		}
		//var_dump($mtx[0]);

		for ($t=0; $t < sizeof($mtx); $t++) { 
			for ($b=0; $b < sizeof($mtx[$t]); $b++) { 
				for ($v=2; $v < sizeof($mtx[$t][$b]); $v++) { 
					for ($v2=1; $v2 < 5; $v2++) {
						$mtx[$t][$b][$v][$v2] = 0;	
					}
				}	
			}
			//var_dump($mtx[$t]);
		}
		
		//var_dump($values);
		//var_dump($tiers);
		//pegando valores das linhas 1 e 2, da matriz, menos do total
		for ($t=0; $t < sizeof($newValues); $t++) { 
			for ($b=0; $b < sizeof($newValues[$t]); $b++) { 
				for ($m=0; $m < sizeof($newValues[$t][$b]); $m++) { 
					for ($s=0; $s < sizeof($newValues[$t][$b][$m]); $s++) {
						if ($m == 0 || $m == 1 || $m == 2) {
							$v = 2;
							
							if (is_null($newPlanValues[$t][$b][$m][$s])) {
								$mtx[$t][$b][$v][1] += 0;
							}else{
								$mtx[$t][$b][$v][1] += $newPlanValues[$t][$b][$m][$s];
							}

							if (is_null($newValues[$t][$b][$m][$s])) {
								$mtx[$t][$b][$v][2] += 0;
							}else{
								$mtx[$t][$b][$v][2] += $newValues[$t][$b][$m][$s];	
							}
						}elseif ($m == 3 || $m == 4 || $m == 5) {
							$v = 3;

							if (is_null($newPlanValues[$t][$b][$m][$s])) {
								$mtx[$t][$b][$v][1] += 0;
							}else{
								$mtx[$t][$b][$v][1] += $newPlanValues[$t][$b][$m][$s];
							}

							if (is_null($newValues[$t][$b][$m][$s])) {
								$mtx[$t][$b][$v][2] += 0;
							}else{
								$mtx[$t][$b][$v][2] += $newValues[$t][$b][$m][$s];	
							}

							$v = 4;

							$mtx[$t][$b][$v][1] += $mtx[$t][$b][$v-1][1] + $mtx[$t][$b][$v-2][1];
							$mtx[$t][$b][$v][2] += $mtx[$t][$b][$v-1][2] + $mtx[$t][$b][$v-2][2];

						}elseif ($m == 6 || $m == 7 || $m == 8) {
							$v = 5;

							if (is_null($newPlanValues[$t][$b][$m][$s])) {
								$mtx[$t][$b][$v][1] += 0;
							}else{
								$mtx[$t][$b][$v][1] += $newPlanValues[$t][$b][$m][$s];
							}

							if (is_null($newValues[$t][$b][$m][$s])) {
								$mtx[$t][$b][$v][2] += 0;
							}else{
								$mtx[$t][$b][$v][2] += $newValues[$t][$b][$m][$s];	
							}							
						}else{
							$v = 6;

							if (is_null($newPlanValues[$t][$b][$m][$s])) {
								$mtx[$t][$b][$v][1] += 0;
							}else{
								$mtx[$t][$b][$v][1] += $newPlanValues[$t][$b][$m][$s];
							}

							if (is_null($newValues[$t][$b][$m][$s])) {
								$mtx[$t][$b][$v][2] += 0;
							}else{
								$mtx[$t][$b][$v][2] += $newValues[$t][$b][$m][$s];	
							}

							$v = 7;

							$mtx[$t][$b][$v][1] += $mtx[$t][$b][$v-1][1] + $mtx[$t][$b][$v-2][1];
							$mtx[$t][$b][$v][2] += $mtx[$t][$b][$v-1][2] + $mtx[$t][$b][$v-2][2];
						}
					}
				}
			}

			//var_dump($mtx[$t]);
		}

		//var_dump($mtx[1]);
		for ($t=0; $t < sizeof($mtx); $t++) { 
			for ($b=0; $b < sizeof($mtx[$t]); $b++) { 
				for ($v=2; $v < sizeof($mtx[$t][$b]); $v++) { 
					for ($v2=1; $v2 < sizeof($mtx[$t][$b][$v]); $v2++) {
						if ($v2 == 3) {
							$mtx[$t][$b][$v][$v2] = $mtx[$t][$b][$v][2] - $mtx[$t][$b][$v][1];
						}elseif ($v2 == 4) {
							if ($mtx[$t][$b][$v][1] != 0) {
								$mtx[$t][$b][$v][$v2] = $mtx[$t][$b][$v][2] / $mtx[$t][$b][$v][1];
							}else{
								$mtx[$t][$b][$v][$v2] = 0;
							}
						}elseif ($v != 8) {
							$mtx[$t][$b][8][$v2] += $mtx[$t][$b][$v][$v2];	
						}else{

						}
					}
				}	
			}

			//var_dump($mtx[$t]);
		}
		

		/*$mtx["tiers"] = $tiers;
        $mtx["brands"] = $brands;

		$semester = 1;
		$quarter = 1;
		for ($t=0; $t < 8; $t++) { 
			
			if ($t == 0) {
				$mtx["title"][$t] = " ";	
			}elseif ($t == 3 || $t == 6) {
				$mtx["title"][$t] = "S".$semester;
				$semester++;
			}elseif ($t == 7) {
				$mtx["title"][$t] = "Total";
			}else{
				$mtx["title"][$t] = "Q".$quarter;
				$quarter++;
			}
		}

		for ($b=0; $b < sizeof($brands); $b++) { 
			for ($m=1; $m < 8; $m++) { 
				for ($s=0; $s < sizeof($salesRep); $s++) { 
					$tmpPlanValues[$s][$b][0] = "Target ".$year;
					$tmpPlanValues[$s][$b][$m] = 0;
					$tmpValues[$s][$b][0] = "Actual ".$year;
					$tmpValues[$s][$b][$m] = 0;
				}
			}
		}

		for ($b=0; $b < sizeof($brands); $b++) {
			for ($m=0; $m < sizeof($months); $m++) { 
				for ($s=0; $s < sizeof($salesRep); $s++) {
					if ($m == 0 || $m == 1 || $m == 2) {
						$q = 1;
						$tmpPlanValues[$s][$b][$q] += $planValues[$b][$m][$s];
						$tmpValues[$s][$b][$q] += $values[$b][$m][$s];
					}elseif ($m == 3 || $m == 4 || $m == 5) {
						$q = 2;
						$tmpPlanValues[$s][$b][$q] += $planValues[$b][$m][$s];
						$tmpValues[$s][$b][$q] += $values[$b][$m][$s];

						$q++;
						$tmpPlanValues[$s][$b][$q] = $tmpPlanValues[$s][$b][$q-1] + $tmpPlanValues[$s][$b][$q-2];
						$tmpValues[$s][$b][$q] = $tmpValues[$s][$b][$q-1] + $tmpValues[$s][$b][$q-2];
					}elseif ($m == 6 || $m == 7 || $m == 8) {
						$q = 4;
						$tmpPlanValues[$s][$b][$q] += $planValues[$b][$m][$s];
						$tmpValues[$s][$b][$q] += $values[$b][$m][$s];
					}elseif ($m == 9 || $m == 10 || $m == 11) {
						$q = 5;
						$tmpPlanValues[$s][$b][$q] += $planValues[$b][$m][$s];
						$tmpValues[$s][$b][$q] += $values[$b][$m][$s];

						$q++;
						$tmpPlanValues[$s][$b][$q] = $tmpPlanValues[$s][$b][$q-1] + $tmpPlanValues[$s][$b][$q-2];
						$tmpValues[$s][$b][$q] = $tmpValues[$s][$b][$q-1] + $tmpValues[$s][$b][$q-2];
					}
					
					$tmpPlanValues[$s][$b][7] += $tmpPlanValues[$s][$b][$q];
					$tmpValues[$s][$b][7] += $tmpValues[$s][$b][$q];

				}
			}
		}

		$mtx["planValues"] = $tmpPlanValues;
		$mtx["values"] = $tmpValues;
        
		for ($s=0; $s < sizeof($mtx["values"]); $s++) { 
			for ($b=0; $b < sizeof($mtx["values"][$s]); $b++) { 
				for ($v=1; $v < sizeof($mtx["values"][$s][$b]); $v++) { 
					$varAbs[$s][$b][0] = "Var. Abs";
					$varAbs[$s][$b][$v] = $tmpValues[$s][$b][$v] - $tmpPlanValues[$s][$b][$v];

					$var[$s][$b][0] = "Var(%)";

					if ($tmpPlanValues[$s][$b][$v] != 0) {
						$var[$s][$b][$v] = $tmpValues[$s][$b][$v] / $tmpPlanValues[$s][$b][$v];
					}else{
						$var[$s][$b][$v] = 0;
					}
				}
			}
		}


		$mtx["varAbs"] = $varAbs;
		$mtx["var"] = $var;*/
		
		/*Matrix Final*/
		
		/*$mtxFinal["salesRepGroup"] = "";

		if (sizeof($mtx["salesRepGroup"]) == sizeof($salesRepGroupN)) {
			$mtxFinal["salesRepGroup"] .= "All";
		}else{
			for ($srg=0; $srg < sizeof($mtx["salesRepGroup"]); $srg++) { 
				$mtxFinal["salesRepGroup"] .= $mtx["salesRepGroup"][$srg]['name'];

				if ($srg != sizeof($mtx["salesRepGroup"])-1) {
					$mtxFinal["salesRepGroup"] .= ",";
				}
			}	
		}
		
		$mtxFinal["salesRep"] = "";
		
		if (sizeof($mtx["salesRep"]) == sizeof($salesRepN)) {
			$mtxFinal["salesRep"] .= "All";
		}else{
			for ($sr=0; $sr < sizeof($mtx["salesRep"]); $sr++) { 
				$mtxFinal["salesRep"] .= $mtx["salesRep"][$sr]['salesRep'];

				if ($sr != sizeof($mtx["salesRep"])-1) {
					$mtxFinal["salesRep"] .= ",";
				}
			}	
		}

		$mtxFinal["tiers"] = $mtx["tiers"];
		$mtxFinal["brands"] = $mtx["brands"];
		$mtxFinal["title"] = $mtx["title"];

		for ($b=0; $b < sizeof($brands); $b++) { 
			for ($v=1; $v < 8; $v++) { 
				$totalPlanValues[$b][0] = "Target ".$year;
				$totalPlanValues[$b][$v] = 0;

				$totalValues[$b][0] = "Actual ".$year;
				$totalValues[$b][$v] = 0;
			}
		}

		for ($s=0; $s < sizeof($mtx["values"]); $s++) { 
			for ($b=0; $b < sizeof($mtx["values"][$s]); $b++) { 
				for ($v=1; $v < sizeof($mtx["values"][$s][$b]); $v++) { 
					$totalPlanValues[$b][$v] += $mtx["planValues"][$s][$b][$v];
					$totalValues[$b][$v] += $mtx["values"][$s][$b][$v];
				}
			}
		}

		$mtxFinal["planValues"] = $totalPlanValues;
		$mtxFinal["values"] = $totalValues;

        //var_dump($mtxFinal);
        //var_dump($salesRep);
		//var_dump($mtxFinal);*/

		return $mtx;
	}
}

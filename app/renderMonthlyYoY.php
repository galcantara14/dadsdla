<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class renderMonthlyYoY extends Model{
    
	public function assemble($mtx,$quarters,$form,$pRate,$value,$year,$months,$brands){
		
		echo "<table style='width: 100%; zoom:80%;'>";

			echo "<th class='dc center' colspan='13'>";
				echo "<span style='font-size:22px;''>";
					echo "$form to Monthly Year Over Year : (".strtoupper($pRate[0]['name'])."/".strtoupper($value).")";
				echo "</span>";
			echo "</th>";

			echo "<tr><td></td></tr>";
		for($i = 0, $j = 0; $i < sizeof($months); $i+=3, $j++){
			echo "<tr>";

			echo "<tr>";
                $this->renderHead($months, $i, $j, "dc", "vix", "darkBlue");
            echo "</tr>";
           	echo "<tr>";
           		$this->renderHead2($year, $i, "dc", "vix", "darkBlue");
           	echo "</tr>";
            for($b = 0; $b < sizeof($brands); $b++){
            	echo "<tr>";
            		$this->renderData($brands[$b], $mtx, $quarters[$j], $i, $b, "dc", "rcBlue", "month", "medBlue");
        		echo "</tr>";
            }
            echo "</tr>";

			if($i != (sizeof($months)-1)){
				echo "<tr><td>&nbsp;</td></tr>";
			}
		}

		echo "</table>";

	}

	public function renderHead($months, $size, $index, $firstColor, $secondColor, $thirdColor){
		
		$firstClass = "class='center ".$firstColor."' style='font-size: 18px'";
		$secondClass = "class='center ".$secondColor."' style='font-size: 18px'";
		$thirdClass = "class='center ".$thirdColor."' style='font-size: 18px'";

		echo "<td $firstClass>&nbsp;</td>";

		for ($i = $size, $j=0; $i < ($size+3); $i++, $j++) {
			
			if ($j == 0) {
				$class = $firstClass;
			}elseif($j == 1){
				$class = $secondClass;
			}else{
				$class = $firstClass;
			}

			echo "<td colspan='3' $class>".$months[$i][0]."</td>";
		}

		echo "<td colspan='3' $thirdClass>Q".($index+1)."</td>";

	}

    public function renderHead2($year, $size, $firstColor, $secondColor, $thirdColor){

    	$firstClass = "class='center ".$firstColor."'";
		$secondClass = "class='center ".$secondColor."'";
		$thirdClass = "class='center ".$thirdColor."'";

    	echo "<td $firstClass>&nbsp;</td>";

		for ($i = $size, $j=0; $i <= ($size+3); $i++, $j++) {

			if ($j == 0) {
				$class = $firstClass;
			}elseif($j == 1){
				$class = $secondClass;
			}else{
				$class = $firstClass;
			}

			if ($i == ($size+3)) {
				$class = $thirdClass;
			}

			echo "<td $class>Real".($year-1)."</td>";
			echo "<td $class>Target".$year."</td>";
			echo "<td $class>Real".$year."</td>";
		}

    }

    public function renderData($brand, $matrix, $quarter, $month, $brandPos, $firstColor, $secondColor, $thirdColor, $fourthColor){
    	
    	$firstClass = "class='center ".$firstColor."'";
		$secondClass = "class='center ".$secondColor."'";
		$thirdClass = "class='center ".$thirdColor."' style='font-weight: bold;'";
		$fourthClass = "class='center ".$fourthColor."'";

		echo "<td $firstClass>".$brand[1]."</td>";

		for ($i=$month; $i < ($month+3); $i++) { 
			
			for ($j=0; $j < 3; $j++) {

				if ($j == 0) {
					$class = $secondClass;
				}elseif ($j == 1) {
					$class = $thirdClass;
				}else{
					$class = $fourthClass;
				}

				echo "<td $class>".number_format($matrix[$brandPos][$j][$i+1])."</td>";
			}
		}
		//var_dump($quarter);
		for ($i=0; $i < 3; $i++) { 

			if ($i == 0) {
				$class = $secondClass;
			}elseif ($i == 1) {
				$class = $thirdClass;
			}else{
				$class = $fourthClass;
			}

			echo "<td $class>".number_format($quarter[$i][$brandPos+1])."</td>";
		}

    }

    public function assembleModal($brands, $quarters, $year){
    	echo "<table style='width: 100%; zoom:100%;' class='table-responsive'>";
	    	echo "<tr>";
	    		$this->renderModalHeader("dc", "darkBlue");
			echo "</tr>";
	        
	        echo "<tr>";
	        	$this->renderModalHeader2($year, "dc", "darkBlue");
	    	echo "</tr>";

			for($i = 0; $i < sizeof($brands); $i++){
	            echo "<tr>";
	                $this->renderDataModal($brands[$i], $quarters, $i, "dc", "rcBlue", "white", "medBlue");
	            echo "</tr>";
	        }
        echo "</table>";

    }

    public function renderModalHeader($firstcolor, $secondColor){
    	
    	$firstClass = "class='center ".$firstcolor."'";
    	$secondClass = "class='center ".$secondColor."'";
    	$style = "style='font-size: 18px;'";

    	echo "<td $firstClass>&nbsp;</td>";

    	for ($i=0; $i < 2; $i++) { 
    		echo "<td $firstClass colspan='3'>S".$i."</td>";
    	}

    	echo "<td $secondClass $style colspan='3'>TOTAL</td>";
    }

    public function renderModalHeader2($year, $firstcolor, $secondColor){
    	
    	$firstClass = "class='center ".$firstcolor."'";
    	$secondClass = "class='center ".$secondColor."'";
    	$style = "style='font-size: 14px;'";
    	echo "<td $firstClass>&nbsp;</td>";

    	for ($i=0; $i < 3; $i++) {

    		if ($i == 2) {
    			$class = $secondClass;
    		}else{
    			$class = $firstClass;
    		}

    		echo "<td $class $style colspan='1'>Real ".($year-1)."</td>";
			echo "<td $class $style colspan='1'>Target ".$year."</td>";
			echo "<td $class $style colspan='1'>Real ".$year."</td>";
    	}
    }

    public function renderDataModal($brand, $quarter, $brandPos, $firstColor, $secondColor, $thirdColor, $fourthColor){
    	
    	$firstClass = "class='center ".$firstColor."'";
		$secondClass = "class='center ".$secondColor."'";
		$thirdClass = "class='center ".$thirdColor."' style='font-weight: bold;'";
		$fourthClass = "class='center ".$fourthColor."'";

		echo "<td $firstClass>".$brand[1]."</td>";
		
		for ($j=0; $j < 3; $j++) { 

			if ($j == 0) {
				$class = $secondClass;
			}elseif ($j == 1) {
				$class = $thirdClass;
			}else{
				$class = $fourthClass;
			}

			echo "<td $class colspan='1'>".number_format(($quarter[0][$j][$brandPos+1]+$quarter[1][$j][$brandPos+1]))."</td>";
		}
		
		for ($j=0; $j < 3; $j++) { 

			if ($j == 0) {
				$class = $secondClass;
			}elseif ($j == 1) {
				$class = $thirdClass;
			}else{
				$class = $fourthClass;
			}

			echo "<td $class colspan='1'>".number_format(($quarter[2][$j][$brandPos+1]+$quarter[3][$j][$brandPos+1]))."</td>";
		}
		for ($i=0; $i < 3; $i++) { 

			if ($i == 0) {
				$class = $secondClass;
			}elseif ($i == 1) {
				$class = $thirdClass;
			}else{
				$class = $fourthClass;
			}

			echo "<td $class colspan='1'>".number_format(
				(
					$quarter[0][$i][$brandPos+1]+$quarter[1][$i][$brandPos+1]+
					$quarter[2][$i][$brandPos+1]+$quarter[3][$i][$brandPos+1]
				)
				).
				"</td>";
		}

    }
}

				/*<tr>{{ $renderMonthlyYoY->renderModalHeader("dc", "darkBlue") }}</tr>
                        <tr>{{ $renderMonthlyYoY->renderModalHeader2($year, "dc", "darkBlue")}}</tr>

						@for($i = 0; $i < sizeof($brands); $i++)
                            <tr>
                                {{
                                    $renderMonthlyYoY->renderDataModal($brands[$i], $matrix[1], $i, "dc", "rcBlue", "white", "medBlue") 
                                }}
                            </tr>
                        @endfor*/
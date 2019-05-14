<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class shareRender extends Render{

    public function mtx($mtx){
   		echo "<table>";
			echo "<tr class='lightBlue'>";
				echo "<th colspan='".(sizeof($mtx["brand"]) + 4)."'>Share (".$mtx["source"].") - Sales Group: ".$mtx["salesRepGroup"]." - (".$mtx["currency"]."/".$mtx["value"].")</th>";
			echo "</tr>";
			echo "<tr class='lightBlue'>";
				echo "<th colspan='".(sizeof($mtx["brand"]) + 4)."'> Sales Representative: ".$mtx["salesRepView"]." </th>";
			echo "</tr>";
			echo "<tr class='lightBlue'>";
				echo "<th style='width:180px'>Sales Rep</th>";
				for ($b=0; $b <sizeof($mtx["brand"]) ; $b++) { 
    	 			echo "<th >";
    	 				echo $mtx["brand"][$b];
	    			echo "</th>";
		    	}
	    		if ($mtx["dn"]) {
		    		echo "<th >DN</th>";
		    	}
				echo "<th>Share</th>";
	    	echo "</tr>";
	    	///Começa o Corpo do Codigo
	    	for ($s=0; $s <sizeof($mtx["salesRep"]); $s++) { 
		    	if ($s%2 == 1) {
		    		$string = "odd";
		    	}else{
		    		$string = "rcBlue";
		    	}
	    		echo "<tr class='".$string."'>";
    				echo "<td  >".$mtx["salesRep"][$s]."</td>";
	    			for ($b=0; $b <sizeof($mtx["brand"]); $b++) { 
	    				echo "<td>".(number_format($mtx["values"][$b][$s],2))."</td>";
	    			}
	    			if ($mtx["dn"]) {
		    			echo "<td>".(number_format($mtx["dn"][$s],2))."</td>";
	    			}
		    		echo "<td>".(number_format($mtx["share"][$s],2))."%</td>";
	    		echo "</tr>";
	    	}
	    	//Começa o total
	    	echo "<tr class='darkBlue'>";
		    	echo "<td  >Total</td>";
	    		for ($b=0; $b <sizeof($mtx["brand"]); $b++) { 
	    			echo "<td>".(number_format($mtx["total"][$b],2))."</td>";
	    		}

	    		if ($mtx["dn"]) {
		    		echo "<td>".number_format($mtx["totalT"],2)."</td>";
		    	}
		    	echo "<td>100%</td>";

	    	echo "</tr>";
		echo "</table>";

    }
}
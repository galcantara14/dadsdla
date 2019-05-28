<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class corePerformanceRender extends renderPerformance
{
    public function coreMatrix($mtx){
	    for ($sg=0; $sg < sizeof($mtx["value"]); $sg++) {
	    	if ($sg%2 == 0) {
	    		echo "<div class='row'>";
	    	}
	    	echo "<div class='col'>";
	    	echo "<table  border='1' style='width:100%; margin-top:1.5%;'><th>".$mtx["salesGroup"][$sg]["name"]."</th></table>";
	    	for ($t=0; $t < sizeof($mtx["value"][$sg]); $t++) { 
		    	echo "<table border='1' style='width: 100%;' class='mt-3'>";
		    		echo "<tr>";
		    			echo "<td rowspan='5' style='width:3%;'>".$mtx["tier"][$t]."</td>";
		    			echo "<td style='width:7%;'></td>";
		    			for ($m=0; $m <sizeof($mtx["quarters"]); $m++) { 
		    				echo "<td style='width:18%;' >".$mtx["quarters"][$m]."</td>";
		    			}
		    			echo "<td style='width:18%;' >Total</td>";
		    		echo "</tr>";
		    		echo "<tr>";
		    			echo "<td>Meta</td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td>".number_format($mtx["planValue"][$sg][$t][$q],0)."</td>";
		    			}
		    			echo "<td>".number_format($mtx["totalPlanValueTier"][$sg][$t],0)."</td>";
		    		echo "</tr>";
		    		echo "<tr>";
		    			echo "<td>Real</td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td>".number_format($mtx["value"][$sg][$t][$q],0)."</td>";
		    			}
		    			echo "<td>".number_format($mtx["totalValueTier"][$sg][$t],0)."</td>";
		    		echo "</tr>";
		    		echo "<tr>";
		    			echo "<td>Var Abs</td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td>".number_format($mtx["varAbs"][$sg][$t][$q],0)."</td>";
		    			}
		    			echo "<td>".number_format($mtx["totalVarAbs"][$sg][$t],0)."</td>";
		    		echo "</tr>";
		    		echo "<tr>";
		    			echo "<td>Var %</td>";
		    			for ($q=0; $q <sizeof($mtx["quarters"]); $q++) { 
		    				echo "<td>".number_format($mtx["varPrc"][$sg][$t][$q],0)."</td>";
		    			}
		    			echo "<td>".number_format($mtx["totalVarPrc"][$sg][$t],0)."%</td>";
		    		echo "</tr>";
		    	echo "</table>";
	    	}
	    	echo "</div>";

	    	if ($sg%2 == 1) {
	    		echo "</div>";
	    	}
	    }
    }
}
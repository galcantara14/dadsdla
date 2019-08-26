<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Render;

class renderBrandRanking extends Render {
    
    public function assembler($mtx, $currency, $value, $region, $names){
		
        $currency = $currency[0]['name'];
        $value = strtoupper($value);
        
    	echo "<table style='width: 100%; zoom:100%; font-size: 16px;'>";
            echo "<tr>";
            	echo "<th colspan='15' class='lightBlue'><center>
                            <span style='font-size:18px;'> 
                                <b>$region - Brand Ranking (".strtoupper($names['source']).") : ($currency/$value)</b>
                            </span>
                        </center></th>";
            echo "</tr>";

            for ($m=0; $m < sizeof($mtx[0]); $m++) {
                
            	echo "<tr>";

            	if ($m == 0) {
        			$color = 'lightBlue';
        		}elseif ($m == sizeof($mtx[0])-1) {
        			$color = 'darkBlue';
        		}elseif ($m%2 == 0) {
        			$color = 'medBlue';
        		}else{
        			$color = 'rcBlue';
        		}

            	for ($n=0; $n < sizeof($mtx); $n++) { 
            		//var_dump($mtx[$n][$m]);
            		if ($m == 0) {
            			echo "<td class='$color center'> ".$mtx[$n][$m]." </td>";
            		}else{
            			if (is_numeric($mtx[$n][$m])) {
            				if ($n >= 4 && $n <= 7) {
            					echo "<td class='$color center'> ".number_format($mtx[$n][$m])." %</td>";	
            				}else{
            					echo "<td class='$color center'> ".number_format($mtx[$n][$m])." </td>";
            				}
            			}else{
            				if ($n == 0) {
            					echo "<td id='".$mtx[$n][$m]."' class='$color center'> ".$mtx[$n][$m]." </td>";
            				}else{
            					echo "<td class='$color center'> ".$mtx[$n][$m]." </td>";
            				}
            				
            			}
            		}
            	}

            	echo "</tr>";

            	echo "<tr>";
        			echo "<td class='$color' id='sub".$mtx[0][$m]."' style='display: none' colspan='".sizeof($mtx)."'></td>";
        		echo "</tr>";
            }

       echo "</table>";

    }
}

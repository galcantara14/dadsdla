<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class renderYoY extends Model {
    
    public function source($region){
    	echo "<select name='source' style='width:100%;'>";
            echo "<option value='ytd'> IBMS </option>";
            
            if ($region == 'Brazil') {
                echo "<option value='cmaps'> CMAPS </option>";
            }else{
                echo "<option value='mini_header'> Header </option>";//somente se for brasil a região selecionada
            }
    		
    	echo "</select>";	
    }

    public function assemble($mtx,$form,$pRate,$value,$year,$region){

        echo "<table style='width: 100%; zoom:80%;'>";
            echo "<tr>";
                echo "<th colspan='15' class='lightBlue'><center><span style='font-size:24px;'> Year Over Year :(".$form.") ".$year." (".$pRate[0]['name']."/".strtoupper($value).")</span></center></th>";
            echo "</tr>";

            echo "<tr><td> &nbsp; </td></tr>";

        for ($b=0; $b < sizeof($mtx); $b++) { 
            echo "<tr><td class='".strtolower($mtx[$b][0][0])." center' rowspan='7'>".$mtx[$b][0][0]."</td></tr>";
            for ($l=0; $l < sizeof($mtx[$b]); $l++) { 
                echo "<tr>";
                for ($v=0; $v < sizeof($mtx[$b][$l]); $v++) { 
                    if (is_numeric($mtx[$b][$l][$v])) {
                        if ($v == 13) {
                            echo "<td class='smBlue center'>".number_format($mtx[$b][$l][$v])."</td>";
                        }elseif ($l == 1 || $l == 2) {
                            echo "<td class='center'>".number_format($mtx[$b][$l][$v])."</td>";
                        }elseif ($l == 3) {
                            echo "<td class='rcBlue center'>".number_format($mtx[$b][$l][$v])."</td>";
                        }else{
                            echo "<td class='medBlue center'>".number_format($mtx[$b][$l][$v])."</td>";
                        }
                    }else{
                        if ($l == 0) {
                            if ($v == 0) {
                                echo "<td class='lightGrey center'>&nbsp;</td>";
                            }elseif ($v != 13) {
                                echo "<td class='lightGrey center'>".$mtx[$b][$l][$v]."</td>";
                            }else{
                                echo "<td class='darkBlue center'>".$mtx[$b][$l][$v]."</td>";
                            }
                        }elseif ($l == 1) {
                            echo "<td class='coralBlue center'>".$mtx[$b][$l][$v]."</td>";
                        }elseif ($l == 2 || $l == 3) {
                            echo "<td class='rcBlue center'>".$mtx[$b][$l][$v]."</td>";
                        }else{
                            echo "<td class='medBlue center'>".$mtx[$b][$l][$v]."</td>";
                        }
                    }
                }
                echo "</tr>";
            }
            echo "<tr><td> &nbsp; </td></tr>";
        }

        echo "</table>";

    }

}



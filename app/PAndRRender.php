<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PAndRRender extends Render{

    protected $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');

    protected $channel = array('DC','HH','DK','AP','TLC','ID','DT','FN','ONL','VIX','OTH','HGTV');

    protected $head = array('Closed','$Cons.','Prop','Fcast','Total');

    public function AE1($forRender,$client,$tfArray,$odd,$even,$userName){

        $cYear = $forRender['cYear'];
        $pYear = $forRender['pYear'] ;
        $salesRep = $forRender['salesRep'];
        $client = $forRender['client'];
        $splitted = $forRender['splitted'];
        $targetValues = $forRender['targetValues'];

        $odd = $forRender["readable"]["odd"];
        $even = $forRender["readable"]["even"];
        $tfArray = $forRender["readable"]["tfArray"];
        $manualEstimation = $forRender["readable"]["manualEstimation"];

        $rollingFCST = $forRender['rollingFCST'];
        $lastRollingFCST = $forRender['lastRollingFCST'];
        $clientRevenueCYear = $forRender['clientRevenueCYear'];
        $clientRevenuePYear = $forRender['clientRevenuePYear'];

        $executiveRF = $forRender["executiveRF"];
        $executiveRevenueCYear = $forRender["executiveRevenueCYear"];
        $executiveRevenuePYear = $forRender["executiveRevenuePYear"];

        $pending = $forRender["pending"];
        $RFvsTarget = $forRender["RFvsTarget"];
        $targetAchievement = $forRender["targetAchievement"];

        $currency = $forRender["currency"];
        $value = $forRender["value"];
        $region = $forRender["region"];

        $currencyName = $forRender["currencyName"];
        $valueView = $forRender["valueView"];

        $fcstAmountByStage = $forRender["fcstAmountByStage"];
        $fcstAmountByStageEx = $forRender["fcstAmountByStageEx"];

        echo "<input type='hidden' id='salesRep' name='salesRep' value='".base64_encode(json_encode($salesRep))."'>";
        echo "<input type='hidden' id='client' name='client' value='".base64_encode(json_encode($client)) ."'>";
        echo "<input type='hidden' id='currency' name='currency' value='".base64_encode(json_encode($currency))."'>";
        echo "<input type='hidden' id='splitted' name='splitted' value='".base64_encode(json_encode($splitted))."'>";
        echo "<input type='hidden' id='value' name='value' value='".base64_encode(json_encode($value))."'>";
        echo "<input type='hidden' id='region' name='region' value='".base64_encode(json_encode($region))."'>";
        echo "<input type='hidden' id='user' name='user' value='".base64_encode(json_encode($userName))."'>";
        echo "<input type='hidden' id='year' name='year' value='".base64_encode(json_encode($cYear))."'>";

        echo "<div class='table-responsive' style='zoom:80%;'>
            <table style=' border:solid; width:100%; text-align:center; border-width:1px; font-size:25px;'>
                <tr><th class='lightBlue'>".$salesRep['salesRep']." - ".$currencyName."/".$valueView."</th></tr>
            </table>
        </div>";

        echo "<br>";

        echo "<div class='' style='zoom:80%; scroll-margin-botton: 10px;'>";

        echo "<div class='row'>";

        echo "<div class='col-2' style='padding-right:1px;'>";
        echo "<table class='' id='example' style='width:100%; text-align:center; min-height:225px;'>";
            echo "<tr>";
                echo "<td class='darkBlue' style=' border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; font-size:20px; height:40px; '>".$salesRep['abName']."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Target</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><span>Rolling Fcast ".$cYear."</span><br>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Bookings</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Pending</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".$pYear."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Var RF vs Target</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>% Target Achievement</td>";
            echo "</tr>";           

        echo "</table>";
        echo "</div>";

        echo "<div class='col linked table-responsive ' style='width:100%; padding-left:0px;'>";
    	echo "<table style='min-width:3000px; width:80%; text-align:center; min-height:225px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>";
    		/*
                START OF SALES REP AND SALES REP TOTAL MONTHS

            */
            echo "<thead>";
            echo "<tr>";
                for ($m=0; $m <sizeof($this->month) ; $m++) {
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='quarter' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; height:40px;'>".$this->month[$m]."</td>";
                    }else{
                        echo "<td class='smBlue' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; height:40px;'>".$this->month[$m]."</td>";
                    }
                }
                echo "<td class='darkBlue' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; height:40px;'>Total</td>";
                echo "<td style='width:0.5%;'>&nbsp</td>";
                echo "<td class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>Closed</td>";
                echo "<td class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>Cons. (%)</td>";
                echo "<td class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>Exp</td>";
                echo "<td class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>Prop</td>";
                echo "<td class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>Adv</td>";
                echo "<td class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>Contr</td>";
                echo "<td class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;'>Total</td>";
                echo "<td class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Lost</td>";
    		echo "</tr>";
            echo "</thead>";
            
            /*
                
                START OF TARGET BY SALES REP INFO

            */
            echo "<tbody>";
    		echo "<tr>";
                $totalTarget = 0.0;
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='target-$m' name='target-$m' value='".number_format($targetValues[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                        $totalTarget += $targetValues[$m];
                    }else{
                        echo "<td class='$even[$m]'><input type='text' readonly='true' id='target-$m' name='target-$m' value='".number_format($targetValues[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalTarget' name='totalTarget' value='".number_format($targetValues[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
                echo "<td>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
    		echo "</tr>";
            /*
                
                END OF TARGET BY SALES REP INFO

            */

            /*
                
                START OF ROLLING FCST BY SALES REP INFO

            */ 
    		echo "<tr>";
                    //echo "<div style='display:none;' id='totalTotalPP'><span >Total P.P. (%):   </span><input type='number' value='100' readonly='true' id='totalClients' style='display:;width:30%;text-align:right;'></div>";
                echo"</td>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='rf-$m' name='rf-$m' value='".number_format($executiveRF[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='$odd[$m]'><input type='text' name='fcstSalesRep-$m' name='rf-$m' readonly='true' id='rf-$m' value='".number_format($executiveRF[$m])."' style='width:100%; border:none; text-align:center; font-weight:bold;  background-color:transparent;'></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' name='total-total' readonly='true' id='total-total' value='".number_format($executiveRF[$m])."' style='width:100%; border:none; font-weight:bold; color:white; background-color:transparent; text-align:center'></td>";
                echo "<td>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][4])."</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][7])."%</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][0])."</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][1])."</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][2])."</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][3])."</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>".number_format($fcstAmountByStageEx[1][6])."</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStageEx[1][5])."</td>";
    		echo "</tr>";
            /*
                
                END OF ROLLING FCST BY SALES REP INFO

            */ 

            /*
                
                START OF BOOKED BY SALES REP INFO

            */ 
            echo "<tr>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='bookingE-$m' name='bookingE-$m' value='".number_format($executiveRevenueCYear[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='$even[$m]' ><input type='text' readonly='true' id='bookingE-$m' value='".number_format($executiveRevenueCYear[$m])."' name='bookingE-$m' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalBookingE' name='totalBookingE' value='".number_format($executiveRevenueCYear[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
                echo "<td>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
            echo "</tr>";
            /*
                
                END OF BOOKED BY SALES REP INFO

            */ 

            /*
                
                START OF PENDING BY SALES REP INFO

            */ 
            echo "<tr>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' name='pending-$m' id='pending-$m' value='".number_format($pending[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='$odd[$m]' ><input type='text' readonly='true' id='pending-$m' value='".number_format($pending[$m])."' name='pending-$m' style='width:100%; border:none;  font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalPending' name='totalPending' value='".number_format($pending[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
                echo "<td>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
            echo "</tr>";
            /*
                
                END OF PENDING BY SALES REP INFO

            */ 

             /*
                
                START OF PYEAR

            */ 
            echo "<tr>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='oldY-$m' name='oldY-$m' value='".number_format($executiveRevenuePYear[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='$even[$m]'><input type='text' readonly='true' id='oldY-$m' value='".number_format($executiveRevenuePYear[$m])."' name='oldY-$m' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;' ></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalOldYear' name='totalOldYear' value='".number_format($executiveRevenuePYear[$m])."' style='width:100%; border:none; color:white; font-weight:bold; text-align:center; background-color:transparent;' ></td>";
                echo "<td>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
            echo "</tr>";
            /*
                
                END OF PYEAR

            */ 


            /*
                
                START VAR RF VS TARGET BY SALES REP

            */ 
            echo "<tr>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='RFvsTarget-$m' value='".number_format($RFvsTarget[$m])."' name='RFvsTarget-$m' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='$odd[$m]'><input type='text' readonly='true' id='RFvsTarget-$m' value='".number_format($RFvsTarget[$m])."' name='RFvsTarget-$m' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' ><input type='text' readonly='true' id='TotalRFvsTarget' name='TotalRFvsTarget' value='".number_format($RFvsTarget[$m])."' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
                echo "<td>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>&nbsp</td>";
                echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
            echo "</tr>";
            /*
                
                END VAR RF VS TARGET BY SALES REP

            */

            /*
                
                START % TARGET ACHIEVEMENT

            */ 
            echo "<tr>";
                for ($m=0; $m <sizeof($this->month) ; $m++) { 
                    if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                        echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'><input type='text' readonly='true' name='achievement-$m' id='achievement-$m' value='".number_format($targetAchievement[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }else{
                        echo "<td class='$even[$m]' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'><input type='text' readonly='true' name='achievement-$m' id='achievement-$m' value='".number_format($targetAchievement[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                    }
                }
                echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;' ><input type='text' readonly='true' id='totalAchievement' name='totalAchievement' value='".number_format($targetAchievement[$m])."%' style='width:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";
                echo "<td>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>&nbsp</td>";
                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
            echo "</tr>";
            /*
                
                END % TARGET ACHIEVEMENT

            */

            /*
                
                START VAR RV vs PLAN

            */ 
           
            echo "</tbody>";
            /*
                
                END VAR RV vs PLAN

            */
    	echo "</table>";
        echo "</div>";
        echo "</div>";
        echo "</div>";

        echo "<br>";


        for ($c=0; $c < sizeof($client); $c++) {
            if($splitted){
                if($splitted[$c]['splitted']){
                    $clr = "lightBlue";
                }else{
                    $clr = "lightBlue";
                }                        
            }else{
                $clr = "lightBlue";                    
            }

            if($splitted){
                if($splitted[$c]['splitted']){
                    if(is_null($splitted[$c]['owner'])){
                        $ow = "(?)";
                    }else{
                        if($splitted[$c]['owner']){
                            $ow = "(P)";
                        }else{
                            $ow = "(S)";
                        }
                    }
                }else{
                    $ow = false;
                }
            }else{
                $ow = false;
            }

            echo "<div class='' style='zoom:80%;'>";
            echo "<div class='row'>";
            echo "<div class='col-2' style='padding-right:1px;'>";
            echo "<table id='table-$c' style='width:100%; text-align:center; overflow:auto; min-height: 180px;' >";
                echo "<tr>";
                    echo "<td class='$clr' id='client-$c' rowspan='1' style=' text-align:center; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; '><span style='font-size:18px; '> ".$client[$c]['clientName']." $ow </span>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='rcBlue'  style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'> Rolling Fcast ".$cYear." </td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Manual Estimation";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Booking</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".$pYear."</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='rcBlue' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>Var RF vs ".$pYear."</td>";
                echo "</tr>";
            echo "</table>";
            echo "</div>";
            echo "<div class='col linked table-responsive' style='padding-left:0px;'>";



            echo "<table id='table-$c' style='min-width:3000px; width:100%; text-align:center; overflow:auto; min-height: 180px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;' >";
                
                /* 

                    START OF CLIENT NAME AND MONTHS

                */

                

                echo "<input type='text' id='splitted-$c' name='splitted-$c' value='$ow' style='display:none;'>";

                echo "<tr>";

                    
                    echo "</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='quarter' id='quarter-$c-$m' rowspan='1' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; '>".$this->month[$m]."</td>";
                        }else{
                            echo "<td class='smBlue' colspan='1' id='month-$c-$m' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; '>".$this->month[$m]."</td>";
                        }
                    }
                    echo "<td class='darkBlue' id='TotalTitle-$c' rowspan='1' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px; '>Total</td>";
                    echo "<td rowspan='6' id='division-$c' style='width:0.5%;'>&nbsp</td>";
                    echo "<td id='sideTable-$c-0' rowspan='1' class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Closed</td>";
                    echo "<td id='sideTable-$c-1' rowspan='1' class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Cons.(%)</td>";
                    echo "<td id='sideTable-$c-2' rowspan='1' class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Exp</td>";
                    echo "<td id='sideTable-$c-3' rowspan='1' class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Prop</td>";
                    echo "<td id='sideTable-$c-4' rowspan='1' class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Adv</td>";
                    echo "<td id='sideTable-$c-5' rowspan='1' class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Contr</td>";
                    echo "<td id='sideTable-$c-6' rowspan='1' class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Total</td>";
                    echo "<td id='sideTable-$c-7' rowspan='1' class='lightGrey' style='width:3.9%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>Lost</td>";

                echo "</tr>";
                /* 

                    END OF CLIENT NAME AND MONTHS

                */

                                   
                /*echo "<tr style='display:none;' id='newLine-$c'>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {

                        }else{
                            echo "<td colspan='1' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;' class='smBlue'>";
                                echo "<div class='row'>";
                                    echo "<div class='col' id='input-$c-$m' style='display:none;width:100%;'><span>P.P. (%):</span><input id='inputNumber-$c-$m' name='inputNumber-$c-$m' type='number' min='0' max='100' step='0.5' value='0' style='width:25%; background-color:transparent; text-align:right; border-style:solid; border-color: grey; border-width:1px;'></div>";
                                    echo "<input type='number' style='display:none;' name='inputNumber2-$c-$m' id='inputNumber2-$c-$m' value='0'>";

                                echo "</div>";

                            echo "</td>";
                            
                        }
                    }
                echo "</tr>";*/
                
                /* 

                    START OF CLIENT ROLLING FORECAST

                */                 
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($lastRollingFCST[$c][$m])."</td>";
                        }else{
                            echo "<td class='$even[$m]'>".number_format($lastRollingFCST[$c][$m])."</td>";
                    
                        }
                    }
                    echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' id='passTotal-$c' name='passTotal-$c' readonly='true' value='".number_format($lastRollingFCST[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center; color:white;'></td>";

                    if ($fcstAmountByStage[$c]) {
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStage[$c][1][4])."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStage[$c][1][7])."%</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStage[$c][1][0])."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStage[$c][1][1])."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStage[$c][1][2])."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStage[$c][1][3])."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStage[$c][1][6])."</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($fcstAmountByStage[$c][1][5])."</td>";
                    }else{
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0.00</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0.00%</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0.00</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0.00</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0.00</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0.00</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0.00</td>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0.00</td>";    
                    }
                echo "</tr>";
                 /* 

                    END OF CLIENT ROLLING FORECAST

                */ 

                /* 

                    START OF CLIENT MANUAL ESTIMATION

                */ 
                echo "<tr>";
                        //echo "<div style='display:none;' id='totalPP-$c' ><span>Total P.P.(%):</span><input type='number' id='totalPP2-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;'><input type='number' id='totalPP3-$c' step='0.5' value='10' min='0' max='100' style='width:25%; background-color:white; text-align:right; border-style:solid; border-color: grey; border-width:1px;display:none;'></div>";
                    echo "</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' name='fcstClient-$c-$m' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='clientRF-$c-$m' name='clientRF-$c-$m' value='".number_format($rollingFCST[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center'></td>";
                        }else{
                            echo "<td class='$odd[$m]' style='".$manualEstimation[$m]."'>";
                                if ($ow && $ow != '(P)') {
                                    echo "<input type='text' name='fcstClient-$c-$m' id='clientRF-$c-$m' readonly='true' value='".number_format($rollingFCST[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center'>";
                                }else{
                                    echo "<input type='text' name='fcstClient-$c-$m' id='clientRF-$c-$m' ".$tfArray[$m]." value='".number_format($rollingFCST[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; text-align:center;'>";
                                }
                            echo "</td>";
                            /*echo "<td class='odd' rowspan='5' style='width:4%; display:none; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;' id='newCol-$c-$m'>";
                                for ($ch=0; $ch <sizeof($this->channel) ; $ch++) { 
                                    echo"<center>";
                                        echo "<div class='row' id='inputC-$c-$ch-$m' style='width:100%;white-space:nowrap;'>";
                                            echo "<div class='col-sm-4'>".$this->channel[$ch]."</div>";
                                            echo "<div class='col-sm-8'><input id='inputCNumber-$c-$ch-$m' type='number' min='0' max='100' step='0.5' value='10' style='width:100%;'></div>";
                                        echo "</div>";
                                    echo"</center>";
                                }
                            echo "</td>";*/
                        }
                    }
                    echo "<td class='smBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalClient-$c' name='totalClient-$c' value='".number_format($rollingFCST[$c][$m])."' style='width:100%; border:none; font-weight:bold; background-color:transparent; color:white; text-align:center'><input type='text' readonly='true' id='totalTClient-$c' name='totalTClient-$c' value='50000' style='width:100%; border:none; font-weight:bold; background-color:transparent; color:white; text-align:center;display:none;'></td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "</tr>";
                /* 

                    END OF CLIENT MANUAL ESTIMATION

                */

                /* 

                    START OF CLIENT BOOKING

                */   
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>".number_format($clientRevenueCYear[$c][$m])."</td>";
                        }else{
                            echo "<td class='$even[$m]' >".number_format($clientRevenueCYear[$c][$m])."</td>";
                            echo "<td id='booking-$c-$m' style='display:none;'> </td>";
                        }
                    }
                    echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>".number_format($clientRevenueCYear[$c][$m])."</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "</tr>";
                /* 

                    END OF CLIENT BOOKING

                */ 
                
                /* 

                    START OF CLIENT PAST YEAR

                */ 
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='PY-$c-$m' name='PY-$c-$m' value='".number_format($clientRevenuePYear[$c][$m])."' style='width:100%; font-weight:bold; background-color:transparent; border:none; text-align:center' ></td>";
                        }else{
                            echo "<td class='$odd[$m]'><input type='text' readonly='true' id='PY-$c-$m' name='PY-$c-$m' value='".number_format($clientRevenuePYear[$c][$m])."' style='width:100%; font-weight:bold; background-color:transparent; border:none; text-align:center' ></td>";
                            echo "<td id='lastYear-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='totalPY-$c' name='totalPY-$c' value='".number_format($clientRevenuePYear[$c][$m])."' style='width:100%; color:white; background-color:transparent; font-weight:bold; border:none; text-align:center'></td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "</tr>";
                /* 

                    END OF CLIENT PAST YEAR

                */ 


                /* 

                    START OF CLIENT RF VS PLAN
                    



                    NAO EXISTE PLANO POR CLIENTE, FAZER O QUE ???

                *//*
                echo "<tr>";
                    echo "<td class='odd' style='text-align:left; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>Var RF vs Plan</td>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                        }else{
                            echo "<td class='odd'>0</td>";
                            echo "<td id='RFxPlan-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                    echo "<td class='odd' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>&nbsp</td>";
                echo "</tr>"; 
                /* 

                    END OF CLIENT RF VS PLAN

                */   

                /* 

                    START OF CLIENT RF VS PYEAR

                */
                echo "<tr>";
                    for ($m=0; $m <sizeof($this->month) ; $m++) { 
                        $tmp = $rollingFCST[$c][$m] - $clientRevenuePYear[$c][$m];
                        if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                            echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>";                            
                                echo "<input type='text' readonly='true' id='RFvsPY-$c-$m' name='RFvsPY-$c-$m' value='".number_format($tmp)."' style='width:100%; font-weight:bold; background-color:transparent; border:none; text-align:center'>";
                            echo "</td>";
                        }else{
                            echo "<td class='$even[$m]' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>";                            
                                echo "<input type='text' name='RFvsPY-$c-$m' readonly='true' id='RFvsPY-$c-$m' value='".number_format($tmp)."' style='width:100%; font-weight:bold; background-color:transparent; border:none; text-align:center'>";
                            echo "</td>";
                            echo "<td id='RFxLY-$c-$m' style='display:none;'></td>";
                        }
                    }
                    echo "<td class='smBlue' style=' border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'><input type='text' id='totalRFvsPY-$c' name='totalRFvsPY-$c' readonly='true' value='".number_format($rollingFCST[$c][$m] - $clientRevenuePYear[$c][$m])."' style='width:100%; font-weight:bold; background-color:transparent; border:none; color:white; text-align:center'></td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                    echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>&nbsp</td>";
                echo "</tr>";
                /* 

                    END OF CLIENT RF VS PYEAR

                */
                
            echo "</table>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "<br>";
        }   

    }

    public function dealsWithMonthYTD($currentMonth){
        $base = new base();

        $monthArray = $base->month;

        if($currentMonth == 1){
            return "JAN";
        }else{

            for ($m=0; $m < sizeof($monthArray); $m++) { 
                if($currentMonth == $monthArray[$m][1]){
                    $ytd = $monthArray[$m][0];
                }
            }
            return "JAN-$ytd";
        }

    }

    public function dealsWithMonth($currentMonth){
        $base = new base();

        $monthArray = $base->month;

        for ($m=0; $m < sizeof($monthArray); $m++) { 
            if($currentMonth == $monthArray[$m][1]){
                $mm = $monthArray[$m][2];
            }
        }
        return $mm;
    }

    public function VP1($forRender){
        $current = date('m');
        $yearToDate = $this->dealsWithMonthYTD($current);
        $currentMonth = $this->dealsWithMonth($current);
        
        $client = $forRender['client'];

        $bookingscYTDByClient = $forRender["bookingscYTDByClient"];
        $bookingspYTDByClient = $forRender["bookingspYTDByClient"];
        $varAbsYTDByClient = $forRender["varAbsYTDByClient"];

        $fcstcMonthByClient = $forRender["fcstcMonthByClient"];
        $bookingscMonthByClient = $forRender["bookingscMonthByClient"];
        $totalcYearMonthByClient = $forRender["totalcYearMonthByClient"];
        $bookingspMonthByClient = $forRender["bookingspMonthByClient"];
        $varAbsMonthByClient = $forRender["varAbsMonthByClient"];

        $fcstFullYearByClient = $forRender['fcstFullYearByClient'] ;
        $bookingscYearByClient = $forRender['bookingscYearByClient'];
        $bookingspYearByClient = $forRender['bookingspYearByClient'];
        $closedFullYearByClient = $forRender['closedFullYearByClient'];
        $bookedPercentageFullYearByClient = $forRender['bookedPercentageFullYearByClient'];
        $totalFullYearByClient = $forRender['totalFullYearByClient'];
        $varAbsFullYearByClient = $forRender["varAbsFullYearByClient"];
        $varPerFullYearByClient = $forRender["varPerFullYearByClient"];

        $bookingscYTD = $forRender["bookingscYTD"];
        $bookingspYTD = $forRender["bookingspYTD"];

        echo "<div class='row'>";
        echo "<div class='col-2'>";
            echo "<table  style=' width:100%;  text-align:center;'>";
                echo "<tr>";
                    echo "<td style='width:8%; height:40px;'><input type='text' id='myInput' onkeyup=\"myFunc()\" placeholder=\"Search for clients...\"></td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; height:20px;' >&nbsp</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='lightBlue' style='width:8%; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px; height:20px;'>&nbsp</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; height:20px;' >Total</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px; height:20px;'>%</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td>&nbsp</td>";
                echo "</tr>";
            echo "</table>";
        echo "</div>";
        echo "<div class='col table-responsive linked'>";
            echo "<table style=' min-width:2600px; width:100%; text-align:center; margin-right:10px;'>";
                echo "<tr>";
                    echo "<td class='darkBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 1px; height:40px;' colspan='2'>Bookings YTD</td>";
                    echo "<td class='darkBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;'> $yearToDate </td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='darkBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 1px;' colspan='4'> Bookings $currentMonth </td>";
                    /*
                        CURRENT MONTH
                    */
                    echo "<td class='darkBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;' > $currentMonth </td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='darkBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;' colspan='9'>Full Year</td>";
                echo "</tr>";
                 echo "<tr>";
                    echo "<td class='lightBlue' rowspan='2' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 1px; height:20px;'>2019</td>";
                    echo "<td class='lightBlue' rowspan='2' style='border-style:solid; border-color:black; border-width: 0px 0px 0px 0px;'>2018</td>";
                    echo "<td class='lightBlue' rowspan='2' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>Var. 2018</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='lightBlue' colspan='3' style='width:15%; border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;'>2019</td>";
                    echo "<td class='lightBlue' rowspan='2'>2018</td>";
                    echo "<td class='lightBlue' rowspan='2'style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>Var. 2018</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='lightBlue' colspan='6' style='width:30%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>2019</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>2018</td>";
                    echo "<td class='lightBlue' colspan='2' style='width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>Var 2019/2018</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td style='height:20px;'>&nbsp</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>Bookings</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Fcast</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Total</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>Closed</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Booked</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>% Booked</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Proposals</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>Fcast</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>Total</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>Total</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>\$</td>";
                    echo "<td class='lightBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>%</td>";
                echo "</tr>";
                echo "<tr>";
                    /* Bookings YTD Current Year */
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 1px; height:20px; width:5.7%;'>
                            ".number_format($bookingscYTD, 0, ".", ",")."
                        </td>";

                    /* Bookings YTD Past Year */
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; width:5.7%;'>
                            ".number_format($bookingspYTD, 0, ".", ",")."
                          </td>";

                    /* Bookings YTD Var YoY */
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 0px; width:5.7%;'>0</td>";

                    echo "<td style='width:1%;'>&nbsp</td>";

                    /* Bookings Current Month on Current Year */
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 1px; width:5.7%;'>0</td>";

                    /* FCST Current Month on Current Year */
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; width:5.7%;'>
                            <input type='text' readonly='true' id='RF-Total-Cm' 
                                               value='0' 
                                   style='width:100%; border:none; 
                                   font-weight:bold; background-color:transparent; text-align:center'>
                          </td>";

                    /* Bookings Current Month on Current Year + FCST Current Month on Current Year */
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; width:5.7%;'>0</td>";

                    /* Bookings Current Month on Past Year */
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; width:5.7%;'>0</td>";

                    /* VAR Bookings Current Month on Current Year -/ FCST Current Month on Current Year */
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 0px; width:5.7%;'>0</td>";

                    echo "<td style='width:1%;'>&nbsp</td>";

                    /*Closed*/
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 1px; width:5.7%;'>0</td>";

                    
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; width:5.7%;'>0</td>";

                    /* % Booked*/                    
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; width:5.7%;'>0%</td>";

                    /*Proposals*/                    
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; width:5.7%;'>0</td>";
                    
                    /*Fcst*/
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; width:5.7%;'><input type='text' readonly='true' id='RF-Total-Fy' value='0' style=' border:none; font-weight:bold; background-color:transparent; text-align:center'></td>";
                    /*Total CYear*/
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; width:5.7%;'>0</td>";

                    /*Total PYear*/
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; width:5.7%;'>2</td>";

                    /*Var Abs YoY*/
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; width:5.7%;'>0</td>";

                    /*Var Per YoY*/
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 0px; width:5.7%;'>0%</td>";

                echo "</tr>";

                echo "<tr>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px; height:20px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>0%</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>0%</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>0%</td>";
                    echo "<td>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 1px;'>0%</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>0%</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>0%</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>0%</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>0%</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>&nbsp</td>";
                    echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>&nbsp</td>";
                echo "</tr>";
            echo "</table>";
        echo "</div>";
        echo "</div>";


        echo "<div class='row '>";

        echo "<div class='col-2'>";
            echo "<table class='temporario linked2' id='table1' style='width:100%; height:400px; overflow-y:scroll; text-align:center;'>";
                for ($c=0; $c <sizeof($client) ; $c++) {
                    if($c%2 == 0){
                        $class = "rcBlue";
                    }else{
                        $class = "odd";
                    }
                    echo "<tr>";
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 1px 1px 1px; height:30px; width:100%;' id='parent-$c' >".$client[$c]['client']."</td>";
                        echo "<td>&nbsp</td>";
                    echo "</tr>";
                }
            echo "</table>";
        echo "</div>";

        echo "<div class='col table-responsive linked '>";
            echo "<table class='temporario linked2' id='table2' style='min-width:2615px; height:400px; width:100%; overflow-y:scroll; text-align:center; width:100%;'>";
                
                for ($c=0; $c < sizeof($client); $c++) {
                    if($c%2 == 0){
                        $class = "rcBlue";
                    }else{
                        $class = "odd";
                    }

                    echo "<tr>";

                        /* Bookings YTD Current Year */
                        echo "<td class='$class' id='child-$c' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 1px; height:30px; width:5.7%;'>
                                ".number_format($bookingscYTDByClient[$c], 0, ".", ",")."
                              </td>";

                        /* Bookings YTD Past Year */
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5.7%;'>
                                ".number_format($bookingspYTDByClient[$c], 0, ".", ",")."
                              </td>";

                        /* Bookings YTD Var YoY */
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 1px 1px 0px; width:5.7%;'>
                                ".number_format($varAbsYTDByClient[$c], 0, ".", ",")."
                              </td>";

                        echo "<td style='width:1%;'>&nbsp</td>";

                        /* Bookings Current Month on Current Year */
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 1px; width:5.7%;'>
                                ".number_format($bookingscMonthByClient[$c], 0, ".", ",")."
                              </td>";

                        /* FCST Current Month on Current Year */
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5.7%;'>
                                    <input type='text' id='clientRF-Cm-$c' 
                                           value='".number_format($fcstcMonthByClient[$c], 0, ".", ",")."' 
                                           style='width:100%; border:none; 
                                           font-weight:bold; 
                                           background-color:transparent; text-align:center'>
                              </td>";

                        /*TOTAL September BKG + FCST*/                        
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5.7%;'>
                                ".number_format($totalcYearMonthByClient[$c], 0, ".", ",")."
                            </td>";

                        /* Bookings Current Month on Past Year */
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5.7%;'>
                                ".number_format($bookingspMonthByClient[$c], 0, ".", ",")."
                              </td>";

                        /* VAR Bookings Current Month on Current Year -/ FCST Current Month on Current Year */
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 1px 1px 0px; width:5.7%;'>
                                    ".number_format($varAbsMonthByClient[$c], 0, ".", ",")."
                              </td>";

                        echo "<td style='width:1%;'>&nbsp</td>";

                        /*Closed*/
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 1px; width:5.7%;'>
                                ".number_format($closedFullYearByClient[$c], 0, ".", ",")."
                              </td>";

                        /* Booked*/
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5.7%;'>
                                ".number_format($bookingscYearByClient[$c], 0, ".", ",")."
                              </td>";

                        /* % Booked Percentage*/                    
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5.7%;'>
                                ".number_format($bookedPercentageFullYearByClient[$c], 0, ".", ",")."%
                              </td>";

                        /*Proposals*/ 
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5.7%;'>0</td>";

                        /*Fcst*/
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5.7%;'>
                                    <input type='text' id='clientRF-Fy-$c' 
                                           value='".number_format($fcstFullYearByClient[$c], 0, ".", ",")."' 
                                           style='width:100; border:none; font-weight:bold;
                                           background-color:transparent; text-align:center;'>
                             </td>";

                        /*Total CYear*/
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5.7%;'>
                                ".number_format($totalFullYearByClient[$c], 0, ".", ",")."
                              </td>";

                        /*Total PYear*/
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5.7%;'>
                                ".number_format($bookingspYearByClient[$c], 0, ".", ",")."
                              </td>";

                        /*Var Abs YoY*/
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 0px 1px 0px; width:5.7%;'>
                                ".number_format($varAbsFullYearByClient[$c], 0, ".", ",")."
                              </td>";

                        /*Var Per YoY*/
                        echo "<td class='$class' style='border-style:solid; border-color:black; border-width: 1px 1px 1px 0px; width:5.7%;'>";
                            if($varPerFullYearByClient[$c] > 0){
                                echo number_format($varPerFullYearByClient[$c], 0, ".", ",")."%";
                            }else{
                                echo "-";
                            }
                        echo "</td>";
                    echo "</tr>";
                }
            echo "</table>";
        echo "</div>";

           
        echo "</div>";
    }    

    public function PandR1(){
        echo "<div class='table-responsive'>";
            for ($b=0; $b <sizeof($this->channel) ; $b++) {
                echo "<table style='width:100%;  text-align:center;' >";
                    echo "<tr>";
                        echo "<td colspan='2' class='".strtolower($this->channel[$b])."' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; width:15%;'>&nbsp</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='quarter' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; width:4.5%;'>".$this->month[$m]."</td>";
                            }else{
                                echo "<td class='lightGrey' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px; width:4.5%;'>".$this->month[$m]."</td>";
                            }
                        }
                        echo "<td class='darkBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 1px; width:6%;'>Total</td>";
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td rowspan='9' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;' class='".strtolower($this->channel[$b])."' >".$this->channel[$b]."</td>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;' class='rcBlue'>2018 Ad Sales</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;'>0</td>";
                            
                            }else{
                                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;' class='rcBlue'>0</td>";    
                            }
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;'>0</td>";    
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' 
                         class='odd'>2018 SAP</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                            }else{
                                echo "<td class='odd'>0</td>";
                            }    
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";    
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' class='rcBlue'>Target</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                            }else{
                                echo "<td class='rcBlue'>0</td>";
                            }
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";    
                    echo "</tr>";               

                    echo "<tr>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' class='odd'>Fcast Ad Sales - Current</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'><input type='text' readonly='true' id='fa-$b-$m' value='0' style='width:100%; height:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                            }else{
                                echo "<td class='odd'><input type='text' id='fa-$b-$m' value='0' style='width:100%; height:100%; border:none; font-weight:bold; text-align:center; background-color:transparent;'></td>";
                            }   
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'><input type='text' readonly='true' id='total-$b' value='0' style='width:100%; height:100%; border:none; font-weight:bold; text-align:center; background-color:transparent; color:white;'></td>";    
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' class='rcBlue'>Forecast Corporate</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                            }else{
                                echo "<td class='rcBlue'>0</td>";
                            }
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";    
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' class='odd'>2019 Ad Sales</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                            }else{
                                echo "<td class='odd'>0</td>";
                            }   
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";    
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' class='rcBlue'>2019 SAP</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                            }else{
                                echo "<td class='rcBlue'>0</td>";
                            }    
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";    
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;' class='odd'>Fcast 2019 - Fcast 2018 (%)</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;'>0</td>";
                            }else{
                                echo "<td class='odd'>0</td>";
                            }  
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;'>0</td>";    
                    echo "</tr>";

                    echo "<tr>";
                        echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>2019-Target (%)</td>";
                        for ($m=0; $m <sizeof($this->month) ; $m++) { 
                            if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) {
                                echo "<td class='medBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;'>0</td>";
                            }else{
                                echo "<td class='rcBlue' style='border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>0</td>";    
                            }
                        }
                        echo "<td class='smBlue' style='border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;'>0</td>";    
                    echo "</tr>";

                echo "</table>";
                echo "<br>";
            }
        echo "</div>";
    }
    
}

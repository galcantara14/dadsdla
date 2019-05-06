<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\results;
use App\sql;
use App\brand;
use App\ytd;
use App\pRate;
use App\planByBrand;
use App\mini_header;
use App\digital;

class resultsYoY extends results {
    
    public function lines($con, $brands, $region, $year, $value, $form, $source){

        $lines = array();

        if (sizeof($brands) == 0) {
            $lines = false;
        }else{

            for ($i=0; $i < sizeof($brands); $i++) {

                if ($brands[$i] == "9" || $brands[$i] == "10") {
                    $finalForm = 'Digital';
                }else{
                    $finalForm = $form;
                }
                
                for ($j=0; $j < 3; $j++) { 
                    $lines[$i][$j] = $this->line($con, $brands[$i], $region, $year, $value, $finalForm, ($j+1), $source);

                    if (!$lines[$i][$j]) {
                        $lines[$i][$j] = "This brand doesn't has values";
                    }
                }
            }
        }

        return $lines;

    }

    public function line($con, $brand, $region, $year, $value, $form, $lineNumber, $source){        

        switch ($lineNumber) {
            case 1:
                
                $res = $this->createObject($form, $value);
                $formResp = $res[0];
                
                if (!is_null($formResp)) {

                    $columns = $res[1];

                    if ($columns) {

                        if (sizeof($columns) == 4) {
                            $colValues = array($region, $brand, ($year-1));
                            $line = $this->lineValues($con, $res[2], $formResp, $columns, $colValues, $region, $year);

                        } elseif (sizeof($columns) == 3) {
                            $colValues = array($brand, ($year-1));
                            $line = $this->lineValues($con, $res[2], $formResp, $columns, $colValues, $region, $year);
                        }else{

                            $line = false;
                        }
                    }else{
                        $line = false;
                    }
                    
                }

                break;
            
            case 2:
                
                $columns = array("sales_office_id", "source", "type_of_revenue", "brand_id", "year", "month");
                $colValues = array($region, $source, strtoupper($value), $brand, $year);
                
                $p = new planByBrand();

                $line = $this->lineValues($con, "revenue", $p, $columns, $colValues, $region, $year);

                break;

            case 3:
                
                $res = $this->createObject($form, $value);
                $formResp = $res[0];

                if (!is_null($formResp)) {

                    $columns = $res[1];

                    if ($columns) {
                        if (sizeof($columns) == 4) {
                            $colValues = array($region, $brand, $year);
                            $line = $this->lineValues($con, $res[2], $formResp, $columns, $colValues, $region, $year);
                        } elseif (sizeof($columns) == 3) {
                            $colValues = array($brand, $year);
                            $line = $this->lineValues($con, $res[2], $formResp, $columns, $colValues, $region, $year);
                        }else{
                            $line = false;
                        }
                    }else{
                        $line = false;
                    }
                    
                }

                break;

            default:
                $line = false;
                break;
        }

        return $line;
        
    }

    public function lineValues($con, $value, $form, $columns, $colValues, $region, $year){
        
        $p = new pRate();
        $pRate = $p->getPRateByRegionAndYear($con, array($region), array($year));

        array_push($colValues, 0);

        $tam = sizeof($columns);

        for ($i = 0; $i < 12; $i++) {
            $colValues[$tam-1] = ($i+1);

            $formValue[$i] = $form->sum($con, $value, $columns, $colValues)['sum'];

            if ($formValue[$i] == 0) {
                $formValue[$i] = 0;
            }else{
                $formValue[$i] *= $pRate;
            }

        }
        
        return $formValue;
    }

    public function createObject($form, $value){
        
        $obj = null;
        $columns = array();

        switch ($form) {
            case 'IBMS':
                $obj = new ytd();

                $columns = array("campaign_sales_office_id", "brand_id", "year", "month");
                $value .= "_revenue";

                break;
            
            case 'CMAPS':
                $obj = new cmaps();

                $columns = array("brand_id", "year", "month");

                break;

            case 'Header':
                $obj = new mini_header();

                $columns = array("campaign_sales_office_id", "brand_id", "year", "month");
                $value .= "_revenue";

                break;

            case 'Digital':
                $obj = new digital();

                $columns = array("campaign_sales_office_id", "brand_id", "year", "month");
                $value .= "_revenue";

                break;

            default:
                $obj = false;

                break;
        }

        return array($obj, $columns, $value);

    }

    public function assemblers($totalBrands, $brands, $lines, $month, $year){
        
        $matrix = array();

        for ($i = 0; $i < sizeof($brands); $i++) {

            if (is_string($lines[$i][2])) {
                $matrix[$i] = $lines[$i][2];       
            }else{
                $matrix[$i] = $this->assembler($lines[$i][2], $lines[$i][1], $lines[$i][0],
                                                $month, $year);
            }
            
        }

        if (sizeof($brands) == sizeof($totalBrands)) {
            $matrix[sizeof($brands)] = $this->assemblerDN($matrix, sizeof($brands), $month, $year);
        }

        return $matrix;

    }

    public function assembler($valueCurrentYear, $target, $valuePastYear, $month, $year){

        $matrix = array();

        $valueCurrentYearSum = 0;
        $targetSum = 0;
        $valuePastYearSum = 0;
        $difExpectedSum = 0;
        $difYoYSum = 0;     

        $matrix[0][0] = " ";
        $matrix[1][0] = "Real ".($year-1);
        $matrix[2][0] = "Target $year";
        $matrix[3][0] = "Real $year";
        $matrix[4][0] = "Dif. 3° - 2°";
        $matrix[5][0] = "Dif. YoY";

        for ($i = 1; $i <= sizeof($month); $i++) { 

            $matrix[0][$i] = $month[$i-1][2];

            $matrix[1][$i] = $valuePastYear[$i-1];
            $valuePastYearSum += $valuePastYear[$i-1];

            $matrix[2][$i] = $target[$i-1];
            $targetSum += $target[$i-1];

            $matrix[3][$i] = $valueCurrentYear[$i-1];
            $valueCurrentYearSum += $valueCurrentYear[$i-1];

            $matrix[4][$i] = $matrix[3][$i] - $matrix[2][$i];
            $difExpectedSum += $matrix[4][$i];

            $matrix[5][$i] = $matrix[3][$i] - $matrix[1][$i];
            $difYoYSum += $matrix[5][$i];

        }

        $last = $i;

        $matrix[0][$last] = "Total";
        $matrix[1][$last] = $valuePastYearSum;
        $matrix[2][$last] = $targetSum;
        $matrix[3][$last] = $valueCurrentYearSum;
        $matrix[4][$last] = $difExpectedSum;
        $matrix[5][$last] = $difYoYSum;

        return $matrix;
    }

    public function assemblerDN($matrix, $pos, $month, $year){
        
        $currentMatrix = array();

        $currentMatrix[0][0] = " ";
        $currentMatrix[1][0] = "Real ".($year-1);
        $currentMatrix[2][0] = "Target $year";
        $currentMatrix[3][0] = "Real $year";
        $currentMatrix[4][0] = "Dif. 3° - 2°";
        $currentMatrix[5][0] = "Dif. YoY";

        for ($i = 1; $i <= sizeof($month); $i++) {

            $currentMatrix[0][$i] = $month[$i-1][2];

            $currentMatrix[1][$i] = 0;
            $currentMatrix[2][$i] = 0;
            $currentMatrix[3][$i] = 0;
            $currentMatrix[4][$i] = 0;
            $currentMatrix[5][$i] = 0;
        }

        $valueCurrentYearSum = 0;
        $targetSum = 0;
        $valuePastYearSum = 0;
        $difExpectedSum = 0;
        $difYoYSum = 0;     

        for ($i = 0; $i < $pos; $i++) { 

            for ($j = 1; $j <= sizeof($month); $j++) { 

                $currentMatrix[1][$j] += $matrix[$i][1][$j];
                $valueCurrentYearSum += $matrix[$i][1][$j];

                $currentMatrix[2][$j] += $matrix[$i][2][$j];
                $targetSum += $matrix[$i][2][$j];

                $currentMatrix[3][$j] += $matrix[$i][3][$j];
                $valuePastYearSum += $matrix[$i][3][$j];

                $currentMatrix[4][$j] += $matrix[$i][4][$j];
                $difExpectedSum += $matrix[$i][4][$j];

                $currentMatrix[5][$j] += $matrix[$i][5][$j];
                $difYoYSum += $matrix[$i][5][$j];
            }
        }

        $last = $j;

        $currentMatrix[0][$last] = "Total";
        $currentMatrix[1][$last] = $valuePastYearSum;
        $currentMatrix[2][$last] = $targetSum;
        $currentMatrix[3][$last] = $valueCurrentYearSum;
        $currentMatrix[4][$last] = $difExpectedSum;
        $currentMatrix[5][$last] = $difYoYSum;

        return $currentMatrix;

    }

}

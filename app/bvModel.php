<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\sql;


class bvModel extends Model{
    
    // == This function get all clients relationated with the AgencyGroup selected by user in the filter == //
    public function getSalesRepByAgencyGroup(String $agencyGroupId, string $salesRep, int $year, Object $con, Object $sql){
        $queryCmaps = "SELECT distinct sr.id as srID, sr.name as srName, a.id as agency, a.name as agencyName, c.id as client, c.name as clientName from cmaps cm 
                   left join agency a on a.ID = cm.agency_id 
                   left join client c on c.ID = cm.client_id 
                   left join sales_rep sr on sr.ID = cm.sales_rep_id  
                   left join agency_group ag on ag.ID = a.agency_group_id 
                   where ag.ID = $agencyGroupId
                   and sr.id = $salesRep
                   and cm.`year` in ($year)
                   order by 1 asc";

        $resultCmaps = $con->query($queryCmaps);
        $from = array('srID' , 'srName','agency', 'agencyName', 'client', 'clientName');
        $valueCmaps = $sql->fetch($resultCmaps, $from, $from);


        // == This part make the integration with WarnerMedia ALEPH base == //
        $queryAleph = "SELECT distinct sr.id as srID, sr.name as srName, a.id as agency, a.name as agencyName, c.id as client, c.name as clientName from aleph al 
                   left join agency a on a.ID = al.agency_id 
                   left join client c on c.ID = al.client_id 
                   left join sales_rep sr on sr.ID = al.current_sales_rep_id  
                   left join agency_group ag on ag.ID = a.agency_group_id 
                   where ag.ID = $agencyGroupId
                   and sr.id = $salesRep
                   and al.`year` in ($year)
                   order by 1 asc";

        $resultAleph = $con->query($queryAleph);
        $from = array('srID' , 'srName','agency', 'agencyName', 'client', 'clientName');
        $valueAleph = $sql->fetch($resultAleph, $from, $from);
    
        
        // == This variable return a matrix with Sales Rep Name and ID, Agency Name and ID and Client name and ID == //
        if ($valueAleph == "") {
            $value = $valueCmaps;
        }elseif ($valueCmaps == "") {
            $value = $valueAleph;
        }else{
            $value = array_merge($valueCmaps,$valueAleph);
        }  // Only for test porpouses 

        return $value;
    }

    // == This function get values for every client in the matrix generated by getSalesRepByAgencyGroup function == // 
    public function getValueForBvByYear(String $salesRep, String $agency, String $client, int $year, String $valueType, Object $con, Object $sql){
       $query = "SELECT SUM($valueType) from cmaps
                WHERE sales_rep_id = $salesRep
                AND agency_id = $agency
                AND client_id = $client
                AND year = $year";

        $result = $con->query($query);
        $from = "SUM($valueType)";
        $valuePivot = $sql->fetchSUM($result, $from);
        $value = $valuePivot[$from];
        //var_dump($query);
        return $value;
    }

    // == This function generate the matrix used in front-end == //
    public function tableBV(String $agencyGroupId, int $year, Object $con, String $valueType, string $salesRep){
        $sql = new sql();
        $year = (int)date("Y");
        $pYear = $year-1;
        $ppYear = $year-2;
        $bvTable = array();
        $result = $this->getSalesRepByAgencyGroup($agencyGroupId, $salesRep, $year, $con, $sql);

        /* == Generate arrays for fill the matrix, 
        the matrix structure is:
        [Sales Rep Name, Agency Name, Client Name, Antepenultimate Year Value, Penultimate Year Value, Actual Year Value, Actual Year Prevision, (Actual Year Value + Actual Year Prevision) and Variation] == */
        for ($i = 0; $i < sizeof($result); $i++){
            $pPreviousValue = $this->getValueForBvByYear($result[$i]['srID'], $result[$i]['agency'], $result[$i]['client'], $ppYear, $valueType, $con, $sql);
            $previousValue = $this->getValueForBvByYear($result[$i]['srID'], $result[$i]['agency'], $result[$i]['client'], $pYear, $valueType, $con, $sql);
            $actualValue = $this->getValueForBvByYear($result[$i]['srID'], $result[$i]['agency'], $result[$i]['client'], $year, $valueType, $con, $sql);
            $prevValue = 0; // Prevision are from database
            $statusString = ''; // Status are from database

            // == Percentage and division by 0 check, if values are big than 0 == //
            if ($actualValue > 0 && $previousValue > 0){
                $variation = number_format((($actualValue + $prevValue) / $previousValue) * 100);
            } else {
                $variation = 0;
            }

            // == Pivot Array used for fullfill the matrix, using the structure above == //
            $pivotArray = array('client' => $result[$i]['clientName'], $ppYear => $pPreviousValue, $pYear => $previousValue, $year => $actualValue, "prev" => $prevValue, "prevActualSum" => $actualValue + $prevValue, "variation" => $variation, "status" => $statusString);
            array_push($bvTable, $pivotArray);           
            
        };

        return $bvTable;
    }
}

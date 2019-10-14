<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;
use App\pRate;

class digital extends Management {
    
    public function getWithFilter($con, $value, $where, $currency, $region, $order_by = 1){
        
        $sql = new sql();

        $table = "fw_digital d";

        $columns = "d.ID AS 'id',
                    c.name AS 'client',
                    a.name AS 'agency',
                    d.campaign AS 'campaign',
                    d.insertion_order AS 'insertion_order',
                    r.name AS 'region',
                    d.io_start_date AS 'io_start_date',
                    d.io_end_date AS 'io_end_date',
                    d.agency_commission_percentage AS 'agency_commission_percentage',
                    d.rep_commission_percentage AS 'rep_commission_percentage',
                    cr.name AS 'currency',
                    d.placement AS 'placement',
                    d.buy_type AS 'buy_type',
                    d.content_targeting_set_name AS 'content_targeting_set_name',
                    d.ad_unit AS 'ad_unit',
                    d.month AS 'month',
                    d.".$value."_revenue AS '".$value."_revenue',
                    d.commission AS 'commission',
                    b.name AS 'brand',
                    d.year AS 'year'";

        $join = "LEFT JOIN client c ON c.ID = d.client_id
                 LEFT JOIN agency a ON a.ID = d.agency_id
                 LEFT JOIN region r ON r.ID = d.region_id
                 LEFT JOIN currency cr ON cr.ID = d.currency_id
                 LEFT JOIN brand b ON b.ID = d.brand_id";

        if (is_null($where)) {
            $where = "";
        }

        $order_by = "year";

        $result = $sql->select($con, $columns, $table, $join, $where, $order_by);
        
        $from = array('client', 'agency', 'campaign', 'insertion_order', 'region', 'io_start_date', 'io_end_date', 'agency_commission_percentage', 'rep_commission_percentage',
         'currency', 'placement', 'buy_type', 'content_targeting_set_name', $value.'_revenue', 'commission', 'brand', 'year');

        $to = $from;

        $digital = $sql->fetch($result, $from, $to);

        if (is_array($digital)) {
            $p = new pRate();
        
            if ($currency[0]['name'] == 'USD') {
                $pRate = 1.0;
            }else{
                $pRate = $p->getPRateByRegionAndYear($con,array($region),array(date('Y')));
            }
            
            for ($d=0; $d < sizeof($digital); $d++) {
                $digital[$d][$value.'_revenue'] *= $pRate;
            }   
        }
        
        return $digital;
    }

    public function sum($con, $value, $columnsName, $columnsValue){
        
        $sql = new sql();

        $table = "digital";

        $sum = "$value";

        $as = "sum";

        $where = $sql->where($columnsName, $columnsValue);
        $result = $sql->selectSum($con, $sum, $as, $table, null, $where);

        $res = $sql->fetchSum($result, $as);

        return $res;
    }

    public function excelToBase($sp){

        //var_dump($sp);

        unset($sp[0]);

        $sp = array_values($sp);
        var_dump($sp);
        for ($s=0; $s < sizeof($sp); $s++) { 
            
        }

    }
}

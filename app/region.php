<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;

class region extends Management{
    
    public function addRegion($con){
        $sql = new sql();

        $region = Request::get('region');
        $table = 'region';
        $columns = 'name';
        $values = "'$region'";
        $bool = $sql->insert($con,$table,$columns,$values);

        return $bool;
    }

    public function getRegion ($con, $ID){
        $sql = new sql();

        $table = "region r";
        $columns = "r.ID AS id,
                r.name AS name";

        $where = "";
    	if ($ID) {
    		$ids = implode(",", $ID);
    		$where .= "WHERE r.ID IN ('$ids')";
    	}

        $res = $sql->select($con,$columns,$table);

        $from = array('id','name');

        $region = $sql->fetch($res,$from,$from);

        return $region;
    }

    public function editRegion($con){

        $size = intval(Request::get("size"));
        $table = "region";
        $columns = array("name");

        for ($i=0; $i <$size ; $i++){ 
            $old[$i] = Request::get("Old-$i");
            $new[$i] = array(Request::get("New-$i"));

            $set[$i] = $this->setUpdate($columns,$new[$i]);

            $where[$i] = "WHERE name = '$old[$i]'";
        }

        for ($i=0; $i <$size ; $i++) { 
            $bool = $this->updateValues($con,$table,$set[$i],$where[$i]);
            if ($bool["bool"] == false) {
                break;
            }
        }

        return $bool;
    }
}
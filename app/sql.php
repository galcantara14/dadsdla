<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sql extends Model{
    
    public function select($con, $columns, $table, $join = null, $where = null, $order_by = 1, $limit = false){    	
        $sql = "SELECT $columns FROM $table $join $where ORDER BY $order_by $limit";    
        $res = $con->query($sql);

        //var_dump($res);
        //echo ($sql);

    	return $res;
    }

    public function insert($con,$table,$columns,$values){
        $insert = "INSERT INTO $table ($columns) VALUES ($values)"; 

        if($con->query($insert) === true){
            $rtr["bool"] = true;
            $rtr["msg"] = "A New record on the table $table was successfully created!";
        }else{
            $rtr["bool"] = false;
            $rtr["msg"] = "Error: ".$insert."<br>".$con->error;
        }

        return $rtr;
    }

    public function fetch($result,$from,$to){

    	if($result && $result->num_rows > 0){
    		$count = 0;
    		while ($row = $result->fetch_assoc()){
    			for ($i=0; $i < sizeof($from); $i++) { 
    				$info[$count][$to[$i]] = $row[$from[$i]];  				
    			}
    			$count++;
    		}
    	}else{
    		$info = false;
    	}

    	return $info;

    }

    public function setUpdate($columns, $values){

        $set = "SET ";
        for ($i=0; $i <sizeof($columns) ; $i++) { 
            if ($i == sizeof($columns)-1) {
                $set .= "$columns[$i] = \"$values[$i]\"";
            }else{
                $set .= "$columns[$i] = \"$values[$i]\", ";
            }
        }

        return $set;
    }


    public function updateValues($con,$tableName,$set,$where,$join = null){


        $sql = "UPDATE $tableName $set $join $where";

        if($con->query($sql) === true){
            $rtr["bool"] = true;
            $rtr["msg"] = "Successfully updated!";
        }else{
            $rtr["bool"] = false;
            $rtr["msg"] = "Error: ".$sql."<br>".$con->error;
        }

        return $rtr;
    }

    public function where($columns,$variables){

        $where = "WHERE ";


        for ($i=0; $i <sizeof($columns) ; $i++) { 
            if ($i == sizeof($columns)-1) {
                $where .= "($columns[$i] = \"$variables[$i]\")";
            }else{
                $where .= "($columns[$i] = \"$variables[$i]\") AND ";
            }
        }

        return $where;

    }

    public function deleteValues($con,$table,$where){
        $sql = "DELETE FROM $table $where";

        if($con->query($sql) === true){
            $rtr["bool"] = true;
            $rtr["msg"] = "Successfully updated!";
        }else{
            $rtr["bool"] = false;
            $rtr["msg"] = "Error: ".$sql."<br>".$con->error;
        }


        return $rtr;

    }

}

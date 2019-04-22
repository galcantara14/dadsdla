<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;

class pRate extends Management{
    
	public function getPRate($con){
		$sql = new sql();
		$table = "p_rate p";
		$columns = "p.ID AS 'id',					
					p.year AS 'year',
					p.value AS 'value',				
					c.name AS 'currency',
					r.name AS 'region'
				   ";
		$from = array('id','year','value','currency','region');	
		$join = "LEFT JOIN currency c ON p.currency_id = c.ID
				 LEFT JOIN region r ON c.region_id = r.ID";
		$order = "2,5,4";
		$result = $sql->select($con,$columns,$table,$join,false,$order);
		$pRate = $sql->fetch($result,$from,$from);		
		return $pRate;
	}

	public function addPRate($con){
		$sql = new sql();
		$year = Request::get('year');
		$currency = Request::get('currency');
		$value = doubleval(Request::get('value'));
		$table = 'p_rate';
		$columns = 'currency_id,year,value';
		$values = " '$currency','$year','$value' ";
		$bool = $sql->insert($con,$table,$columns,$values);
		return $bool;
		
	}

	public function getCurrency($con){
		$sql = new sql();
		$table = "currency c";
		$columns = "c.ID AS 'id',
					c.name AS 'name',
					r.name AS 'region'
				   ";
		$from = array('id','name','region');	
		$join = "LEFT JOIN region r ON c.region_id = r.ID";
		$order = "3";
		$result = $sql->select($con,$columns,$table,$join,false,$order);
		$currency = $sql->fetch($result,$from,$from);
		return $currency;
	}

	public function addCurrency($con){
        $sql = new sql();
        $region = Request::get('region');
        $currency = Request::get('currency');
        $regionID = $this->getID($con,'region',$region);
        $table = 'currency';
        $columns = 'name,region_id';
        $values = " '$currency','$regionID' ";
        $bool = $sql->insert($con,$table,$columns,$values);
        return $bool;

	}

}
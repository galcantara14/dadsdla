<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\base;
use App\monthly;
use App\region;
use App\salesRep;
use App\share;
use App\brand;
use App\pRate;
use App\Render;
use App\quarterRender;
use App\resultsMQ;
use App\renderMQ;
use Validator;
use App\resultsPacing;

class resultsPacingController extends Controller{

	public function get(){

        	$base = new base();
                $db = new dataBase();
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);
                $r = new region();
                $b = new brand();
                $pr = new pRate();
                $render = new Render();
                $region = $r->getRegion($con,false);
                $brand = $b->getBrand($con);
                $currency = $pr->getCurrency($con,false);

                $regionCurrencies = $base->currenciesByRegion();

                return view('adSales.results.7pacingGet',compact('render','region','brand','currency','regionCurrencies'));

	}

	public function post(){
                
                $rp = new resultsPacing();

                $validator = Validator::make(Request::all(),[
                        'region' => 'required',                        
                        'brand' => 'required',
                ]);

                if ($validator->fails()) {
                        return back()->withErrors($validator)->withInput();
                }

                $base = new base();
                $db = new dataBase();
                $default = $db->defaultConnection();
                $con = $db->openConnection($default);
                $r = new region();
                $b = new brand();
                $pr = new pRate();
                $render = new Render();
                $region = $r->getRegion($con,false);
                $brand = $b->getBrand($con);
                $currency = $pr->getCurrency($con,false);
                $regionCurrencies = $base->currenciesByRegion();  

                $regionID = Request::get('region');
                $tmp = $r->getRegion($con,array($regionID));
                if(is_array($tmp)){
                        $salesRegion = $tmp[0]['name'];
                }else{
                        $salesRegion = $tmp['name'];
                }
                $brandTmp = Request::get('brand');
                $brandID = $base->handleBrand($brandTmp);
                $currencyID = Request::get("currency");
                $value = Request::get('value');        

                $cYear = date('Y');
                $pYear = $cYear - 1;

                $years = array($cYear,$pYear);


                $month = $base->getMonth();

                $preMtx = $rp->construct($con,$currency,$month,$brandID,$regionID,$value);



                var_dump($regionID);
                var_dump($salesRegion);
                var_dump($brandID);
                var_dump($currencyID);
                var_dump($value);

		
	}

}

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
use App\client;
use App\agency;
use Validator;
use App\consolidateResults;

class consolidateResultsController extends Controller{
    
    public function getOffice(){

        $base = new base();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $render = new Render();
        $region = $r->getRegion($con,false);
        $regionName = Request::session()->get('userRegion'); 
        //var_dump($regionName);

        $regionCurrencies = $base->currenciesByRegion();

        return view('adSales.results.9consolidateGetOffice',compact('render','region', 'regionName'));
    }

    public function postOffice(){
        $validator = Validator::make(Request::all(),[
            'region' => 'required',                        
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $base = new base();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $pr = new pRate();
        $render = new Render();
        $region = $r->getRegion($con,false);        
        $cR = new consolidateResults();

        $regionID = Request::get('region');
        $currencyID = Request::get("currency");
        $value = Request::get('value');
        $company = Request::get('company');

        //var_dump(Request::all());      

        $cYear = intval(date('Y'));
        $pYear = $cYear - 1;

        $years = array($cYear,$pYear);

        $month = $base->getMonth();

        $typeSelectN = $cR->typeSelectN($con,$r,array($regionID));

        $mtx = $cR->constructOffice($con,$currencyID,$month,array($regionID),$value,$years,$company);
        $mtx = $cR->assemble($mtx);
        $mtxDN = $cR->addDN($mtx);        

        $currencyS = $pr->getCurrencyByRegion($con,array($currencyID))[0]['name'];

        $regionName = Request::session()->get('userRegion'); 
        $userRegionExcel = $regionName;


        $regionExcel = array($regionID);
        $currencyExcel = $currencyID;
        $valueExcel = $value;
        $title = 'Results - Consolidate Office';
        $titleExcel = 'Results - Consolidate Office.xlsx';

        if (sizeof($company) == 1) {
            if ($company[0] == 'dc') {
                $companyView = "DN";
            }else{
                $companyView = strtoupper($company[0]);    
            }
            
        }elseif(sizeof($company) == 2){
            $companyView = "$company[0] / $company[1]";
            $companyView = strtoupper($companyView);
        }
        else{
            $companyView = "WBD";
        }

        $userRegionExcel = $regionName;

        $companyExcel = $company;
        $companyViewExcel = $companyView;
        $regionExcel = array($regionID);
        $currencyExcel = $currencyID;
        $valueExcel = $value;
        $title = 'Results - Consolidate Office';
        $titleExcel = 'Results - Consolidate Office.xlsx';

        
        return view('adSales.results.9consolidateOfficePost',compact('render','region','mtx','years','mtxDN','currencyS','value','typeSelectN', 'regionExcel', 'currencyExcel','valueExcel', 'title', 'titleExcel', 'userRegionExcel','companyView','regionName','regionID', 'companyExcel', 'companyViewExcel')); 


    }

    public function getDLA(){

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

        return view('adSales.results.10consolidateDLAGet',compact('render','region'));
    }

    public function postDLA(){

        $validator = Validator::make(Request::all(),[
            'region' => 'required',                        
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
        $cl = new client();
        $ag = new agency();
        $render = new Render();
        $region = $r->getRegion($con,false);
        $brand = $b->getBrand($con);
        $currency = $pr->getCurrency($con,false);
        $sr = new salesRep();


        $regionCurrencies = $base->currenciesByRegion();

        $cR = new consolidateResults();

        $regionID = Request::get('region');

        $type = Request::get('type');

        $currencyID = Request::get("currency");
        $tmp = $pr->getCurrency($con,array($currencyID));
        $currencyIDs = $tmp;

        $currencyName = $tmp[0]['name'];

        $value = Request::get('value');        

        $cYear = date('Y');
        $pYear = $cYear - 1;

        switch ($type) {
            case 'brand':
                $brandTmp = Request::get('typeSelect');
                $brandID = $base->handleBrand($brandTmp);
                $typeSelect = $brandID;
                $typeSelectS = false;
                break;
            case 'ae':                
                $typeSelect = Request::get('typeSelect');
                for ($t=0; $t < sizeof($typeSelect); $t++) { 
                    $typeSelectS[$t] = $sr->getSalesRepById($con,array($typeSelect[$t]))[0];
                }
                break;
            case 'advertiser':                
                $typeSelectS = $cl->getClientByRegionWithValue($con,$regionID,$cYear);
                $typeSelect = $typeSelectS;
                break;
            case 'agency':                               
                $typeSelectS = $ag->getAgencyByRegionWithValue($con,$regionID,$cYear);
                $typeSelect = $typeSelectS;
                break; 
            case 'agencyGroup':  
                $typeSelectS = $ag->getAgencyGroupByRegionWithValue($con,$regionID,$cYear);
                $typeSelect = $typeSelectS;
            default:
                
                break;
        }     

        $years = array($cYear,$pYear);

        $month = $base->getMonth();

        $mtx = $cR->construct($con,$currencyIDs,$month,$type,$typeSelect,$regionID,$value);

        $mtx = $cR->assemble($mtx);

        if($type == 'advertiser' || $type == 'agency' || $type == 'agencyGroup'){
            $newMtx = $cR->newOrder($mtx);           
        }else{
            $newMtx = false;
        }

        $mtxDN = $cR->addDN($mtx);
        $regionName = Request::session()->get('userRegion'); 
        $userRegionExcel = $regionName;    
        
        $title = 'Results - Consolidate DLA';
        $titleExcel = 'Results - Consolidate DLA.xlsx';

        $typeExcel = $type;
        $regionExcel = $regionID;
        $typeSelectExcel = Request::get('typeSelect');
        $currencyExcel = $currencyID;
        $valueExcel = $value;

        return view('adSales.results.10consolidateDLAPost',compact('render','region','brand','currency','regionCurrencies','mtx','years','typeSelect','mtxDN','value','type','typeSelectS', 'title','titleExcel', 'typeExcel', 'regionExcel','typeSelectExcel', 'currencyExcel', 'valueExcel','newMtx', 'userRegionExcel','currencyName'));   
        
    	
    }

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

        return view('adSales.results.8consolidateGet',compact('render','region'));
    }

    public function post(){

        $validator = Validator::make(Request::all(),[
            'region' => 'required',
            'type' => 'required',
            'typeSelect' => 'required'                        
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
        $cl = new client();
        $ag = new agency();
        $render = new Render();
        $region = $r->getRegion($con,false);
        $brand = $b->getBrand($con);
        $currency = $pr->getCurrency($con,false);
        $sr = new salesRep();


        $regionCurrencies = $base->currenciesByRegion();

        $cR = new consolidateResults();

        $regionID = Request::get('region');

        $type = Request::get('type');

        $currencyID = Request::get("currency");
        $tmp = $pr->getCurrency($con,array($currencyID));
        $currencyIDs = $tmp;

        $currencyName = $tmp[0]['name'];
        //var_dump($currencyName);

        $value = Request::get('value');  

        $regionName = Request::session()->get('userRegion');      

        $cYear = date('Y');
        $pYear = $cYear - 1;
        $typeSelectS = array();

        switch ($type) {
            case 'brand':
                $brandTmp = Request::get('typeSelect');
                $brandID = $base->handleBrand($brandTmp);
                
                $typeSelect = $brandID;
                $typeSelectS = false;
                break;
            case 'ae':                
                $typeSelect = Request::get('typeSelect');
                for ($t=0; $t < sizeof($typeSelect); $t++) { 
                    $typeSelectS[$t] = $sr->getSalesRepById($con,array($typeSelect[$t]))[0];
                }
                break;
            case 'advertiser':      
                if ($regionID == '1') {
                    $tmp1 = $cl->getClientByRegionWithValueAleph($con,array($regionID),$cYear);         
                }else{
                    $tmp1 = $cl->getClientByRegionWithValue($con,array($regionID),$cYear);    
                }          
                /*$tmp1 = $cl->getClientByRegionWithValue($con,array($regionID),$cYear);
                $tmp2 = $cl->getClientByRegionWithValueAleph($con,array($regionID),$cYear);
                $typeSelectS = array_merge($typeSelectS,$tmp1,$tmp2);
                $typeSelectS = array_unique($typeSelectS,SORT_REGULAR);*/
                $typeSelect = array_values($tmp1);
                break;
            case 'agency':
                if ($regionID == '1') {
                    $tmp1 = $ag->getAgencyByRegionWithValueAleph($con,array($regionID),$cYear);
                }else{
                    $tmp1 = $ag->getAgencyByRegionWithValue($con,array($regionID),$cYear);    
                }             
               /* $typeSelectS = array_merge($typeSelectS,$tmp1,$tmp2);
                $typeSelectS = array_unique($typeSelectS,SORT_REGULAR);*/
                $typeSelect = array_values($tmp1);
                break; 
            case 'agencyGroup': 
                if ($regionID == '1') {
                    $tmp1 = $ag->getAgencyGroupByRegionWithValueAleph($con,array($regionID),$cYear);
                }else{
                    $tmp1 = $ag->getAgencyGroupByRegionWithValue($con,array($regionID),$cYear);
                }
               /* $tmp1 = $ag->getAgencyGroupByRegionWithValue($con,array($regionID),$cYear);
                $tmp2 = $ag->getAgencyGroupByRegionWithValueAleph($con,array($regionID),$cYear);
                $typeSelectS = array_merge($typeSelectS,$tmp1,$tmp2);
                $typeSelectS = array_unique($typeSelectS,SORT_REGULAR);*/
                $typeSelect = array_values($tmp1);
            default:
                
                break;
        }     

        $years = array($cYear,$pYear);

        $month = $base->getMonth();
        //var_dump($typeSelect);
        $mtx = $cR->construct($con,$currencyIDs,$month,$type,$typeSelect,$regionID,$value,$brand);

        $mtx = $cR->assemble($mtx);

        if($type == 'advertiser' || $type == 'agency' || $type == 'agencyGroup'){
            $newMtx = $cR->newOrder($mtx);           
        }else{
            $newMtx = false;
        }

        $mtxDN = $cR->addDN($mtx);

        $tmp = $r->getRegion($con,array($regionID));
        if(is_array($tmp)){
                $salesRegion = $tmp[0]['name'];
        }else{
                $salesRegion = $tmp['name'];
        }

        $currencyS = $pr->getCurrencyByRegion($con,array($regionID))[0]['name'];
        //var_dump($currencyS);
        
        
        $title = 'Results - Consolidate';
        $titleExcel = 'Results - Consolidate.xlsx';

        $typeExcel = $type;
        $regionExcel = $regionID;
        $typeSelectExcel = Request::get('typeSelect');
        $currencyExcel = $currencyID;
        $valueExcel = $value;
        $userRegionExcel = $regionName;

        //var_dump($typeSelect);
        return view('adSales.results.8consolidatePost',compact('render','region','brand','currency','regionCurrencies','mtx','years','typeSelect','mtxDN','salesRegion','currencyS','value','type','typeSelectS', 'title','titleExcel', 'typeExcel', 'regionExcel','typeSelectExcel', 'currencyExcel', 'valueExcel','newMtx', 'userRegionExcel','currencyName'));   
        
        
    }
}

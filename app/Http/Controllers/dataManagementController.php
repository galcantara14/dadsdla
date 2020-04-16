<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataManagement;
use App\dataBase;
use App\dataManagementRender;

use App\agency;
use App\client;
use App\brand;
use App\region;
use App\User;
use App\queries;
use App\salesRep;
use App\origin;
use App\matchingClientAgency;
use App\sql;
use App\pRate;
use App\emailDivulgacao;

class dataManagementController extends Controller{
    
    public function dataCurrentThroughtG(){
        
        $db = new dataBase();
        
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        
        $sql = new sql();

        $select = "SELECT * FROM sources_date";

        $res = $con->query($select);
        $from = array("source","current_throught");

        $list = $sql->fetch($res,$from,$from);

        for ($l=0; $l < sizeof($list); $l++) { 
            if($list[$l]['source'] == "CMAPS"){
                $cmaps = $list[$l]['current_throught'];
            }elseif($list[$l]['source'] == "SF"){
                $sf = $list[$l]['current_throught'];
            }elseif($list[$l]['source'] == "FW"){
                $fw = $list[$l]['current_throught'];
            }elseif ($list[$l]['source'] == 'INSIGHTS') {
                $insights = $list[$l]['current_throught'];
            }else{
                $bts = $list[$l]['current_throught'];
            }
        }

        $newList = array("cmaps" => $cmaps, "bts" => $bts, "fw" => $fw, "sf" => $sf, "insights" => $insights);

        return view('dataManagement.dataCurrentThrought',compact('newList'));
    }

    public function dataCurrentThroughtP(){
        $db = new dataBase();
        
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $cmapsInfo = Request::get('cmapsInfo');
        $crmInfo = Request::get('crmInfo');
        $freeWheelInfo = Request::get('freeWheelInfo');
        $btsInfo = Request::get('btsInfo');
        $insightsInfo = Request::get('insightsInfo');

        $list = array( 
                        array("name" => "BTS","value" => $btsInfo),
                        array("name" => "CMAPS","value" => $cmapsInfo),
                        array("name" => "FW","value" => $freeWheelInfo),
                        array("name" => "SF","value" => $crmInfo),
                        array('name' => "INSIGHTS","value" => $insightsInfo)
        );

        $count = 0;
        $error = array();
        for ($l=0; $l < sizeof($list); $l++) { 
            $up[$l] = "UPDATE sources_date SET current_throught = \"".$list[$l]['value']."\" WHERE (source = \"".$list[$l]['name']."\") ";
            
            if( $con->query($up[$l]) === TRUE ){
                echo "Record updated successfully <br>";
                $count ++;
            }else{
                $err = "Error updating record: " . $con->error;
                array_push($error, $err);
                echo "Error updating record: " . $con->error ."<br>";
            }
        }

        if( $count == (sizeof($list)) ){
            $rtr = array("success" => true ,"error" => false);
            $message = "Dates was successfully update !!!";
        }else{
            $rtr = array("success" => false ,"error" => $error);
            $message = "Error on update !!!";
        }

        return back()->with('currentThrought',$message);
    }

    public function fixCRM(){
        $db = new dataBase();
        $sql = new sql();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $select = "SELECT oppid , gross_revenue , net_revenue , fcst_amount_gross , fcst_amount_net , COUNT(oppid) AS 'repeat'
                   FROM sf_pr 
                   GROUP BY oppid , gross_revenue , net_revenue , fcst_amount_gross , fcst_amount_net
                  ";

        $result = $con->query($select);

        $from = array("oppid","gross_revenue","net_revenue","fcst_amount_gross","fcst_amount_net","repeat");

        $toFix = $sql->fetch($result,$from,$from);

        $cc = 0;

        $updated = 0;
        for ($f=0; $f < sizeof($toFix) ; $f++) { 
            if($toFix[$f]['repeat'] > 0 && $toFix[$f]['repeat'] != 1){
                $update[$cc] = "UPDATE sf_pr 
                                    SET 
                                        net_revenue = \"".($toFix[$f]['net_revenue']/$toFix[$f]['repeat'])."\",
                                        fcst_amount_gross = \"".($toFix[$f]['fcst_amount_gross']/$toFix[$f]['repeat'])."\",
                                        fcst_amount_net = \"".($toFix[$f]['fcst_amount_net']/$toFix[$f]['repeat'])."\"
                                    WHERE(oppid = \"".$toFix[$f]['oppid']."\")
                               ";
                
                if( $con->query($update[$cc]) === TRUE ){
                    $updated ++;
                }
                $cc ++;
            }
        }

        if($updated == $cc){
            $rtr  = TRUE;
        }else{
            $rtr = FALSE;            
        }

        if($rtr){
            return back()->with('CRMFixSuccess',"The sf_pr Table was succesfully updated :)");
        }        
    }

    public function home(){
    	return view('dataManagement.home');
    }
    
    public function relationships(){
        
    }    

    public function ytdLatamGet(){
        return view('dataManagement.ytdLatamGet');
    }

    /*START OF REGIONS FUNCTIONS*/

    public function regionAdd(){
        $sql = new sql(); 
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bool = $r->addRegion($con);

        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function regionGet(){
    	$sql = new sql();
    	$r = new region();
    	$db = new dataBase();
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $region = $r->getRegion($con,false);
    	$render = new dataManagementRender();
    	return view('dataManagement.regionGet',compact('region','render'));
    }

    public function regionEditGet(){
        $sql = new sql();
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $region = $r->getRegion($con,false);
        $render = new dataManagementRender();
        return view('dataManagement.edit.editRegion',compact('region','render'));
    }

    public function regionEditPost(){
        $sql = new sql();
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $region = $r->getRegion($con,false);
        $bool = $r->editRegion($con);        
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }
    /*END OF REGIONS FUNCTIONS*/
    /*START OF USER FUNCTIONS*/
    public function userAdd(){
        $usr = new User();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bool = $usr->addUser($con);

        if($bool['bool']){
            return back()->with('addUser',$bool['msg']);
        }else{
            return back()->with('errorAddUser',$bool['msg']);
        }
    }

    public function userGet(){
        $sql = new sql();
        $r = new region();
        $db = new dataBase();
        $usr = new User();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $region = $r->getRegion($con,false);
        $user = $usr->getUser($con, null);
        $userType = $usr->getUserType($con);
        $render = new dataManagementRender();

    	return view('dataManagement.userGet',compact('user','userType','region','render'));

    }

    public function userEditFilter(){
        $sql = new sql();
        $sr = new salesRep();
        $r = new region();
        $db = new dataBase();
        $usr = new User();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        
        if (!is_null(Request::get('filterRegion'))) {
            $filter = array(Request::get('filterRegion'));
        }else{
            $filter = null;
        }

        $region = $r->getRegion($con,null);
        $regionFilter = $r->getRegion($con,$filter);
        
        if (!is_null(Request::get('filterRegion'))) {
            $filters = array();
            for ($i=0; $i <sizeof($regionFilter) ; $i++) { 
                array_push($filters, $regionFilter[$i]["id"]);
            }
        }else{
            $filters = null;
        }

        $render = new dataManagementRender();
        $userType = $usr->getUserType($con);

        if ( !is_null( Request::get('size') ) ) {
           $bool = $usr->editUser($con);
        }else{
            $bool = false;
        }

        for ($i=0; $i <sizeof($region) ; $i++) { 
            $salesGroup[$region[$i]["name"]] = $sr->getSalesRepGroup($con,array($region[$i]["id"]));
        }
        
        $user = $usr->getUser($con,$filters);        
        return view('dataManagement.edit.editUser',compact('user','region','render','userType','salesGroup','bool'));
    }

    public function UserTypeAdd(){
        $usr = new User();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bool = $usr->addUserType($con);

        if($bool){
            return back()->with('addUserType',$bool['msg']);
        }else{
            return back()->with('errorUserType',$bool['msg']);
        }

    }

    public function userTypeEditGet(){
        $usr = new User();
        $db = new dataBase();
        $render = new dataManagementRender();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $userType = $usr->getUserType($con);
        
        return view('dataManagement.edit.editUserType',compact('userType','render'));
    }

    public function userTypeEditPost(){
        $usr = new User();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $bool = $usr->editUserType($con);


        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }


    }

    /*END OF USER FUNCTIONS*/

    /*START OF P-RATE FUNCTIONS*/

    public function pRateAdd(){
        $sql = new sql();
        $db = new dataBase();
        $p = new pRate();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bool = $p->addPRate($con,false);
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function pRateGet(){
        $sql = new sql();
        $r = new region();
        $p = new pRate();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $region = $r->getRegion($con,false);
        $currency = $p->getCurrency($con);
        $pRate = $p->getPRate($con,null);
        $cYear = date('Y');
        $render = new dataManagementRender();
        return view('dataManagement.pRateGet',compact('region','currency','pRate','cYear','render'));
    }

    public function pRateEditGet(){
        $sql = new sql();
        $r = new region();
        $p = new pRate();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $region = $r->getRegion($con,"");
        $currency = $p->getCurrency($con);
        $pRate = $p->getPRate($con,null);
        $cYear = date('Y');
        $render = new dataManagementRender();

        return view('dataManagement.edit.editPRate',compact('region','currency','pRate','cYear','render'));
    }

    public function pRateEditPost(){
        $p = new pRate();
        $sql = new sql(); 
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bool = $p->editPRate($con);

        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            //return back()->with('error',$bool['msg']);
        }
    }

    public function currencyAdd(){
        $p = new pRate();
        $sql = new sql(); 
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bool = $p->addCurrency($con);
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function currencyEditGet(){
        $sql = new sql();
        $r = new region();
        $p = new pRate();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $region = $r->getRegion($con,false);
        $currency = $p->getCurrency($con);
        $pRate = $p->getPRate($con,null);
        $cYear = date('Y');
        $render = new dataManagementRender();
        return view('dataManagement.edit.editCurrency',compact('region','currency','pRate','cYear','render'));
    }

    public function currencyEditPost(){
        $db = new dataBase();
        $p = new pRate();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $bool = $p->editCurrency($con);
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }   
    }

    /*END OF P-RATE FUNCTIONS*/


    /*START OF SALES REP FUNCTIONS*/
    public function salesRepGroupAdd(){
        $sql = new sql(); 
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sr = new salesRep();
        $bool = $sr->addSalesRepGroup($con);

        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function salesRepGet(){
        $o = new origin();
        $sql = new sql(); 
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sr = new salesRep();
        $region = $r->getRegion($con,false);
        $salesRepGroup = $sr->getSalesRepGroup($con,false);
        $salesRep = $sr->getSalesRep($con,false);       
        $salesRepGroupingReps = $sr->getSalesRepGroupingReps($con,false);   

        $salesRepUnit = $sr->getSalesRepUnit($con,false);       
        $origin = $o->getOrigin($con,false);
        $render = new dataManagementRender();

        return view('dataManagement.salesRepGet',compact('region','salesRepGroup','salesRep','salesRepUnit','origin','render'));
    }

    public function salesRepAdd(){
        $sql = new sql(); 
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sr = new salesRep();
        $bool = $sr->addSalesRep($con);
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function salesRepEditFilter(){
        $db = new dataBase();
        $r = new region();
        $sr = new salesRep();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $render = new dataManagementRender();


        $temp = Request::get("filterRegion");
        $filter = array();
        if ($temp != null) {
            array_push($filter, $temp);
        }else{
            $filter = null;
        }

        $region = $r->getRegion($con,null);

        if ( !is_null( Request::get('size') ) ) {
           $bool = $sr->editSalesRep($con);
        }else{
            $bool = false;
        }

        $salesRep = $sr->getSalesRepByRegion($con,$filter); 

        for ($i=0; $i <sizeof($region) ; $i++) { 
            $salesGroup[$region[$i]["name"]] = $sr->getSalesRepGroup($con,array($region[$i]["id"]));
        }

        return view('dataManagement.edit.editSalesRep',compact('salesRep','render','region','salesGroup'));
    }

    public function salesRepUnitAdd(){
        $sql = new sql(); 
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sr = new salesRep();
        $bool = $sr->addSalesRepUnit($con);
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function salesRepUnitEditFilter(){
        $o = new origin();
        $sql = new sql(); 
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $sr = new salesRep();
        $region = $r->getRegion($con,false);
        $salesRepGroup = $sr->getSalesRepGroup($con,false);
        $salesRep = $sr->getSalesRep($con,false);       
        $origin = $o->getOrigin($con,false);
        $render = new dataManagementRender();

        if (!is_null(Request::get('filterRep'))) {
            $filter = array(Request::get('filterRep'));
        }else{
            $filter = null;
        }


        if (!is_null(Request::get('size'))) {
            $bool = $sr->editSalesRepUnit($con);
        }else{
            $bool = null;
        }




        $salesRepUnit = $sr->getSalesRepUnit($con,$filter);       

        return view('dataManagement.edit.editSalesRepUnit',compact('salesRep','salesRepUnit','salesRepGroup','origin','render','region'));       
    }

    public function salesRepGroupEditFilter(){
        $dm = new dataManagement();
        $db = new dataBase();
        $r = new region();
        $sr = new salesRep();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $render = new dataManagementRender();

        $temp = Request::get("filterRegion");
        $filter = array();
        if ($temp != null) {
            array_push($filter, $temp);
        }

        $region = $r->getRegion($con,null);

        if ( !is_null( Request::get('size') ) ) {
           $bool = $sr->editSalesRepGroup($con);
        }else{
            $bool = false;
        }

        $salesRepGroup = $sr->getSalesRepGroup($con,$filter);

        return view('dataManagement.edit.editSalesRepGroup',compact('salesRepGroup','region','render'));

    } 


    /*END OF SALES REP FUNCTIONS*/

    /*START OF AGENCY FUNCTIONS*/

    public function newAgencyAdd(){
        $sql = new sql();
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $ag = new agency();
        $agencyGroupID = array( Request::get('agencyGroup') );
        $agencyGroup = $ag->getAgencyGroup($con,$agencyGroupID);
        


        var_dump($agencyGroup);
        var_dump("New Agency Add");

    }

    public function newAgencyGroupAdd(){
        $sql = new sql();
        $r = new region();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $ag = new agency();

        $regionID = Request::get('region');
        $agencyGroupName = Request::get('createAgencyGroup');

        $table = 'agency_group';
        $columns = 'region_id,name';
        $values = " \" ".$regionID." \" , \" ".$agencyGroupName." \"  ";

        $bool = $sql->insert($con,$table,$columns,$values);

        if($bool){

            return view("dataManagement.ytdLatamPost",compact('tmpSheet','clientMissMatches','agencyMissMatches','region','agency','client','agencyGroup','clientGroup'));
            
        }else{

        }
    }

    public function agencyAdd(){

    }

    public function agencyGetFromExcel(){

        return view('dataManagement.agencyGetFromExcel');

    }

    

    /*END OF SALES AGENCY FUNCTIONS*/

    /*START OF CLIENT FUNCTIONS*/

    public function clientGetFromExcel(){

        return view('dataManagement.clientGetFromExcel');

    }

    


    

    public function newClientAdd(){
        var_dump("New Client Add");
    }

    public function newClientGroupAdd(){
        var_dump("New Client Group Add");
    }
    

    /*END OF SALES CLIENT FUNCTIONS*/
    
    /*START OF ORIGIN FUNCTIONS*/

    public function originAdd(){
        $o = new origin();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bool = $o->addOrigin($con);
        
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function originGet(){
        $o = new origin();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $origin = $o->getOrigin($con,false);
        $render = new dataManagementRender();

        return view('dataManagement.originGet',compact('origin','render'));
    }

    /*END OF ORIGIN FUNCTIONS*/

    /*START OF BRAND FUNCTIONS*/

    public function brandAdd(){
        $sql = new sql();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $b = new brand();
        $bool = $b->addBrand($con);
        
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }        
    }

    public function brandGet(){
        $sql = new sql();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $b = new brand();
        $o = new origin();
        $brand = $b->getBrand($con,false);
        $brandUnit = $b->getBrandUnit($con,false);
        $origin = $o->getOrigin($con,false);
        if(!$origin && !$brand){
            $state = "disabled='true'";
        }else{
            $state = false;
        }
        $render = new dataManagementRender();
        return view('dataManagement.brandGet',compact('brand','brandUnit','origin','state','render'));
    }

    public function brandUnitAdd(){
        $b = new brand();
        $sql = new sql();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $bool = $b->addBrandUnit($con);        
        if($bool){
            return back()->with('response',$bool['msg']);
        }else{
            return back()->with('error',$bool['msg']);
        }
    }

    public function emailDivulgacaoGet(){
        
        $email = new emailDivulgacao();

        $to = "guilherme_costa@discoverybrasil.com";
        $subject = "teste";
        $message = $email->getMessage();
        
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        $headers[] = 'From: TesteChangePassword <d_ads@discovery.com>';

        $res = mail($to, $subject, $message, implode("\r\n", $headers));

        var_dump($res);
    }

    /*END OF BRAND FUNCTIONS*/
/*
    public function truncateGet(){

        $queries = new queries();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        return view('dataManagement.truncateCheck');
    }

    public function trueTruncateGet(){

        $queries = new queries();
        $db = new dataBase();
        $con = $db->openCOnnection("DLA");

        $queries->truncateAll($con);

        return view('dataManagement.home');
    }
*/
    

    

    

    

    

    

    

    
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\region;
use App\PAndRRender;
use App\salesRep;
use App\pRate;
use App\dataBase;
use App\VP;

class VPController extends Controller
{
    public function get(){

    	$db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new PAndRRender();
        $pr = new pRate();

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

		return view('pAndR.VPView.get',compact('render','region','currency'));
    }

    public function post(){
        var_dump(Request::all());
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new PAndRRender();
        $pr = new pRate();
        $vp = new vp();

        $regionID = Request::get("region");

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

        $forRender = $vp->base($con,$regionID);

        
        var_dump($forRender);
        return view('pAndR.VPView.post',compact('render','region','currency'));
    }
}

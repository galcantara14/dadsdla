<?php

namespace App\Http\Controllers;

use App\base;
use App\brand;
use App\salesRep;
use App\region;
use App\PAndRRender;
use App\AE;

use App\pRate;
use App\sql;
use App\dataBase;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

use App\Exports\aeExport;


class aeExcelController extends Controller{
    
    public function aeView(){
        $db = new dataBase();
        $b = new base();
        $render = new PAndRRender();
        $r = new region();
        $pr = new pRate();
        $ae = new AE();     
        $sr = new salesRep();   
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $cYear = date('Y');
        $pYear = $cYear - 1;
        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false)[0]['name'];
        $permission = Request::session()->get('userLevel');
        $user = Request::session()->get('userName');
        $title = Request::get('title');
        $typeExport = Request::get('typeExport');
        $regionID = (int) Request::get('region');
        $salesRepID = Request::get('salesRep');
        $currencyID = (int) Request::get('currency');
        $value = Request::get('value');
        $regionName = Request::session()->get('userRegion');

        $salesRepName = $sr->getSalesRepById($con,array($salesRepID));
        
        $aeTable = $ae->makeRepTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value);
        
        //var_dump($aeTable['total']);
        
        $clientsTable = $ae->makeClientsTable($con,$salesRepID,$pr,$cYear,$pYear,$regionID,$currencyID,$value);   
        
        $title = "Forecast.xlsx";
        $titleExcel = "Forecast.xlsx";      
     	
        $label = "exports.PandR.AE.aeConsolidateExport";

         $auxTitle = $title;

        $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');
        $monthConsolidate = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
        $company = array('3','1','2');

        for ($c=0; $c < sizeof($company); $c++) { 
            if ($company[$c] == '1') {
                $color[$c] = '#0070c0;';
                $companyView[$c] = 'DSC';
            }elseif ($company[$c] == '2') {
                $color[$c] = '#000000;';
                $companyView[$c] = 'SPT';
            }elseif ($company[$c]) {
                $color[$c] = '#0f243e;';
                $companyView[$c] = 'WM';
            }
        }

       	$data = array('aeTable' => $aeTable, 'clientsTable' => $clientsTable, 'cYear' => $cYear, "pYear" => $pYear, "salesRepName" => $salesRepName, "currency" => $currency, "month" => $month, 'company' => $company, 'color' => $color,'companyView' => $companyView, 'value' => $value,'monthConsolidate' => $monthConsolidate);

       	return Excel::download(new aeExport($data, $label, $typeExport, $auxTitle), $title);
    }
}

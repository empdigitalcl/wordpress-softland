<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Woocommerce;
use App\Sync;

class SoftlandController extends Controller
{
    //
    const IVA = 1.19;
    private $accessToken;
    private $endPoint;
    public function __construct(
    )
    {
      $this->endPoint = env('LAUDUS_ENDPOINT') != '' ? env('LAUDUS_ENDPOINT') : 'https://erp.laudus.cl/api/';
      $this->user = env('LAUDUS_USER') != '' ? env('LAUDUS_USER') : '';
      $this->password = env('LAUDUS_PASSWORD') != '' ? env('LAUDUS_PASSWORD') : '';
      $this->companyVatId = env('LAUDUS_COMPANY_VAT_ID') != '' ? env('LAUDUS_COMPANY_VAT_ID') : '';
      $this->wharehouseId = env('LAUDUS_WAREHOUSE_ID') != '' ? env('LAUDUS_WAREHOUSE_ID') : '';
      $this->laudusToken = null;
    }
    public function index(){
        return 'Softland Controller';
    }
    public function config(){
        /* echo 'user = '.env('LAUDUS_USER').'<br>';
        echo 'password = '.env('LAUDUS_PASSWORD').'<br>'; */
        echo 'companyVatId = '.env('LAUDUS_COMPANY_VAT_ID').'<br>';
        echo 'wharehouseId = '.env('LAUDUS_WAREHOUSE_ID').'<br>';
    }
    
}

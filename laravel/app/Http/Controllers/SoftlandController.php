<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Woocommerce;
use App\Sync;

class SoftlandController extends Controller
{

    public function index()
    {
        return $this->getObtenerCatalogoProductosResponse();
    }

    private function getObtenerCatalogoProductosResponse()
    {
        $dataRaw = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <AuthHeader xmlns="http://softland.cl/">
      <Username>STORE</Username>
      <Password>softland</Password>
    </AuthHeader>
  </soap:Header>
  <soap:Body>
    <ObtenerCatalogoProductos xmlns="http://softland.cl/">
      <Empresa>string</Empresa>
      <esParaWeb>False</esParaWeb>
      <CodBode>string</CodBode>
      <listaPrecio>string</listaPrecio>
      <soloActivos>False</soloActivos>
    </ObtenerCatalogoProductos>
  </soap:Body>
</soap:Envelope>';
        $url = 'http://web.softlandcloud.cl/ecommerce/WSProducto.asmx?WSDL';
        $response = postSoapCurlRequest($url, null, $dataRaw);
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
        $xml = new \SimpleXMLElement($response);
        if ($xml->soapBody && $xml->soapBody->ObtenerCatalogoProductosResponse && $xml->soapBody->ObtenerCatalogoProductosResponse->ObtenerCatalogoProductosResult && $xml->soapBody->ObtenerCatalogoProductosResponse->ObtenerCatalogoProductosResult->productoUni) {
            $results = $xml->soapBody->ObtenerCatalogoProductosResponse->ObtenerCatalogoProductosResult;
            $products = [];
            foreach ($results->productoUni as $key => $val) {
                $products[] = $val;
            }
            // return json_encode($products);
            return response()->json($products, 200);
        } else {
            dd($xml);
        }
    }
}

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

    private function getObtenerProductosPorCodigo()
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
            <ObtenerProductosPorCodigo xmlns="http://softland.cl/">
              <codigo>4025066125142</codigo>
              <Empresa>CORSE1</Empresa>
              <Token></Token>
            </ObtenerProductosPorCodigo>
          </soap:Body>
        </soap:Envelope>';
        $url = 'http://web.softlandcloud.cl/ecommerce/WSProducto.asmx?WSDL';
        $response = postSoapCurlRequest($url, null, $dataRaw);
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
        $xml = new \SimpleXMLElement($response);
        dd($xml);
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
      <Empresa>CORSE1</Empresa>
      <esParaWeb>true</esParaWeb>
      <CodBode>01</CodBode>
      <listaPrecio></listaPrecio>
      <soloActivos>true</soloActivos>
    </ObtenerCatalogoProductos>
  </soap:Body>
</soap:Envelope>';
        $url = 'http://web.softlandcloud.cl/ecommerce/WSProducto.asmx?WSDL';
        $response = postSoapCurlRequest($url, null, $dataRaw);
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
        dd($response);
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
    private function getObtenerProductosPorDescripcion()
    {
        //4025066125142
        $dataRaw = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sof="http://softland.cl/">
        <soapenv:Header>
           <sof:AuthHeader>
              <!--Optional:-->
              <sof:Username>STORE</sof:Username>
              <!--Optional:-->
              <sof:Password>softland</sof:Password>
           </sof:AuthHeader>
        </soapenv:Header>
        <soapenv:Body>
           <sof:ObtenerProductosPorDescripcion>
              <!--Optional:-->
              <sof:codigo>para</sof:codigo>
              <!--Optional:-->
              <sof:Empresa>CORSE1</sof:Empresa>
              <!--Optional:-->
              <sof:Token></sof:Token>
           </sof:ObtenerProductosPorDescripcion>
        </soapenv:Body>
     </soapenv:Envelope>';
        $url = 'http://web.softlandcloud.cl/ecommerce/WSProducto.asmx?WSDL';
        $response = postSoapCurlRequest($url, null, $dataRaw);
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
        $xml = new \SimpleXMLElement($response);
        if ($xml->soapBody && $xml->soapBody->ObtenerProductosPorDescripcionResponse && $xml->soapBody->ObtenerProductosPorDescripcionResponse->ObtenerProductosPorDescripcionResult && $xml->soapBody->ObtenerProductosPorDescripcionResponse->ObtenerProductosPorDescripcionResult->productoUni) {
            $results = $xml->soapBody->ObtenerProductosPorDescripcionResponse->ObtenerProductosPorDescripcionResult;
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

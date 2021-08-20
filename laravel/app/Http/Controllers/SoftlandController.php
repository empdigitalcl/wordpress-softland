<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Woocommerce;
use App\Sync;

class SoftlandController extends Controller
{

  public function index()
  {
    //return $this->getObtenerCatalogoProductosResponse();
    //return $this->getObtenerProductosPorCodigo();
    //return $this->getObtenerStockPorBodega();
    return $this->postIngresaNotadeVenta();
  }



  public function syncProducts()
  {
    $products = $this->getObtenerProductosPorDescripcion();
    $session = date('YmdHis');
    if (count($products) > 0) {
      foreach ($products as $product) {
        $sync = Sync::BySku($product->codprod)->first();
        if (!$sync) {
          $sync = new Sync();
          $sync->status = 1;
          $sync->sku = $product->codprod;
        }
        $isUpdate = false;
        echo $product->codprod . '>' . $product->precvta . ' !=' . $sync->netPrice . '<br>';
        if ($product->precvta != $sync->netPrice) {
          $isUpdate = true;
          $sync->netPrice = $product->precvta;
        }
        if ($isUpdate) {
          $sync->status = 1;
        }
        // $sync->soflandProductId = null;
        // $sync->softlandProductId = null;
        $sync->session = $session;
        $sync->save();
      }
    }
  }



  private function postIngresaNotadeVenta()
  {
    $nvPorcFlete = "";
    $nvValflete = "";
    $nvPorcEmb = "";
    $nvValEmb = "";
    $nvEquiv = "";
    $nvNetoExento = "";
    $nvNetoAfecto = "";
    $nvTotalDesc = "";
    $ConcAuto = "";
    $NumGuiaRes = "";
    $CheckeoPorAlarmaVtas = "";
    $FechaHoraCreacion = "";
    $ConcManual = "";
    $RutSolicitante = "";
    $TotalBoleta = "";
    $NumReq = "";
    $EnMantencion = "";
    $nvFeAprob = "";
    $nvMonto = "";
    $nvSubTotal = "";
    $RutCliente = "";
    $nvFem = "";
    $nvEstado = "";
    $nvEstFact = "";
    $nvEstDesp = "";
    $nvEstRese = "";
    $nvEstConc = "";
    $CotNum = "";
    $NumOC = "";
    $nvFeEnt = "";
    $VenCod = "";
    $CodMon = "";
    $CodLista = "";
    $nvObser = "";
    $nvCanalNV = "";
    $CveCod = "";
    $NomCon = "";
    $CodiCC = "";
    $CodBode = "";
    $CodLugarDesp = "";
    $CorreoCliente = "";
    $TipoDoctoVta = "";
    $ValorPorcentualImpuesto = "";
    $AfectoAImpuesto = "";
    $MontoImpuesto = "";
    $nvPorcDesc01 = "";
    $nvPorcDesc02 = "";
    $nvPorcDesc03 = "";
    $nvPorcDesc04 = "";
    $nvPorcDesc05 = "";
    $nvDescto01 = "";
    $nvDescto02 = "";
    $nvDescto03 = "";
    $nvDescto04 = "";
    $nvDescto05 = "";
    $CantUVta = "";
    $CodUMed = "";
    $CodPromocion = "";
    $CheckeoMovporAlarmaVtas = "";
    $DetProd = "";
    $nvCantOC = "";
    $nvCantBoleta = "";
    $nvCantNC = "";
    $nvCantDevuelto = "";
    $nvCantFact = "";
    $Partida = "";
    $nvCantProd = "";
    $nvTotLinea = "";
    $nvSubTotal = "";
    $nvEquiv = "";
    $nvPrecio = "";
    $nvCant = "";
    $CodProd = "";
    $nvFecCompr = "";
    $nvCorrela = "";
    $nvLinea = "";
    $nvCantDesp = "";
    $Pieza = "";
    $nvDPorcDesc01 = "";
    $nvDPorcDesc02 = "";
    $nvDPorcDesc03 = "";
    $nvDPorcDesc04 = "";
    $nvDPorcDesc05 = "";
    $nvDDescto01 = "";
    $nvDDescto02 = "";
    $nvDDescto03 = "";
    $nvDDescto04 = "";
    $nvDDescto05 = "";
    $nvTotDesc = "";
    $nombreContactoFacturaBoleta = "";
    $Token = "";
    $enviaPdf = "";



    $dataRaw = '<x:Envelope xmlns:x="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sof="http://softland.cl/">
    <x:Header>
        <sof:AuthHeader>
            <sof:Username>STORE</sof:Username>
            <sof:Password>softland</sof:Password>
        </sof:AuthHeader>
    </x:Header>
    <x:Body>
        <sof:IngresaNotadeVenta>
            <sof:Empresa>CORSE1</sof:Empresa>
            <sof:notaVenta>
                <sof:Cabecera>
                    <sof:nvPorcFlete>' & nvPorcFlete & '</sof:nvPorcFlete>
                    <sof:nvValflete>' & nvValflete & '</sof:nvValflete>
                    <sof:nvPorcEmb>' & nvPorcEmb & '</sof:nvPorcEmb>
                    <sof:nvValEmb>' & nvValEmb & '</sof:nvValEmb>
                    <sof:nvEquiv>' & nvEquiv & '</sof:nvEquiv>
                    <sof:nvNetoExento>' & nvNetoExento & '</sof:nvNetoExento>
                    <sof:nvNetoAfecto>' & nvNetoAfecto & '</sof:nvNetoAfecto>
                    <sof:nvTotalDesc>' & nvTotalDesc & '</sof:nvTotalDesc>
                    <sof:ConcAuto>' & ConcAuto & '</sof:ConcAuto>
                    <sof:NumGuiaRes>' & NumGuiaRes & '</sof:NumGuiaRes>
                    <sof:CheckeoPorAlarmaVtas>' & CheckeoPorAlarmaVtas & '</sof:CheckeoPorAlarmaVtas>
                    <sof:FechaHoraCreacion>' & FechaHoraCreacion & '</sof:FechaHoraCreacion>
                    <sof:ConcManual>' & ConcManual & '</sof:ConcManual>
                    <sof:RutSolicitante>' & RutSolicitante & '</sof:RutSolicitante>
                    <sof:TotalBoleta>' & TotalBoleta & '</sof:TotalBoleta>
                    <sof:NumReq>' & NumReq & '</sof:NumReq>
                    <sof:EnMantencion>' & EnMantencion & '</sof:EnMantencion>
                    <sof:nvFeAprob>' & nvFeAprob & '</sof:nvFeAprob>
                    <sof:nvMonto>' & nvMonto & '</sof:nvMonto>
                    <sof:nvSubTotal>' & nvSubTotal & '</sof:nvSubTotal>
                    <sof:RutCliente>' & RutCliente & '</sof:RutCliente>
                    <sof:nvFem>' & nvFem & '</sof:nvFem>
                    <sof:nvEstado>' & nvEstado & '</sof:nvEstado>
                    <sof:nvEstFact>' & nvEstFact & '</sof:nvEstFact>
                    <sof:nvEstDesp>' & nvEstDesp & '</sof:nvEstDesp>
                    <sof:nvEstRese>' & nvEstRese & '</sof:nvEstRese>
                    <sof:nvEstConc>' & nvEstConc & '</sof:nvEstConc>
                    <sof:CotNum>' & CotNum & '</sof:CotNum>
                    <sof:NumOC>' & NumOC & '</sof:NumOC>
                    <sof:nvFeEnt>' & nvFeEnt & '</sof:nvFeEnt>
                    <sof:CodAux>' & CodAux & '</sof:CodAux>
                    <sof:VenCod>' & VenCod & '</sof:VenCod>
                    <sof:CodMon>' & CodMon & '</sof:CodMon>
                    <sof:CodLista>' & CodLista & '</sof:CodLista>
                    <sof:nvObser>' & nvObser & '</sof:nvObser>
                    <sof:nvCanalNV>' & nvCanalNV & '</sof:nvCanalNV>
                    <sof:CveCod>' & CveCod & '</sof:CveCod>
                    <sof:NomCon>' & NomCon & '</sof:NomCon>
                    <sof:CodiCC>' & CodiCC & '</sof:CodiCC>
                    <sof:CodBode>' & CodBode & '</sof:CodBode>
                    <sof:CodLugarDesp>' & CodLugarDesp & '</sof:CodLugarDesp>
                    <sof:CorreoCliente>' & CorreoCliente & '</sof:CorreoCliente>
                    <sof:TipoDoctoVta>' & TipoDoctoVta & '</sof:TipoDoctoVta>
                    <sof:impuestos>
                        <sof:ImpuestoNV>
                            <sof:CodImpto>IVA</sof:CodImpto>
                            <sof:ValorPorcentualImpuesto>' & ValorPorcentualImpuesto & '</sof:ValorPorcentualImpuesto>
                            <sof:AfectoAImpuesto>' & AfectoAImpuesto & '</sof:AfectoAImpuesto>
                            <sof:MontoImpuesto>' & MontoImpuesto & '</sof:MontoImpuesto>
                        </sof:ImpuestoNV>
                    </sof:impuestos>
                    <sof:nvPorcDesc01>' & nvPorcDesc01 & '</sof:nvPorcDesc01>
                    <sof:nvPorcDesc02>' & nvPorcDesc02 & '</sof:nvPorcDesc02>
                    <sof:nvPorcDesc03>' & nvPorcDesc03 & '</sof:nvPorcDesc03>
                    <sof:nvPorcDesc04>' & nvPorcDesc04 & '</sof:nvPorcDesc04>
                    <sof:nvPorcDesc05>' & nvPorcDesc05 & '</sof:nvPorcDesc05>
                    <sof:nvDescto01>' & nvDescto01 & '</sof:nvDescto01>
                    <sof:nvDescto02>' & nvDescto02 & '</sof:nvDescto02>
                    <sof:nvDescto03>' & nvDescto03 & '</sof:nvDescto03>
                    <sof:nvDescto04>' & nvDescto04 & '</sof:nvDescto04>
                    <sof:nvDescto05>' & nvDescto05 & '</sof:nvDescto05>
                </sof:Cabecera>
                <sof:Detalles>
                    <sof:NotaVentaDetalleDTO>
                        <sof:CantUVta>' & CantUVta & '</sof:CantUVta>
                        <sof:CodUMed>' & CodUMed & '</sof:CodUMed>
                        <sof:CodPromocion>' & CodPromocion & '</sof:CodPromocion>
                        <sof:CheckeoMovporAlarmaVtas>' & CheckeoMovporAlarmaVtas & '</sof:CheckeoMovporAlarmaVtas>
                        <sof:DetProd>' & DetProd & '</sof:DetProd>
                        <sof:nvCantOC>' & nvCantOC & '</sof:nvCantOC>
                        <sof:nvCantBoleta>' & nvCantBoleta & '</sof:nvCantBoleta>
                        <sof:nvCantNC>' & nvCantNC & '</sof:nvCantNC>
                        <sof:nvCantDevuelto>' & nvCantDevuelto & '</sof:nvCantDevuelto>
                        <sof:nvCantFact>' & nvCantFact & '</sof:nvCantFact>
                        <sof:Partida>' & Partida & '</sof:Partida>
                        <sof:nvCantProd>' & nvCantProd & '</sof:nvCantProd>
                        <sof:nvTotLinea>' & nvTotLinea & '</sof:nvTotLinea>
                        <sof:nvSubTotal>' & nvSubTotal & '</sof:nvSubTotal>
                        <sof:nvEquiv>' & nvEquiv & '</sof:nvEquiv>
                        <sof:nvPrecio>' & nvPrecio & '</sof:nvPrecio>
                        <sof:nvCant>' & nvCant & '</sof:nvCant>
                        <sof:CodProd>' & CodProd & '</sof:CodProd>
                        <sof:nvFecCompr>' & nvFecCompr & '</sof:nvFecCompr>
                        <sof:nvCorrela>' & nvCorrela & '</sof:nvCorrela>
                        <sof:nvLinea>' & nvLinea & '</sof:nvLinea>
                        <sof:nvCantDesp>' & nvCantDesp & '</sof:nvCantDesp>
                        <sof:Pieza>' & Pieza & '</sof:Pieza>
                        <sof:nvDPorcDesc01>' & nvDPorcDesc01 & '</sof:nvDPorcDesc01>
                        <sof:nvDPorcDesc02>' & nvDPorcDesc02 & '</sof:nvDPorcDesc02>
                        <sof:nvDPorcDesc03>' & nvDPorcDesc03 & '</sof:nvDPorcDesc03>
                        <sof:nvDPorcDesc04>' & nvDPorcDesc04 & '</sof:nvDPorcDesc04>
                        <sof:nvDPorcDesc05>' & nvDPorcDesc05 & '</sof:nvDPorcDesc05>
                        <sof:nvDDescto01>' & nvDDescto01 & '</sof:nvDDescto01>
                        <sof:nvDDescto02>' & nvDDescto02 & '</sof:nvDDescto02>
                        <sof:nvDDescto03>' & nvDDescto03 & '</sof:nvDDescto03>
                        <sof:nvDDescto04>' & nvDDescto04 & '</sof:nvDDescto04>
                        <sof:nvDDescto05>' & nvDDescto05 & '</sof:nvDDescto05>
                        <sof:nvTotDesc>' & nvTotDesc & '</sof:nvTotDesc>
                    </sof:NotaVentaDetalleDTO>
                </sof:Detalles>
            </sof:notaVenta>
            <sof:nombreContactoFacturaBoleta>' & nombreContactoFacturaBoleta & '</sof:nombreContactoFacturaBoleta>
            <sof:Token>' & Token & '</sof:Token>
            <sof:enviaPdf>' & enviaPdf & '</sof:enviaPdf>
        </sof:IngresaNotadeVenta>
    </x:Body>
</x:Envelope>';
    $url = 'https://web.softlandcloud.cl/ecommerce/WSNotaVenta.asmx?WSDL';
    $response = postSoapCurlRequest($url, null, $dataRaw);
    $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
    $xml = new \SimpleXMLElement($response);
    dd($xml);
    if ($xml->soapBody && $xml->soapBody->IngresaNotadeVentaResponse && $xml->soapBody->IngresaNotadeVentaResponse->IngresaNotadeVentaResult && $xml->soapBody->IngresaNotadeVentaResponse->IngresaNotadeVentaResult) {
      $results = $xml->soapBody->IngresaNotadeVentaResponse->IngresaNotadeVentaResult;
      $products = [];
      foreach ($results->IngresaNotadeVentaResult as $key => $val) {
        $products[] = $val;
      }
      // return json_encode($products);
      return response()->json($products, 200);
    } else {
      dd($xml);
    }
  }


  private function getObtenerStockPorBodega()
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
        <ObtenerStockPorBodega xmlns="http://softland.cl/">
            <codprod>4025066125142</codprod>
            <Empresa>CORSE1</Empresa>
            <Token>string</Token>
            <CodBode>01</CodBode>
            <Talla>string</Talla>
            <Color>string</Color>
        </ObtenerStockPorBodega>
    </soap:Body>
</soap:Envelope>';
    $url = 'http://web.softlandcloud.cl/ecommerce/WSProducto.asmx?WSDL';
    $response = postSoapCurlRequest($url, null, $dataRaw);
    $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
    $xml = new \SimpleXMLElement($response);
    dd($xml);
    if ($xml->soapBody && $xml->soapBody->ObtenerStockPorBodegaResponse && $xml->soapBody->ObtenerStockPorBodegaResponse->ObtenerStockPorBodegaResult && $xml->soapBody->ObtenerStockPorBodegaResponse->ObtenerStockPorBodegaResult->stock) {
      $results = $xml->soapBody->ObtenerStockPorBodegaResponse->ObtenerStockPorBodegaResult;
      $products = [];
      foreach ($results->stock as $key => $val) {
        $products[] = $val;
      }
      // return json_encode($products);
      return response()->json($products, 200);
    } else {
      dd($xml);
    }
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
      return $products;
      // return json_encode($products);
      // return response()->json($products, 200);
    } else {
      dd($xml);
    }
  }
}

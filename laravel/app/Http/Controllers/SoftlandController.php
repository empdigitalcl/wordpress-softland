<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Woocommerce;
use App\Sync;

class SoftlandController extends Controller
{


  public function __construct()
  {
    //$this->amanoBaseUri = env('PRIMETEC_BASE_URI') != '' ? env('PRIMETEC_BASE_URI') : 'http://wspruebas.dtesoftware.cl/webservice';
    $this->user = env('SOFTLAND_USERNAME') != '' ? base64_encode(env('SOFTLAND_USERNAME')) : base64_encode('STORE');
    $this->password = env('SOFTLAND_PASSWORD') != '' ? base64_encode(env('SOFTLAND_PASSWORD')) : base64_encode('softland');
    $this->codEmpresa = env('SOFTLAND_EMPRESA') != '' ? base64_encode(env('SOFTLAND_EMPRESA')) : base64_encode('CORSE1');


    $this->wpBaseUri = env('WP_BASE_URI') != '' ? env('WP_BASE_URI') : 'https://amanodev.empchile.net/wp-json/wc/v3';
    $this->wcUser = env('WC_USERNAME') != '' ? env('WC_USERNAME') : 'ck_22f7c4a3c672fc928b85aada067841e47535216a';
    $this->wcPassword = env('WC_PASSWORD') != '' ? env('WC_PASSWORD') : 'cs_8f1947554a5822b6e09f1a24d41a2bfecd05d40d';


    $this->headers = [
      'Authorization: Basic ' . base64_encode($this->user . ':' . $this->password),
      'Content-Type: text/xml; charset=utf-8',
    ];
    $this->wcHeaders = [
      'Authorization: Basic ' . base64_encode($this->wcUser . ':' . $this->wcPassword),
      'Content-Type: text/xml; charset=utf-8',
    ];
  }




  public function index()
  {
    //return $this->getObtenerCatalogoProductosResponse();
    //return $this->getObtenerProductosPorCodigo();
    //return $this->getObtenerStockPorBodega();
    //return $this->postIngresaNotadeVenta();
    //return $this->syncProducts();
  }






  public function postIngresaNotadeVenta(Request $request)
  {
    $input = $request->all();

    $metadata = $input['order_data']['meta_data'];



    //dd($input['order_data']['shipping_total']); //para testing


    //$nvValflete = $metadata[$arr]['id'];

    /*  if (
      isset($input['order_data']['meta_data'])
      && ($metadata = $input['order_data']['meta_data'])
      && (($arr = array_search('_billing_dte_type', array_column($metadata, 'key'))) !== null)
    ) {

      $billingType = $metadata[$arr]['value'];
    } else {
      return "_billing_dte_type no encontrado";
    }*

   /* if ($billingType == 'boleta') {
      $xml = $this->makeBoletaElectronicaArray($input);

      $uriArray = [
        'location' => $this->amanoBaseUri . '/wsdlboletas/Wsboletas.php?wsdl',
        'uri' => 'urn:webservices',
      ];
    } elseif ($billingType == 'factura') {
      $xml = $this->makeFacturationArray($input);

      $uriArray = [
        'location' => $this->amanoBaseUri . '/wsdl/Wspuyehue.php?wsdl',
        'uri' => 'urn:webservices',
      ];
    } else {
      return "tipo no encontrado";
    }*/


    /*  try {
      $client = new SoapClient(null, $uriArray);
      $result = $client->__soapCall('procesardte', array($xml, $this->user, $this->password, $this->codUsuario, $this->codEmpresa, 'soap_version' => SOAP_1_2));
      $response = simplexml_load_string($result);
      $response = json_encode($response);
      $response = json_decode($response, TRUE);
    } catch (Exception $e) {

      $result = [
        'folio' => null,
        'rutadocumento' => null,
        'status' => 'error',
        'statusmsg' => $e->getMessage(),

      ];
      echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    }*/


    // Set the new timezone
    date_default_timezone_set('America/Cuiaba');
    $dateFechaHoraCreacion = date('Y-m-d H:i:s');


    $nvPorcFlete = '0';
    //dd($nvPorcFlete);
    $nvValflete = $input['order_data']['shipping_total'];
    $nvPorcEmb = '0';
    $nvValEmb = '0';
    $nvEquiv = '1';
    $nvNetoExento = '0';
    $nvNetoAfecto = $input['order_data']['total'];
    $nvTotalDesc = $input['order_data']['discount_total'];
    $ConcAuto = 'N';
    $NumGuiaRes = '0';
    $CheckeoPorAlarmaVtas = 'N';
    $FechaHoraCreacion = $dateFechaHoraCreacion;
    $ConcManual = 'N';
    $RutSolicitante = '0';
    $TotalBoleta = $input['order_data']['total'];
    $NumReq = '0';
    $EnMantencion = '0';
    $nvFeAprob = $input['order_data']['date_created']['date'];
    $total = $input['order_data']['total'];
    $tax = $input['order_data']['total_tax'];
    $shipping_total = $input['order_data']['shipping_total'];
    $discount_total = $input['order_data']['discount_total'];
    $discount_tax = $input['order_data']['discount_tax'];
    $nvMonto = $input['order_data']['total'];
    $nvSubTotal = $total + $tax + $shipping_total - $discount_total - $discount_tax;
    $metadata = $input['order_data']['meta_data'];
    $arr = array_search('_billing_rut', array_column($metadata, 'key'));
    //dd($metadata[$arr]['value']); //para testing
    $RutCliente = $metadata[$arr]['value'];
    //dd("adsasdads=" . $RutCliente);
    $nvFem = $input['order_data']['date_created']['date'];
    $nvEstado = 'A';
    $nvEstFact = '0';
    $nvEstDesp = '0';
    $nvEstRese = '0';
    $nvEstConc = '0';
    $CotNum = '0';
    $NumOC = '-1';
    $nvFeEnt = $input['order_data']['date_created']['date'];
    $CodAux = '0';
    $VenCod = '01';
    $CodMon = '0';
    $CodLista = '0';
    $nvObser = '0';
    $nvCanalNV = '0';
    $CveCod = '01';
    $NomCon = $input['order_data']['billing']['first_name'] . '  '  . $input['order_data']['billing']['last_name'];
    //dd($NomCon);
    $CodiCC = '0';
    $CodBode = '0';
    $CodLugarDesp = '0';
    $CorreoCliente = $input['order_data']['billing']['email'];
    $TipoDoctoVta = 'B';
    $ValorPorcentualImpuesto = '19';
    $AfectoAImpuesto = '100';
    $MontoImpuesto = $tax;
    $nvPorcDesc01 = '0';
    $nvPorcDesc02 = '0';
    $nvPorcDesc03 = '0';
    $nvPorcDesc04 = '0';
    $nvPorcDesc05 = '0';
    $nvDescto01 = '0';
    $nvDescto02 = '0';
    $nvDescto03 = '0';
    $nvDescto04 = '0';
    $nvDescto05 = '0';
    $CantUVta = '1';
    $CodUMed = 'C.U';
    $CodPromocion = '0';
    $CheckeoMovporAlarmaVtas = 'N';
    $DetProd = 'Producto prueba';

    $i = 0;
    $nvCantOCProdTotal = 0;
    foreach ($input['order_items'] as $orderItem) {
      $i++;
      $nvCantOCProdTotal++;
    }

    // dd("saddasasda=" . $nvCantOCProdTotal);


    $nvCantOC = $nvCantOCProdTotal;
    //dd($nvCantOC);


    $nvCantBoleta = $nvCantOCProdTotal;
    $nvCantNC = '0';
    $nvCantDevuelto = '0';
    $nvCantFact = $nvCantOCProdTotal;
    $Partida = '0';
    $nvCantProd = '0';
    $nvTotLinea = '119';
    $nvSubTotal = '119';
    $nvEquiv = '1';



    /*   este ciclo for linea 223 es relacionado a $nvPrecio  linea 64 en excel ,  = ""; order_items.item[i].subtotal + order_items.item[i].subtotal_tax*/


    $i = 0;
    $nvCantVar = 0;
    foreach ($input['order_items'] as $orderItem) {
      $i++;

      $subtotal = $orderItem['subtotal'];
      $subtotal_tax = $orderItem['subtotal_tax'];
      $nvCantVar =  $subtotal + $subtotal_tax;

      //dd("sadasd" . $nvCantVar);
    }
    $nvCantVar = $nvCantVar + $nvCantVar;

    print_r($nvCantVar);

    /*  $nvPrecio = ""; order_items.item[i].subtotal + order_items.item[i].subtotal_tax*/
    $nvPrecio = $nvCantVar;
    $nvCant = $nvCantOCProdTotal;
    $CodProd = '0';
    $nvFecCompr =
      $input['order_data']['date_created']['date'];
    $nvCorrela = '0';
    $nvLinea = '0';
    $nvCantDesp = '0';
    $Pieza = '0';
    $nvDPorcDesc01 = '0';
    $nvDPorcDesc02 = '0';
    $nvDPorcDesc03 = '0';
    $nvDPorcDesc04 = '0';
    $nvDPorcDesc05 = '0';
    $nvDDescto01 = '0';
    $nvDDescto02 = '0';
    $nvDDescto03 = '0';
    $nvDDescto04 = '0';
    $nvDDescto05 = '0';
    $nvTotDesc = '0';
    $nombreContactoFacturaBoleta =
      $input['order_data']['billing']['first_name'] . '  '  . $input['order_data']['billing']['last_name'];
    $Token = '';
    $enviaPdf = '0';



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
                    <sof:nvPorcFlete>' . $nvPorcFlete . '</sof:nvPorcFlete>
                    <sof:nvValflete>' . $nvValflete . '</sof:nvValflete>
                    <sof:nvPorcEmb>' . $nvPorcEmb . '</sof:nvPorcEmb>
                    <sof:nvValEmb>' . $nvValEmb . '</sof:nvValEmb>
                    <sof:nvEquiv>' . $nvEquiv . '</sof:nvEquiv>
                    <sof:nvNetoExento>' . $nvNetoExento . '</sof:nvNetoExento>
                    <sof:nvNetoAfecto>' . $nvNetoAfecto . '</sof:nvNetoAfecto>
                    <sof:nvTotalDesc>' . $nvTotalDesc . '</sof:nvTotalDesc>
                    <sof:ConcAuto>' . $ConcAuto . '</sof:ConcAuto>
                    <sof:NumGuiaRes>' . $NumGuiaRes . '</sof:NumGuiaRes>
                    <sof:CheckeoPorAlarmaVtas>' . $CheckeoPorAlarmaVtas . '</sof:CheckeoPorAlarmaVtas>
                    <sof:FechaHoraCreacion>' . $FechaHoraCreacion . '</sof:FechaHoraCreacion>
                    <sof:ConcManual>' . $ConcManual . '</sof:ConcManual>
                    <sof:RutSolicitante>' . $RutSolicitante . '</sof:RutSolicitante>
                    <sof:TotalBoleta>' . $TotalBoleta . '</sof:TotalBoleta>
                    <sof:NumReq>' . $NumReq . '</sof:NumReq>
                    <sof:EnMantencion>' . $EnMantencion . '</sof:EnMantencion>
                    <sof:nvFeAprob>' . $nvFeAprob . '</sof:nvFeAprob>
                    <sof:nvMonto>' . $nvMonto . '</sof:nvMonto>
                    <sof:nvSubTotal>' . $nvSubTotal . '</sof:nvSubTotal>
                    <sof:RutCliente>' . $RutCliente . '</sof:RutCliente>
                    <sof:nvFem>' . $nvFem . '</sof:nvFem>
                    <sof:nvEstado>' . $nvEstado . '</sof:nvEstado>
                    <sof:nvEstFact>' . $nvEstFact . '</sof:nvEstFact>
                    <sof:nvEstDesp>' . $nvEstDesp . '</sof:nvEstDesp>
                    <sof:nvEstRese>' . $nvEstRese . '</sof:nvEstRese>
                    <sof:nvEstConc>' . $nvEstConc . '</sof:nvEstConc>
                    <sof:CotNum>' . $CotNum . '</sof:CotNum>
                    <sof:NumOC>' . $NumOC . '</sof:NumOC>
                    <sof:nvFeEnt>' . $nvFeEnt . '</sof:nvFeEnt>
                    <sof:CodAux>' . $CodAux . '</sof:CodAux>
                    <sof:VenCod>' . $VenCod . '</sof:VenCod>
                    <sof:CodMon>' . $CodMon . '</sof:CodMon>
                    <sof:CodLista>' . $CodLista . '</sof:CodLista>
                    <sof:nvObser>' . $nvObser . '</sof:nvObser>
                    <sof:nvCanalNV>' . $nvCanalNV . '</sof:nvCanalNV>
                    <sof:CveCod>' . $CveCod . '</sof:CveCod>
                    <sof:NomCon>' . $NomCon . '</sof:NomCon>
                    <sof:CodiCC>' . $CodiCC . '</sof:CodiCC>
                    <sof:CodBode>' . $CodBode . '</sof:CodBode>
                    <sof:CodLugarDesp>' . $CodLugarDesp . '</sof:CodLugarDesp>
                    <sof:CorreoCliente>' . $CorreoCliente . '</sof:CorreoCliente>
                    <sof:TipoDoctoVta>' . $TipoDoctoVta . '</sof:TipoDoctoVta>
                    <sof:impuestos>
                        <sof:ImpuestoNV>
                            <sof:CodImpto>IVA</sof:CodImpto>
                            <sof:ValorPorcentualImpuesto>' . $ValorPorcentualImpuesto . '</sof:ValorPorcentualImpuesto>
                            <sof:AfectoAImpuesto>' . $AfectoAImpuesto . '</sof:AfectoAImpuesto>
                            <sof:MontoImpuesto>' . $MontoImpuesto . '</sof:MontoImpuesto>
                        </sof:ImpuestoNV>
                    </sof:impuestos>
                    <sof:nvPorcDesc01>' . $nvPorcDesc01 . '</sof:nvPorcDesc01>
                    <sof:nvPorcDesc02>' . $nvPorcDesc02 . '</sof:nvPorcDesc02>
                    <sof:nvPorcDesc03>' . $nvPorcDesc03 . '</sof:nvPorcDesc03>
                    <sof:nvPorcDesc04>' . $nvPorcDesc04 . '</sof:nvPorcDesc04>
                    <sof:nvPorcDesc05>' . $nvPorcDesc05 . '</sof:nvPorcDesc05>
                    <sof:nvDescto01>' . $nvDescto01 . '</sof:nvDescto01>
                    <sof:nvDescto02>' . $nvDescto02 . '</sof:nvDescto02>
                    <sof:nvDescto03>' . $nvDescto03 . '</sof:nvDescto03>
                    <sof:nvDescto04>' . $nvDescto04 . '</sof:nvDescto04>
                    <sof:nvDescto05>' . $nvDescto05 . '</sof:nvDescto05>
                </sof:Cabecera>
                <sof:Detalles>
                    <sof:NotaVentaDetalleDTO>
                        <sof:CantUVta>' . $CantUVta . '</sof:CantUVta>
                        <sof:CodUMed>' . $CodUMed . '</sof:CodUMed>
                        <sof:CodPromocion>' . $CodPromocion . '</sof:CodPromocion>
                        <sof:CheckeoMovporAlarmaVtas>' . $CheckeoMovporAlarmaVtas . '</sof:CheckeoMovporAlarmaVtas>
                        <sof:DetProd>' . $DetProd . '</sof:DetProd>
                        <sof:nvCantOC>' . $nvCantOC . '</sof:nvCantOC>
                        <sof:nvCantBoleta>' . $nvCantBoleta . '</sof:nvCantBoleta>
                        <sof:nvCantNC>' . $nvCantNC . '</sof:nvCantNC>
                        <sof:nvCantDevuelto>' . $nvCantDevuelto . '</sof:nvCantDevuelto>
                        <sof:nvCantFact>' . $nvCantFact . '</sof:nvCantFact>
                        <sof:Partida>' . $Partida . '</sof:Partida>
                        <sof:nvCantProd>' . $nvCantProd . '</sof:nvCantProd>
                        <sof:nvTotLinea>' . $nvTotLinea . '</sof:nvTotLinea>
                        <sof:nvSubTotal>' . $nvSubTotal . '</sof:nvSubTotal>
                        <sof:nvEquiv>' . $nvEquiv . '</sof:nvEquiv>
                        <sof:nvPrecio>' . $nvPrecio . '</sof:nvPrecio>
                        <sof:nvCant>' . $nvCant . '</sof:nvCant>
                        <sof:CodProd>' . $CodProd . '</sof:CodProd>
                        <sof:nvFecCompr>' . $nvFecCompr . '</sof:nvFecCompr>
                        <sof:nvCorrela>' . $nvCorrela . '</sof:nvCorrela>
                        <sof:nvLinea>' . $nvLinea . '</sof:nvLinea>
                        <sof:nvCantDesp>' . $nvCantDesp . '</sof:nvCantDesp>
                        <sof:Pieza>' . $Pieza . '</sof:Pieza>
                        <sof:nvDPorcDesc01>' . $nvDPorcDesc01 . '</sof:nvDPorcDesc01>
                        <sof:nvDPorcDesc02>' . $nvDPorcDesc02 . '</sof:nvDPorcDesc02>
                        <sof:nvDPorcDesc03>' . $nvDPorcDesc03 . '</sof:nvDPorcDesc03>
                        <sof:nvDPorcDesc04>' . $nvDPorcDesc04 . '</sof:nvDPorcDesc04>
                        <sof:nvDPorcDesc05>' . $nvDPorcDesc05 . '</sof:nvDPorcDesc05>
                        <sof:nvDDescto01>' . $nvDDescto01 . '</sof:nvDDescto01>
                        <sof:nvDDescto02>' . $nvDDescto02 . '</sof:nvDDescto02>
                        <sof:nvDDescto03>' . $nvDDescto03 . '</sof:nvDDescto03>
                        <sof:nvDDescto04>' . $nvDDescto04 . '</sof:nvDDescto04>
                        <sof:nvDDescto05>' . $nvDDescto05 . '</sof:nvDDescto05>
                        <sof:nvTotDesc>' . $nvTotDesc . '</sof:nvTotDesc>
                    </sof:NotaVentaDetalleDTO>
                </sof:Detalles>
            </sof:notaVenta>
            <sof:nombreContactoFacturaBoleta>' . $nombreContactoFacturaBoleta . '</sof:nombreContactoFacturaBoleta>
            <sof:Token>' . $Token . '</sof:Token>
            <sof:enviaPdf>' . $enviaPdf . '</sof:enviaPdf>
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


  public function order(Request $request)
  {
    $input = $request->all();
    // dd($input); //para testing

    if (
      isset($input['order_data']['meta_data'])
      && ($metadata = $input['order_data']['meta_data'])
      && (($arr = array_search('_billing_dte_type', array_column($metadata, 'key'))) !== null)
    ) {

      $billingType = $metadata[$arr]['value'];
    } else {
      return "_billing_dte_type no encunetrado";
    }

    if ($billingType == 'boleta') {
      $xml = $this->makeBoletaElectronicaArray($input);

      $uriArray = [
        'location' => $this->amanoBaseUri . '/wsdlboletas/Wsboletas.php?wsdl',
        'uri' => 'urn:webservices',
      ];
    } elseif ($billingType == 'factura') {
      $xml = $this->makeFacturationArray($input);

      $uriArray = [
        'location' => $this->amanoBaseUri . '/wsdl/Wspuyehue.php?wsdl',
        'uri' => 'urn:webservices',
      ];
    } else {
      return "tipo no encontrado";
    }


    try {
      $client = new SoapClient(null, $uriArray);
      $result = $client->__soapCall('procesardte', array($xml, $this->user, $this->password, $this->codUsuario, $this->codEmpresa, 'soap_version' => SOAP_1_2));
      $response = simplexml_load_string($result);
      $response = json_encode($response);
      $response = json_decode($response, TRUE);
    } catch (Exception $e) {

      $result = [
        'folio' => null,
        'rutadocumento' => null,
        'status' => 'error',
        'statusmsg' => $e->getMessage(),

      ];
      echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    }


    if (isset($response['respuesta'])) {
      $response['status'] = 'ok';
      $response['statusmsg'] = null;
    }
    $formatUpdateWcOrder = $this->formatUpdateWcOrder($response);
    // dd($formatUpdateWcOrder); //para testing

    $update = $this->updateWcOrder($input['order_data']['id'], $formatUpdateWcOrder);
    return $update;
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

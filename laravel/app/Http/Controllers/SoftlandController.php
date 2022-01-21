<?php

namespace App\Http\Controllers;

use App\Sync;
use Illuminate\Http\Request;
use Woocommerce;
date_default_timezone_set('America/Santiago');

class SoftlandController extends Controller
{
    //

    public function __construct()
    {
        //$this->amanoBaseUri = env('PRIMETEC_BASE_URI') != '' ? env('PRIMETEC_BASE_URI') : 'http://wspruebas.dtesoftware.cl/webservice';
        $this->user = env('SOFTLAND_USERNAME') != '' ? base64_encode(env('SOFTLAND_USERNAME')) : base64_encode('STORE');
        $this->password = env('SOFTLAND_PASSWORD') != '' ? base64_encode(env('SOFTLAND_PASSWORD')) : base64_encode('softland');
        $this->codEmpresa = env('SOFTLAND_EMPRESA') != '' ? base64_encode(env('SOFTLAND_EMPRESA')) : base64_encode('CORSE1');

        $this->wpBaseUri = env('WP_API_BASE_URL') != '' ? env('WP_API_BASE_URL') : 'https://tienda.ducatichile.cl/wp-json/wc/v3';
        $this->wcUser = env('WP_API_CLIENT_ID') != '' ? env('WP_API_CLIENT_ID') : 'ck_4325b3ee0019eccaae8bc23254e3be7b929c0c91';
        $this->wcPassword = env('WP_API_CLIENT_SECRET') != '' ? env('WP_API_CLIENT_SECRET') : 'cs_9433abea6cde2c0474918a33666a724e1150b2e1';

        $this->headers = [
            'Authorization: Basic ' . base64_encode($this->user . ':' . $this->password),
            'Content-Type: text/xml; charset=utf-8',
        ];
        $this->wcHeaders = [
            'Authorization: Basic ' . base64_encode($this->wcUser . ':' . $this->wcPassword),
            'Content-Type: text/xml; charset=utf-8',
        ];
    }

    private function wpConnection($function = null, $method = 'GET', $data = array())
    {
        $response = null;
        if ($function != null) {
            $url = $this->wpBaseUri . $function . '?consumer_key=' . env('WP_API_CLIENT_ID') . '&consumer_secret=' . env('WP_API_CLIENT_SECRET');
            $session = curl_init($url);
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/json',
            );
            if ($method == 'GET' && count($data) > 0) {
                $url .= '?' . http_build_query($data);
            }
            echo $url.'<br>';
            $config = array(
                CURLOPT_URL => $url,
                CURLOPT_USERPWD => env('WP_API_CLIENT_ID') . ":" . env('WP_API_CLIENT_SECRET'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => $headers,
            );
            if (($method == 'POST' || $method == 'PUT') && count($data) > 0) {
                $config[CURLOPT_POSTFIELDS] = json_encode($data);
            }
            curl_setopt_array($session, $config);
            $response = curl_exec($session);
            $err = curl_error($session);
            $code = curl_getinfo($session, CURLINFO_HTTP_CODE);
            curl_close($session);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $response = json_decode($response);
            }
        }
        // dd($response);
        return $response;
    }
    public function syncWCProducts($take = 10)
    {
        $syncs = Sync::Pending()->orderBy('session', 'ASC')->paginate($take);
        if ($syncs->count() > 0) {
            foreach ($syncs as $sync) {
                echo $sync->sku . '<br>';
                $WCProduct = $this->getWooCProductBySKU($sync->sku);
                if (count($WCProduct) > 0) {
                    foreach ($WCProduct as $item) {
                        echo $sync->sku . ': ' . $item['id'] . '<br>';
                        $fields = [
                            'catalog_visibility' => $sync->netPrice > 1000 ? 'visible' : 'hidden',
                            'status' => $sync->netPrice > 1000 ? 'publish' : 'pending',
                            'regular_price' => (string) (round($sync->netPrice*1.19)),
                            'stock_quantity' => $sync->stockAvailable > 0 ? (string) ($sync->stockAvailable) : '0',
                        ];
                        if ($item['type'] != 'variation') {
                            $fields['regular_price'] = (string) (round($sync->netPrice*1.19));
                        }
                        try {
                            if ($item['type'] != 'variation') {
                                $this->updateWooCProduct($item['id'], $fields);
                                echo 'OK<br>';
                            } else {
                                $this->updateWooCProductVariation($item['parent_id'], $item['id'], $fields);
                                echo 'OK<br>';
                            }
                        } catch (\Exception $exc) {
                            echo $exc->getMessage();
                            echo '<pre>';
                            print_r($item);
                            echo '</pre>';
                        }
                        print_r($fields);
                        $sync->woocType = $item['type'];
                        $sync->woocParentId = $item['parent_id'];
                        $sync->woocProductId = $item['id'];
                        $sync->status = 2;
                        $sync->save();
                    }
                } else {
                    $sync->status = 3;
                    $sync->session = date('YmdHis');
                    $sync->save();
                    echo 'No encontrado<br>';
                }
            }
        }
    }
    public function syncWCProductsBySku($sku = null)
    {
        $take = 10;
        $syncs = Sync::BySku($sku)->paginate($take);
        if ($syncs->count() > 0) {
            foreach ($syncs as $sync) {
                if ($sync->woocProductId != null) {

                }
                $WCProduct = $this->getWooCProductBySKU($sync->sku);
                if (count($WCProduct) > 0) {
                    foreach ($WCProduct as $item) {
                        $fields = [
                            'catalog_visibility' => $sync->netPrice > 1000 ? 'visible' : 'hidden',
                            'status' => $sync->netPrice > 1000 ? 'publish' : 'pending',
                            'regular_price' => (string) (round($sync->netPrice*1.19)),
                            'stock_quantity' => $sync->stockAvailable > 0 ? (string) ($sync->stockAvailable) : '0',
                        ];
                        $this->getWoocProduct($item['id'], $item['type'], $item['parent_id'], $fields);
                        $sync->woocType = $item['type'];
                        $sync->woocParentId = $item['parent_id'];
                        $sync->woocProductId = $item['id'];
                        $sync->status = 2;
                        $sync->save();
                    }
                }
            }
        }
    }
    private function getWoocProduct($id, $type, $parentId, $fields)
    {
        if ($type != 'variation') {
            $fields['price'] = (string) ($fields['regular_price']);
        }
        try {
            // var_dump($fields);
            if ($type != 'variation') {
                echo ' normal ' . ': ' . $id . '<br>';
                $this->updateWooCProduct($id, $fields);
            } else {
                echo ' variante ' . ': ' . $id . ' > ' . $id . '<br>';
                $this->updateWooCProductVariation($parentId, $id, $fields);
            }
        } catch (\Exception $exc) {
            echo $exc->getMessage();
            echo '<pre>';
            echo 'id= ' . $id . ' > type=' . $type . ' parentId=' . $parentId;
            echo '</pre>';
        }
    }
    private function updateWooCProduct($productId, $fields)
    {
        $response = Woocommerce::put('products/' . $productId, $fields);
        echo '<br>==';
        print_r($response);
        echo '==<br>';
    }
    private function updateWooCProductVariation($productId, $variationId, $fields)
    {
        $method = 'products/' . $productId . '/';
        $method .= 'variations/' . $variationId;
        $response = $this->wpConnection($method, 'PUT', $fields);
    }
    private function getWooCProductBySKU($sku){
        $params = [
            'sku' => $sku
        ];
        // return $this->wpConnection('products', 'GET', $params);
        return Woocommerce::get('products', $params);
    }
    public function test(Request $request)
    {
        echo 'fff';
        return 'x';
    }
    private function insertarCliente($cliente)
    {
        
        $url = 'https://web.softlandcloud.cl/ecommerce/WSCWTauxi.asmx?WSDL';
        $dataRaw = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xmlns:xsd="http://www.w3.org/2001/XMLSchema"
        xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Header>
        <AuthHeader xmlns="http://softland.cl/">
        <Username>STORE</Username>
        <Password>softland</Password>
        </AuthHeader>
        </soap:Header>
        <soap:Body>
        <AgregaAuxiliares xmlns="http://softland.cl/">
        <Cliente>
        <strConn></strConn>
        <CodAux>'.str_replace(['.', '-'], ['', ''], $cliente['rut']).'</CodAux>
        <NomAux>'.$cliente['nombre'].'</NomAux>
        <NoFAux></NoFAux>
        <RutAux>'.$cliente['rut'].'</RutAux>
        <ActAux>S</ActAux>
        <GirAux></GirAux>
        <ComAux></ComAux>
        <CiuAux></CiuAux>
        <PaiAux>CL</PaiAux>
        <DirAux>'.$cliente['direccion'].'</DirAux>
        <DirNum>'.$cliente['direccion2'].'</DirNum>
        <FonAux1>'.$cliente['telefono'].'</FonAux1>
        <FonAux2></FonAux2>
        <FonAux3></FonAux3>
        <FaxAux1></FaxAux1>
        <FaxAux2></FaxAux2>
        <ClaCli>S</ClaCli>
        <ClaPro>N</ClaPro>
        <ClaEmp>N</ClaEmp>
        <ClaSoc>N</ClaSoc>
        <ClaDis>N</ClaDis>
        <ClaOtr>N</ClaOtr>
        <DiaPlazo></DiaPlazo>
        <Bloqueado></Bloqueado>
        <EMail>'.$cliente['email'].'</EMail>
        <Casilla></Casilla>
        <WebSite></WebSite>
        <Notas></Notas>
        <Region>-1</Region>
        <ClaPros>N</ClaPros>
        <esReceptorDTE></esReceptorDTE>
        </Cliente>
        <Empresa>CORSE1</Empresa>
        </AgregaAuxiliares>
        </soap:Body>
        </soap:Envelope>';
        $response = postSoapCurlRequest($url, null, $dataRaw);
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
        $response = new \SimpleXMLElement($response);

        $response = json_encode($response);
        $response = json_decode($response, true);

        //dd($response);
        
    }
    private function postConsultarCliente($cliente)
    {
        $url = 'https://web.softlandcloud.cl/ecommerce/WSCWTauxi.asmx?WSDL';
        $dataRaw = '<?xml version="1.0" encoding="utf-8"?> 
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"  xmlns:xsd="http://www.w3.org/2001/XMLSchema"  
        xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"> 
         <soap:Header> 
         <AuthHeader xmlns="http://softland.cl/"> 
         <Username>STORE</Username>
        <Password>softland</Password> 
         </AuthHeader> 
         </soap:Header> 
         <soap:Body>
         <ObtieneAuxiliaresxCodigo xmlns="http://softland.cl/"> 
         <Codaux>'.str_replace(['.', '-'], ['', ''], $cliente['rut']).'</Codaux> 
         <Empresa>CORSE1</Empresa> 
         </ObtieneAuxiliaresxCodigo>
         </soap:Body> 
        </soap:Envelope>';
        $response = postSoapCurlRequest($url, null, $dataRaw);
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
        $response = new \SimpleXMLElement($response);

        $response = json_encode($response);
        $response = json_decode($response, true);

        // dd($cliente, $response);
        if ($c = $response['soapBody']['ObtieneAuxiliaresxCodigoResponse']['ObtieneAuxiliaresxCodigoResult']) {

        } else {
            $this->insertarCliente($cliente);
        }
        
    }
    public function postIngresaNotadeVenta(Request $request)
    {
        
        $input = $request->all();

        $url = 'https://web.softlandcloud.cl/ecommerce/WSNotaVenta.asmx?WSDL';
        $dataRaw = $this->generateNVXml($input);
        $response = postSoapCurlRequest($url, null, $dataRaw);
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
        $response = new \SimpleXMLElement($response);

        $response = json_encode($response);
        $response = json_decode($response, true);
        // dd($dataRaw, $response);
        $ventaResult = $response['soapBody']['IngresaNotadeVentaResponse']['IngresaNotadeVentaResult'];
        $formatUpdateWcOrder = $this->formatUpdateWcOrder($ventaResult);
        $update = $this->updateWcOrder($input['order_data']['id'], $formatUpdateWcOrder);
        return $update;
        
    }
    private function generateNVXml($data) {
        $date = date('Y-m-d');
        $orderData = $data['order_data'];
        $NomCon = $orderData['billing']['first_name'].' '.$orderData['billing']['last_name'];
        $CorreoCliente = $orderData['billing']['email'];
        $RutCliente = '';
        $TipoDoctoVta = '';
        $comprobante = '';
        $phone = $orderData['billing']['phone'];
        if (count($orderData['meta_data']) > 0) {
            foreach ($orderData['meta_data'] as $metaData) {
                if ($metaData['key'] == '_billing_rut') {
                    $RutCliente = $metaData['value'];
                }
                if ($metaData['key'] == '_billing_dte_type') {
                    $comprobante = $metaData['value'];
                }
                if ($metaData['key'] == '_billing_dte_type') {
                    if ($metaData['value'] == 'boleta') {
                        $TipoDoctoVta = 'B';
                    }
                    if ($metaData['value'] == 'factura') {
                        $TipoDoctoVta = 'F';
                    }
                    $RutCliente = $metaData['value'];
                }
            }
        }

        if ($comprobante == 'boleta') {
            $TipoDoctoVta = 'B';
        }
        if ($comprobante == 'factura') {
            $TipoDoctoVta = 'F';
        }

        $address_1 = '';
        $address_2 = '';
        if ($orderData['shipping'] && $orderData['shipping']['address_1']) {
            $address_1 = $orderData['shipping']['address_1'];
            if ($orderData['shipping']['address_2']) {
                $address_2 = $orderData['shipping']['address_2'];
            }
        }        
        $cliente = [
            'rut' => $RutCliente,
            'nombre' => $NomCon,
            'email' => $CorreoCliente,
            'direccion' => $address_1,
            'direccion2' => $address_2,
            'telefono' => $phone
        ];
        $this->postConsultarCliente($cliente);

        $nvNetoExento = 0;
        $totalNet = $orderData['total'];
        $totalWTax = round($totalNet*1.19);
        $totalTax = round($totalNet*0.19);
        $nvTotalDesc = 0;

        $NotaVentaDetalleDTO = '';
        $k = 1;
        if (count($data['order_items']) > 0) {
            foreach ($data['order_items'] as $orderItems) {
                $itemTotalNet = $orderItems['total'];
                $itemTotalWTax = round($orderItems['total']*1.19);
                $itemTotalTax = round($orderItems['total']*0.19);
                $NotaVentaDetalleDTO.='<sof:NotaVentaDetalleDTO>
                <sof:CantUVta>'.$orderItems['quantity'].'</sof:CantUVta>
                <!--Optional:-->
                <sof:CodUMed>C.U.</sof:CodUMed>
                <!--Optional:-->
                <sof:CheckeoMovporAlarmaVtas>N</sof:CheckeoMovporAlarmaVtas>
                <!--Optional:-->
                <sof:DetProd>'.$orderItems['name'].'</sof:DetProd>
                <sof:nvCantOC>0</sof:nvCantOC>
                <sof:nvCantBoleta>'.$itemTotalTax.'</sof:nvCantBoleta>
                <sof:nvCantNC>0</sof:nvCantNC>
                <sof:nvCantDevuelto>0</sof:nvCantDevuelto>
                <sof:nvCantFact>0</sof:nvCantFact>
                <!--Optional:-->
                <sof:Partida>0</sof:Partida>
                <sof:nvCantProd>1</sof:nvCantProd>
                <sof:nvTotLinea>'.$itemTotalTax.'</sof:nvTotLinea>
                <sof:nvSubTotal>'.$itemTotalTax.'</sof:nvSubTotal>
                <sof:nvEquiv>1</sof:nvEquiv>
                <sof:nvPrecio>'.$itemTotalTax.'</sof:nvPrecio>
                <sof:nvCant>1</sof:nvCant>
                <!--Optional:-->
                <sof:CodProd>'.$orderItems['sku'].'</sof:CodProd>
                <sof:nvFecCompr>'.$date.'</sof:nvFecCompr>
                <sof:nvCorrela>'.$k.'</sof:nvCorrela>
                <sof:nvLinea>'.$k.'</sof:nvLinea>
                <sof:nvCantDesp>0</sof:nvCantDesp>
                <!--Optional:-->
                <sof:Pieza>0</sof:Pieza>
                <sof:nvDPorcDesc01>0</sof:nvDPorcDesc01>
                <sof:nvDPorcDesc02>0</sof:nvDPorcDesc02>
                <sof:nvDPorcDesc03>0</sof:nvDPorcDesc03>
                <sof:nvDPorcDesc04>0</sof:nvDPorcDesc04>
                <sof:nvDPorcDesc05>0</sof:nvDPorcDesc05>
                <sof:nvDDescto01>0</sof:nvDDescto01>
                <sof:nvDDescto02>0</sof:nvDDescto02>
                <sof:nvDDescto03>0</sof:nvDDescto03>
                <sof:nvDDescto04>0</sof:nvDDescto04>
                <sof:nvDDescto05>0</sof:nvDDescto05>
                <sof:nvTotDesc>0</sof:nvTotDesc>
             </sof:NotaVentaDetalleDTO>';
             $k++;
            }
        }
        return '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:sof="http://softland.cl/">
        <soap:Header>
           <sof:AuthHeader>
              <!--Optional:-->
              <sof:Username>STORE</sof:Username>
              <!--Optional:-->
              <sof:Password>softland</sof:Password>
           </sof:AuthHeader>
        </soap:Header>
        <soap:Body>
           <sof:IngresaNotadeVenta>
              <!--Optional:-->
              <sof:Empresa>CORSE1</sof:Empresa>
              <!--Optional:-->
              <sof:notaVenta>
                 <!--Optional:-->
                 <sof:Cabecera>
                    <sof:nvPorcFlete>0</sof:nvPorcFlete>
                    <sof:nvValflete>0</sof:nvValflete>
                    <sof:nvPorcEmb>0</sof:nvPorcEmb>
                    <sof:nvValEmb>0</sof:nvValEmb>
                    <sof:nvEquiv>1</sof:nvEquiv>
                    <sof:nvNetoExento>'.$nvNetoExento.'</sof:nvNetoExento>
                    <sof:nvNetoAfecto>'.$totalNet.'</sof:nvNetoAfecto>
                    <sof:nvTotalDesc>'.$nvTotalDesc.'</sof:nvTotalDesc>
                    <!--Optional:-->
                    <sof:ConcAuto>N</sof:ConcAuto>
                    <sof:NumGuiaRes>0</sof:NumGuiaRes>
                    <!--Optional:-->
                    <sof:CheckeoPorAlarmaVtas>N</sof:CheckeoPorAlarmaVtas>
                    <sof:FechaHoraCreacion>'.$date.'</sof:FechaHoraCreacion>
                    <!--Optional:-->
                    <sof:ConcManual>N</sof:ConcManual>
                    <!--Optional:-->
                    <sof:RutSolicitante>0</sof:RutSolicitante>
                    <sof:TotalBoleta>'.$totalWTax.'</sof:TotalBoleta>
                    <sof:NumReq>0</sof:NumReq>
                    <sof:EnMantencion>0</sof:EnMantencion>
                    <sof:nvFeAprob>'.$date.'</sof:nvFeAprob>
                    <sof:nvMonto>'.$totalWTax.'</sof:nvMonto>
                    <sof:nvSubTotal>'.$totalWTax.'</sof:nvSubTotal>
                    <!--Optional:-->
                    <sof:RutCliente>'.$RutCliente.'</sof:RutCliente>
                    <sof:nvFem>'.$date.'</sof:nvFem>
                    <!--Optional:-->
                    <sof:nvEstado>A</sof:nvEstado>
                    <sof:nvEstFact>0</sof:nvEstFact>
                    <sof:nvEstDesp>0</sof:nvEstDesp>
                    <sof:nvEstRese>0</sof:nvEstRese>
                    <sof:nvEstConc>0</sof:nvEstConc>
                    <sof:CotNum>0</sof:CotNum>
                    <sof:NumOC>-1</sof:NumOC>
                    <sof:nvFeEnt>'.$date.'</sof:nvFeEnt>
                    <!--Optional:-->
                    <sof:CodAux>'.str_replace(['.', '-'], ['', ''], $RutCliente).'</sof:CodAux>
                    <!--Optional:-->
                    <sof:VenCod>01</sof:VenCod>
                    <!--Optional:-->
                    <sof:CveCod>01</sof:CveCod>
                    <!--Optional:-->
                    <sof:NomCon>'.$NomCon.'</sof:NomCon>
                    <!--Optional:-->
                    <sof:CorreoCliente>'.$CorreoCliente.'</sof:CorreoCliente>
                    <!--Optional:-->
                    <sof:TipoDoctoVta>'.$TipoDoctoVta.'</sof:TipoDoctoVta>
                    <!--Optional:-->
                    <sof:impuestos>
                       <!--Zero or more repetitions:-->
                       <sof:ImpuestoNV>
                          <!--Optional:-->
                          <sof:CodImpto>IVA</sof:CodImpto>
                          <sof:ValorPorcentualImpuesto>19</sof:ValorPorcentualImpuesto>
                          <sof:AfectoAImpuesto>'.$totalNet.'</sof:AfectoAImpuesto>
                          <sof:MontoImpuesto>'.$totalTax.'</sof:MontoImpuesto>
                       </sof:ImpuestoNV>
                    </sof:impuestos>
                    <sof:nvPorcDesc01>0</sof:nvPorcDesc01>
                    <sof:nvPorcDesc02>0</sof:nvPorcDesc02>
                    <sof:nvPorcDesc03>0</sof:nvPorcDesc03>
                    <sof:nvPorcDesc04>0</sof:nvPorcDesc04>
                    <sof:nvPorcDesc05>0</sof:nvPorcDesc05>
                    <sof:nvDescto01>0</sof:nvDescto01>
                    <sof:nvDescto02>0</sof:nvDescto02>
                    <sof:nvDescto03>0</sof:nvDescto03>
                    <sof:nvDescto04>0</sof:nvDescto04>
                    <sof:nvDescto05>0</sof:nvDescto05>
                    <sof:nvTotDesc>0</sof:nvTotDesc>
                 </sof:Cabecera>
                 <!--Optional:-->
                 <sof:Detalles>
                    <!--Zero or more repetitions:-->
                    '.$NotaVentaDetalleDTO.'
                 </sof:Detalles>
              </sof:notaVenta>
              <!--Optional:-->
              <sof:nombreContactoFacturaBoleta>'.$NomCon.'</sof:nombreContactoFacturaBoleta>
              <!--Optional:-->
              <sof:Token>0</sof:Token>
              <sof:enviaPdf>0</sof:enviaPdf>
           </sof:IngresaNotadeVenta>
        </soap:Body>
     </soap:Envelope>';
    }

    public function formatUpdateWcOrder($ventaResult)
    {
        $data = [
            'meta_data' => [
                [
                    'key' => 'NotaVentaId',
                    'value' => $ventaResult,
                ],
            ],
        ];
        return $data;
    }

    public function updateWcOrder($orderId, $data)
    {
        $uri = $this->wpBaseUri . 'orders/' . $orderId;
        $wcGet = putCurlRequest($uri, $this->wcHeaders, $data);
        $wcGet = json_decode($wcGet, true);
        return $wcGet;
    }

    public function syncProducts()
    {
        $products = $this->getObtenerCatalogoProductosResponse();
        // dd($products);
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
                if ($product->precvta != $sync->netPrice || $product->stockDisponible != $sync->stockAvailable) {
                    $isUpdate = true;
                    $sync->netPrice = $product->precvta;
                    $sync->stockAvailable = $product->stockDisponible;
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
        dd($products);
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
            $response = json_decode($response, true);
        } catch (Exception $e) {

            $result = [
                'folio' => null,
                'rutadocumento' => null,
                'status' => 'error',
                'statusmsg' => $e->getMessage(),

            ];
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
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
        $xml = new \SimpleXMLElement($response);
        if ($xml->soapBody && $xml->soapBody->ObtenerCatalogoProductosResponse && $xml->soapBody->ObtenerCatalogoProductosResponse->ObtenerCatalogoProductosResult && $xml->soapBody->ObtenerCatalogoProductosResponse->ObtenerCatalogoProductosResult->Catalogo) {
            $results = $xml->soapBody->ObtenerCatalogoProductosResponse->ObtenerCatalogoProductosResult->Catalogo;

            $products = [];
            foreach ($results->productoUni as $key => $val) {
                $products[] = $val;
            }
            return $products;
            // return response()->json($products, 200);
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

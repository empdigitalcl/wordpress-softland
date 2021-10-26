<?php

namespace App\Http\Controllers;

class SoapController extends BaseSoapController
{
    private $service;

    public function BienesServicios()
    {
        try {
            self::setWsdl('http://web.softlandcloud.cl/ecommerce/WSProducto.asmx?WSDL');
            $this->service = InstanceSoapClient::init();

            $Username = 'STORE';
            $Password = 'softland';
            $codigo = 'para';
            $Empresa = 'CORSE1';
            /* $CodBode = '47458714';
            $listaPrecio = '47458714';
            $soloActivos = '47458714';*/

            $params = [
                'Username' => $Username,
                'Password'   =>  $Password,
                'codigo'   =>  $codigo,
                'Empresa'   => $Empresa
            ];
            $response = $this->service->ObtenerProductosPorDescripcion($params);
            return view('bienes-servicios-soap', compact('response'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function clima()
    {
        try {
            self::setWsdl('http://www.webservicex.net/globalweather.asmx?WSDL');
            $this->service = InstanceSoapClient::init();

            $cities = $this->service->GetCitiesByCountry(['CountryName' => 'Peru']);
            $ciudades = $this->loadXmlStringAsArray($cities->GetCitiesByCountryResult);
            dd($ciudades['Table'][1]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}

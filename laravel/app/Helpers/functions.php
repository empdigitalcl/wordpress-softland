<?php



    if (!function_exists('getCurlRequest')) {
        function getCurlRequest($uri = null, $headers = [], $token = null, $basicAuth = null)
        {

            // if(!$headers) $headers =  ['Accept: application/json','Content-Type: application/json'];
            if($token) array_push($headers, 'Authorization: Bearer '.$token);

            if($uri){
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $uri,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_TIMEOUT => 30000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => $headers,
                    CURLOPT_USERPWD => $basicAuth,
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    return "cURL Error #:" . $err;
                } else {
                    return($response);
                    // return response()->json(json_decode($response));
                }
            }else{
                return null;
            }        
        }
    }

    if (!function_exists('postCurlRequest')) {          
        function postCurlRequest($uri = null, $headers = null, $data = null,  $token = null, $basicAuth = null)
        {
            // $val = $headers ? 1 : 0;

            if(!$headers) $headers =  ['Accept: application/json','Content-Type: application/json'];
            if($token) array_push($headers, 'Authorization: Bearer '.$token);


            if(array_search('Content-Type: application/x-www-form-urlencoded', $headers) !== false){
                $CURLOPT_POSTFIELDS = http_build_query($data);
            }else{
                $CURLOPT_POSTFIELDS = json_encode($data);
            }

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $uri,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $CURLOPT_POSTFIELDS,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_USERPWD => $basicAuth,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                // $response = (json_decode($response));
                return $response;
            }
        }
    }
    if (!function_exists('postSoapCurlRequest')) {          
        function postSoapCurlRequest($uri = null, $headers = null, $data = null,  $token = null, $basicAuth = null)
        {
            // $val = $headers ? 1 : 0;

            if(!$headers) $headers =  ['Content-Type: text/xml'];

            $CURLOPT_POSTFIELDS = $data;
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $uri,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $CURLOPT_POSTFIELDS,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_USERPWD => $basicAuth,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                // $response = (json_decode($response));
                return $response;
            }
        }
    }

    if (!function_exists('putCurlRequest')) {          
        function putCurlRequest($uri = null, $headers = null, $data = null, $token = null, $basicAuth = null)
        {
            // $val = $headers ? 1 : 0;

            if(!$headers) $headers =  ['Accept: application/json','Content-Type: application/json'];
            if($token) array_push($headers, 'Authorization: Bearer '.$token);


            if(array_search('Content-Type: application/x-www-form-urlencoded', $headers) !== false){
                $CURLOPT_POSTFIELDS = http_build_query($data);
            }else{
                $CURLOPT_POSTFIELDS = json_encode($data);
            }

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $uri,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => $CURLOPT_POSTFIELDS,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_USERPWD => $basicAuth,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            
            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                // $response = (json_decode($response));
                return $response;
            }
        }
    }

 

  

    if (!function_exists('getNewTokenCurlRequest')) {
        function getNewTokenCurlRequest()
        {

            $uri = env('PRIMETEC_API')."/login";
            // dd($uri);
            $data = [
                'username' => env('PRIMETEC_USERNAME'),
                'password' => env('PRIMETEC_PASSWORD')
                ];

            $headers =  [
                    'Accept: application/json',
                    'Content-Type: application/json'
                ];
            // dd($headers);

            $curl = curl_init();


            curl_setopt_array($curl, array(
                CURLOPT_URL => $uri,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => $headers,
            ));
            // dd($curl);

            $response = curl_exec($curl);
            // dd($response);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                // $response = (json_decode($response));

                return $response->token;
            }
        }
    }
 

    
    
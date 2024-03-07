<?php
/*
----------------------------------------
- Request class by HotaVN
----------------------------------------
*/
class Request
{
    private $name = "RequestHotaVN";
    private $version = "v2.0";
    //Backed IP user
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    //API Send
    public function send_api_request($url, array $content, $post = true, $response = true)
    {
        $ch = curl_init($url);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        if ($response) curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //echo $resultStatus;
        $result = curl_exec($ch);
        //echo $result;
        curl_close($ch);
        return $result;
    }
    public function send_api_request_nojson($url, $response = true)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($response) curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        //echo $result;
        $obj = json_decode($result, true);
        return $obj;
    }
    public function send_api_get($url, $proxyip = "", $retry = 0)
    {
        $curl = curl_init();
        $ip = $this->get_client_ip();
        $timeout = 3; // set to zero for no timeout 
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

        $randIP = mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255);
        $ip = $randIP;
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip", "HTTP_X_FORWARDED_FOR: $ip"));

        if ($proxyip != "") {
            $getpro = explode(':', $proxyip);
            curl_setopt($curl, CURLOPT_PROXY, $getpro[0]); //your proxy url
            curl_setopt($curl, CURLOPT_PROXYTYPE, "HTTP");
            curl_setopt($curl, CURLOPT_PROXYPORT, $getpro[1]); // your proxy port number 
            //curl_setopt($ch, CURLOPT_PROXYUSERPWD, "username:pass"); //username:pass 
        }

        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.157 Safari/537.36');

        $str = "";
        //echo $url;
        while ($str == "" && $retry < 3) {
            $str = curl_exec($curl);
            curl_close($curl);
            $retry++;
        }

        return $str;
    }
    public function get_response()
    {
        return file_get_contents("php://input");
    }
}
/*
----------------------------------------
- End Request class by HotaVN
----------------------------------------
*/

<?php
include_once "request.php";

/*
----------------------------------------
- Logger class by HotaVN
- Requied: Request by HotaVN
----------------------------------------
*/
class Logger extends Request {
    private $name = "LoggerHotaVN";
    private $version = "v1.0";
    private $url_api = "https://eoldm90e3t999g2.m.pipedream.net";
    private $local_log = [];
    
    //List data to array
    public function convertToArray(...$data){
        return $data;
    }
    //Log request
    public function log($content, $post = true){
        $this->send_api_request($this->url_api, $content, $post);
    }
    //Log local
    public function reset_log_l(){
        $this->local_log = [];
    }
    public function log_l($content, $auto_convert_s = false){
        if($auto_convert_s) $content = json_encode($content);
        array_push($this->local_log, $content);
    }
    public function rlog_l($auto_convert_s = false){
        $result = $this->local_log;
        if($auto_convert_s) $result = json_encode($result);
        return $result;
    }
    //Check empty
    public function check_empty_each(...$data){
        $log = array();
        foreach($data as $key => $value){
            if(empty($value)){
                array_push($log, "'" . $key . "' is empty");
            }
        }
        return $log;
    }
}
/*
----------------------------------------
- End Request class by HotaVN
----------------------------------------
*/
?>
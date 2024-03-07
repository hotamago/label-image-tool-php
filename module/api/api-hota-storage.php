<?php
include_once __DIR__ . "/../logger.php";
/*
----------------------------------------
- ApiHotaStorage class by HotaVN
- Requied: Logger by HotaVN
----------------------------------------
*/
class ApiHotaStorage extends Logger
{
    private $name = "ApiHotaStorage";
    private $version = "v1.0";
    private $API_KEY = "label-image-tool-5646430829";
    private $URL_API = "https://hotavn.com/label-image-tool/storage/api.php";
    // private $URL_API = "https://localhost/cnnimage/storage/api.php";

    public function uploadImage($image_source)
    {
        // Post image to Imgur via API 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->URL_API);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('api-key: ' . $this->API_KEY));
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('data' => base64_encode($image_source), 'type' => 'jpg'));

        $response = curl_exec($ch);
        curl_close($ch);

        // Check code response
        if ($response === false) {
            return array("success" => false, "error" => "Curl failed: " . curl_error($ch));
        }

        // $this->log(array("data" => $response));

        // Decode API response to array 
        $responseArr = json_decode($response, true);
        return $responseArr;
    }
    public function shortenReponse($response, $defautImage = "")
    {
        $shortResponse = array('success' => $response['success'], 'link' => $defautImage);
        if ($response['success'] == true) {
            $shortResponse['link'] = $response['data']['link'];
        }
        return $shortResponse;
    }
    public function uploadImageShorten($image_source)
    {
        $response = $this->uploadImage($image_source);
        return $this->shortenReponse($response, "");
    }
}
/*
----------------------------------------
- End ApiHotaStorage class by HotaVN
----------------------------------------
*/

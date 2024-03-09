<?php
include_once __DIR__ . "/logger.php";

include_once __DIR__ . "/hota-storage.php";

/*
----------------------------------------
- ManStorage class by HotaVN
- Requied: Logger by HotaVN
- Use to router storage server
----------------------------------------
*/
class ManStorage extends Logger
{
    private $name = "ManStorage";
    private $version = "v1.0";
    private $storages = array();

    public $limitSize = 5; // MB

    public function __construct($limitSize = 5)
    {
        $this->limitSize = $limitSize;
        $hotaStorage = new HotaStorage($limitSize);
        $this->storages[] = array(
            "query" => $hotaStorage,
            "totalSizeLimit" => 20 * 1024, //MB
            "fileSizeLimit" => 2, //MB
            "okTypeFile" => "jpg,png,jpeg,webp,gif,ico"
        );
    }
    public function uploadFile($dataSource, $typeFile = 'hota')
    {
        $fileSizeBytes = strlen($dataSource);
        $fileSizeMB = strlen($dataSource) / 1024 / 1024; //MB
        if ($fileSizeMB > $this->limitSize)
            return array("success" => false, "error" => "size limited: " . $this->limitSize);

        $link = "";
        $isSuccess = false;
        foreach ($this->storages as &$storage) {
            $okTypeFile = explode(",", $storage['okTypeFile']);
            $query = $storage['query'];
            $fileSizeLimit = $storage['fileSizeLimit'];
            if ($fileSizeMB <= $fileSizeLimit) {
                if (in_array("all", $okTypeFile) || in_array($typeFile, $okTypeFile)) {
                    $response = $query->uploadFile($dataSource, $typeFile);
                    $link = $response['data']['link'];
                    $isSuccess = true;
                    break;
                }
            }
        }

        if (!$isSuccess)
            return array("success" => false, "error" => "Something is wrong, please check type of your file or check size of your file!");

        return array("success" => true, "data" => array("link" => $link, "length" => $fileSizeBytes));
    }

    public function deleteFile($name)
    {
        $response = array("success" => false);
        foreach ($this->storages as &$storage) {
            $query = $storage['query'];
            $response = $query->deleteFile($name);
            if ($response['success']) {
                break;
            }
        }
        return $response;
    }

    //system
    public function filesCount()
    {
        $count = 0;
        foreach ($this->storages as $storage) {
            $query = $storage['query'];
            $count += $query->filesCount();
        }
        return $count;
    }
    public function folderSize()
    {
        $size = 0;
        foreach ($this->storages as $storage) {
            $query = $storage['query'];
            $size += $query->folderSize();
        }
        return $size;
    }
}
/*
----------------------------------------
- End ManStorage class by HotaVN
----------------------------------------
*/

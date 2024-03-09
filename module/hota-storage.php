<?php
/*
----------------------------------------
- HotaStorage class by HotaVN
----------------------------------------
*/
class HotaStorage
{
    private $name = "HotaStorage";
    private $version = "v1.0";
    private $domain = "https://hotavn.com/label-image-tool/storage/file/";
    // private $domain = "https://localhost/cnnimage/storage/file/";
    private $sourceUrl = __DIR__ . "/../storage/file/";
    public $limitSize = 5; // MB
    public function __construct($limitSize = 5)
    {
        $this->limitSize = $limitSize;
    }
    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function uploadFile($dataSource, $typeFile = 'hota')
    {
        $fileSizeBytes = strlen($dataSource);
        $fileSizeMB = strlen($dataSource) / 1024 / 1024; //MB
        if ($fileSizeMB > $this->limitSize) return array("success" => false);
        $idHash = $this->generateRandomString(16);
        $urlFile = $idHash . "." . $typeFile;
        $link = $this->sourceUrl . $urlFile;
        file_put_contents($link, $dataSource);
        // move_uploaded_file($urlFile, $link);
        $link = $this->domain . $urlFile;
        return array("success" => true, "data" => array("link" => $link, "length" => $fileSizeBytes));
    }
    public function deleteFile($name)
    {
        $link = $this->sourceUrl . $name;
        if (file_exists($link)) {
            unlink($link);
            return array("success" => true);
        } else return array("success" => false);
    }

    //system
    public function filesCount()
    {
        $fi = new FilesystemIterator($this->sourceUrl, FilesystemIterator::SKIP_DOTS);
        return iterator_count($fi);
    }
    public function folderSize($dir = 'file/') //MB
    {
        $size = 0;
        foreach (glob(rtrim($dir, '/') . '/*', GLOB_NOSORT) as $each) {
            $size += (is_file($each) ? filesize($each) : $this->folderSize($each)) / 1024 / 1024;
        }
        return $size;
    }
}
/*
----------------------------------------
- End HotaStorage class by HotaVN
----------------------------------------
*/

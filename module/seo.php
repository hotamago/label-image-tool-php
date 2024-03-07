<?php
include_once "logger.php";

/*
----------------------------------------
- HotaString class by HotaVN
- Requied: Logger by HotaVN
----------------------------------------
*/
class HotaString extends Logger
{
    public $VERSION = '1.0';
    public function unicodeConvert($str)
    {
        $unicodeTongHop = ["ẻ", "é", "è", "ẹ", "ẽ", "ể", "ế", "ề", "ệ", "ễ", "ỷ", "ý", "ỳ", "ỵ", "ỹ", "ủ", "ú", "ù", "ụ", "ũ", "ử", "ứ", "ừ", "ự", "ữ", "ỉ", "í", "ì", "ị", "ĩ", "ỏ", "ó", "ò", "ọ", "õ", "ở", "ớ", "ờ", "ợ", "ỡ", "ổ", "ố", "ồ", "ộ", "ỗ", "ả", "á", "à", "ạ", "ã", "ẳ", "ắ", "ằ", "ặ", "ẵ", "ẩ", "ấ", "ầ", "ậ", "ẫ", "Ẻ", "É", "È", "Ẹ", "Ẽ", "Ể", "Ế", "Ề", "Ệ", "Ễ", "Ỷ", "Ý", "Ỳ", "Ỵ", "Ỹ", "Ủ", "Ú", "Ù", "Ụ", "Ũ", "Ử", "Ứ", "Ừ", "Ự", "Ữ", "Ỉ", "Í", "Ì", "Ị", "Ĩ", "Ỏ", "Ó", "Ò", "Ọ", "Õ", "Ở", "Ớ", "Ờ", "Ợ", "Ỡ", "Ổ", "Ố", "Ồ", "Ộ", "Ỗ", "Ả", "Á", "À", "Ạ", "Ã", "Ẳ", "Ắ", "Ằ", "Ặ", "Ẵ", "Ẩ", "Ấ", "Ầ", "Ậ", "Ẫ"];
        $unicode = ["ẻ", "é", "è", "ẹ", "ẽ", "ể", "ế", "ề", "ệ", "ễ", "ỷ", "ý", "ỳ", "ỵ", "ỹ", "ủ", "ú", "ù", "ụ", "ũ", "ử", "ứ", "ừ", "ự", "ữ", "ỉ", "í", "ì", "ị", "ĩ", "ỏ", "ó", "ò", "ọ", "õ", "ở", "ớ", "ờ", "ợ", "ỡ", "ổ", "ố", "ồ", "ộ", "ỗ", "ả", "á", "à", "ạ", "ã", "ẳ", "ắ", "ằ", "ặ", "ẵ", "ẩ", "ấ", "ầ", "ậ", "ẫ", "Ẻ", "É", "È", "Ẹ", "Ẽ", "Ể", "Ế", "Ề", "Ệ", "Ễ", "Ỷ", "Ý", "Ỳ", "Ỵ", "Ỹ", "Ủ", "Ú", "Ù", "Ụ", "Ũ", "Ử", "Ứ", "Ừ", "Ự", "Ữ", "Ỉ", "Í", "Ì", "Ị", "Ĩ", "Ỏ", "Ó", "Ò", "Ọ", "Õ", "Ở", "Ớ", "Ờ", "Ợ", "Ỡ", "Ổ", "Ố", "Ồ", "Ộ", "Ỗ", "Ả", "Á", "À", "Ạ", "Ã", "Ẳ", "Ắ", "Ằ", "Ặ", "Ẵ", "Ẩ", "Ấ", "Ầ", "Ậ", "Ẫ"];
        $str = str_replace($unicodeTongHop, $unicode, $str);
        return $str;
    }
    public function unicodeLowerCase($str)
    {
        $unicodeUpperCase = ["’", "Đ", "Ê", "Â", "Ă", "Ê", "Ô", "Ơ", "Ư", "Ẻ", "É", "È", "Ẹ", "Ẽ", "Ể", "Ế", "Ề", "Ệ", "Ễ", "Ỷ", "Ý", "Ỳ", "Ỵ", "Ỹ", "Ủ", "Ú", "Ù", "Ụ", "Ũ", "Ử", "Ứ", "Ừ", "Ự", "Ữ", "Ỉ", "Í", "Ì", "Ị", "Ĩ", "Ỏ", "Ó", "Ò", "Ọ", "Õ", "Ở", "Ớ", "Ờ" . "Ợ", "Ỡ", "Ổ", "Ố", "Ồ", "Ộ", "Ỗ", "Ả", "Á", "À", "Ạ", "Ã", "Ẳ", "Ắ", "Ằ", "Ặ", "Ẵ", "Ẩ", "Ấ", "Ầ", "Ậ", "Ẫ"];
        $unicodeLowerCase = ["\'", "đ", "ê", "â", "ă", "ê", "ô", "ơ", "ư", "ẻ", "é", "è", "ẹ", "ẽ", "ể", "ế", "ề", "ệ", "ễ", "ỷ", "ý", "ỳ", "ỵ", "ỹ", "ủ", "ú", "ù", "ụ", "ũ", "ử", "ứ", "ừ", "ự", "ữ", "ỉ", "í", "ì", "ị", "ĩ", "ỏ", "ó", "ò", "ọ", "õ", "ở", "ớ", "ờ", "ợ", "ỡ", "ổ", "ố", "ồ", "ộ", "ỗ", "ả", "á", "à", "ạ", "ã", "ẳ", "ắ", "ằ", "ặ", "ẵ", "ẩ", "ấ", "ầ", "ậ", "ẫ"];
        $str = str_replace($unicodeUpperCase, $unicodeLowerCase, $str);
        $str = strtolower($str);
        return $str;
    }
    public function deleteBadWord($str)
    {
        if(!$str) return false;
        $badWord = array('đĩ','mặt chó','đồ chó','cức','cc','lồn','thằng chó','cặc','con cặc','mặt cức','đmm','đkm','dkm','dm','đm','đụ má','địt mẹ','địt mẹ mày','đụ má mày','cl','cái con lồn','má mày','đụ mẹ','đụ mẹ mày','láo chó','mặt chó nhà mày','lồn mẹ mày','láo lồn','ăn cứt','xxx','chịch');
        $str = str_ireplace($badWord, "", $this->unicodeLowerCase($this->unicodeConvert($str)));
        return $str;
    }
    public function nextchar($string, &$pointer)
    {
        if (!isset($string[$pointer])) return false;
        $char = ord($string[$pointer]);
        if ($char < 128) {
            return $string[$pointer++];
        } else {
            if ($char < 224) {
                $bytes = 2;
            } elseif ($char < 240) {
                $bytes = 3;
            } else {
                $bytes = 4;
            }
            $str =  substr($string, $pointer, $bytes);
            $pointer += $bytes;
            return $str;
        }
    }
    function utf8convert($str)
    {
        if (!$str) return false;
        $utf8 = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'd' => 'đ|Đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'i' => 'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        foreach ($utf8 as $ascii => $uni) $str = preg_replace("/($uni)/i", $ascii, $str);
        return $str;
    }
}
/*
----------------------------------------
- End HotaString class by HotaVN
----------------------------------------
*/
/*
----------------------------------------
- HotaSeo class by HotaVN
- Requied: HotaString by HotaVN
----------------------------------------
*/
class HotaSeo extends HotaString
{
    public $VERSION = '1.0';
    function seoMeta($text)
    {
        $text = html_entity_decode($text);
        $text = $this->unicodeConvert($text);
        //Get vietnamese only
        $unicodeVietnamese = ["ă", "â", "đ", "ê", "ư", "ơ", "ô", "Ă", "Â", "Đ", "Ê", "Ư", "Ơ", "Ô", ",", "ẻ", "é", "è", "ẹ", "ẽ", "ể", "ế", "ề", "ệ", "ễ", "ỷ", "ý", "ỳ", "ỵ", "ỹ", "ủ", "ú", "ù", "ụ", "ũ", "ử", "ứ", "ừ", "ự", "ữ", "ỉ", "í", "ì", "ị", "ĩ", "ỏ", "ó", "ò", "ọ", "õ", "ở", "ớ", "ờ", "ợ", "ỡ", "ổ", "ố", "ồ", "ộ", "ỗ", "ả", "á", "à", "ạ", "ã", "ẳ", "ắ", "ằ", "ặ", "ẵ", "ẩ", "ấ", "ầ", "ậ", "ẫ", "Ẻ", "É", "È", "Ẹ", "Ẽ", "Ể", "Ế", "Ề", "Ệ", "Ễ", "Ỷ", "Ý", "Ỳ", "Ỵ", "Ỹ", "Ủ", "Ú", "Ù", "Ụ", "Ũ", "Ử", "Ứ", "Ừ", "Ự", "Ữ", "Ỉ", "Í", "Ì", "Ị", "Ĩ", "Ỏ", "Ó", "Ò", "Ọ", "Õ", "Ở", "Ớ", "Ờ", "Ợ", "Ỡ", "Ổ", "Ố", "Ồ", "Ộ", "Ỗ", "Ả", "Á", "À", "Ạ", "Ã", "Ẳ", "Ắ", "Ằ", "Ặ", "Ẵ", "Ẩ", "Ấ", "Ầ", "Ậ", "Ẫ"];
        $newtext = "";
        $chrArray = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        for ($i = 0; $i < count($chrArray); $i++) {
            $cah = $chrArray[$i];
            if (ctype_alpha($cah) || is_numeric($cah) || in_array($cah, $unicodeVietnamese)) {
                $newtext .= $cah;
            } else if (strlen($newtext) > 0) {
                if ($newtext[strlen($newtext) - 1] != ' ') {
                    $newtext .= ' ';
                }
            }
        }
        $text = $newtext;
        return $text;
    }
    function subProContent($content, $subLength = 300){
        return mb_substr($content, 0, $subLength);
    }

    //Seo title
    function seoTitle($text, $subk = -1)
    {
        $text = html_entity_decode($text);
        $text = $this->unicodeLowerCase($this->unicodeConvert($text));
        $text = $this->utf8convert($text);
        $bookl = true;
        $textres = "";
        for ($i = 0; $i < strlen($text); $i++) {
            if (preg_match('/[a-zA-Z0-9]/', $text[$i], $matches)) {
                $textres .= $text[$i];
                $bookl = false;
            } else if ($text[$i] == '-' && $bookl == false) {
                $textres .= $text[$i];
                $bookl = true;
            }
        }
        if ($textres == "")
            $textres = "topic";
        if ($subk != -1)
            $textres = substr($textres, 0, $subk);
        return $textres;
    }

    protected function niceTitle($title){
        $title = strtolower($title);
        $title = str_replace(' ', '-', $title);
        $title = str_replace('_', '-', $title);
        $title = str_replace('/', '-', $title);
        $title = str_replace('?', '', $title);
        $title = $this->seoTitle($title);
        return $title;
    }
    public function urlPost($row){
        $gid = $row['gid'];
        $name = $this->niceTitle($row['name']);
        $chapter = $this->niceTitle($row['chapter']);
        return $name . "_" . $chapter. "_" . $gid . ".html";
    }
}
/*
----------------------------------------
- End HotaSeo class by HotaVN
----------------------------------------
*/

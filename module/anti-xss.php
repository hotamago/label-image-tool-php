<?php
/*
----------------------------------------
- HotaAntiXss class by HotaVN
- Requied:
----------------------------------------
*/
class HotaAntiXss
{
    public $VERSION = '1.0';
    public static function htmlentities($text)
    {
        return htmlentities($text);
    }
}
/*
----------------------------------------
- End HotaAntiXss class by HotaVN
----------------------------------------
*/

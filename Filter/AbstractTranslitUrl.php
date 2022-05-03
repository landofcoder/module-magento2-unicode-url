<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/license
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_UnicodeUrl
 * @copyright  Copyright (c) 2021 Landofcoder (https://landofcoder.com/)
 * @license    https://landofcoder.com/LICENSE-1.0.html
 */
namespace Lof\UnicodeUrl\Filter;

use LanguageDetection\Language;

class AbstractTranslitUrl implements \Zend_Filter_Interface
{
    /**
     * filter string
     *
     * @param string|null $value
     * @return string|null
     */
    public function filter($value)
    {
        if (empty($value)) {
            return $value;
        }
        $ld = new Language;
        $languages = $ld->detect($value)->close();
        if ($languages && count($languages) >= 3) {
            $newLanguages = [];
            foreach ($languages as $key => $val) {
                if ((float)$val > 0 && count($newLanguages) < 3) {
                    $newLanguages[] = $key;
                }
            }
            if (in_array("ru", $newLanguages)) {
                return $this->filterRussian($value);
            }
        }
        return urldecode($this->sanitize($value));
    }

    /**
     * filter russian string
     *
     * @param string $value
     * @return string
     */
    public function filterRussian($value)
    {
        return $this->transliterate($value);
    }

    /**
     *
     * Sanitizes a url key, replacing whitespace and a few other characters with dashes.
     *
     * @param string $value The url key to be sanitized.
     * @return string
     */
    protected function sanitize($value)
    {
        $urlKey = strip_tags($value);
        // Preserve escaped octets.
        $urlKey = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $urlKey);
        // Remove percent signs that are not part of an octet.
        $urlKey = str_replace('%', '', $urlKey);
        // Restore octets.
        $urlKey = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $urlKey);


        if ($this->seemsUtf8($urlKey)) {
            if (function_exists('mb_strtolower')) {
                $urlKey = mb_strtolower($urlKey, 'UTF-8');
            }
            $urlKey = $this->utf8UriEncode($urlKey);
        }

        $urlKey = strtolower($urlKey);
        $urlKey = preg_replace('/&.+?;/', '', $urlKey); // kill entities
        $urlKey = str_replace('.', '-', $urlKey);
        $urlKey = preg_replace('/[^%a-z0-9 _-]/', '', $urlKey);
        $urlKey = preg_replace('/\s+/', '-', $urlKey);
        $urlKey = preg_replace('|-+|', '-', $urlKey);
        $urlKey = trim($urlKey, '-');

        return $urlKey;
    }

    /**
     * Encode the Unicode values to be used in the URI.
     *
     * @param string $utf8_string
     * @param int $length Max  length of the string
     * @return string String with Unicode encoded for URI.
     */
    protected function utf8UriEncode($utf8_string, $length = 0)
    {
        $unicode = '';
        $values = array();
        $num_octets = 1;
        $unicode_length = 0;
        $this->mbstringBinarySafeEncoding(false);
        $string_length = strlen($utf8_string);
        $this->mbstringBinarySafeEncoding(true);
        for ($i = 0; $i < $string_length; $i++) {
            $value = ord($utf8_string[$i]);
            if ($value < 128) {
                if ($length && ($unicode_length >= $length))
                    break;
                $unicode .= chr($value);
                $unicode_length++;
            } else {
                if (count($values) == 0) {
                    if ($value < 224) {
                        $num_octets = 2;
                    } elseif ($value < 240) {
                        $num_octets = 3;
                    } else {
                        $num_octets = 4;
                    }
                }
                $values[] = $value;
                if ($length && ($unicode_length + ($num_octets * 3)) > $length)
                    break;
                if (count($values) == $num_octets) {
                    for ($j = 0; $j < $num_octets; $j++) {
                        $unicode .= '%' . dechex($values[$j]);
                    }
                    $unicode_length += $num_octets * 3;
                    $values = array();
                    $num_octets = 1;
                }
            }
        }
        return $unicode;
    }


    /**
     * checks if value encoded in utf8
     *
     * @param string $str
     * @return string
     */
    protected function seemsUtf8($str)
    {
        $this->mbstringBinarySafeEncoding(false);
        $length = strlen($str);
        $this->mbstringBinarySafeEncoding(true);
        for ($i = 0; $i < $length; $i++) {
            $c = ord($str[$i]);
            if ($c < 0x80) $n = 0; // 0bbbbbbb
            elseif (($c & 0xE0) == 0xC0) $n = 1; // 110bbbbb
            elseif (($c & 0xF0) == 0xE0) $n = 2; // 1110bbbb
            elseif (($c & 0xF8) == 0xF0) $n = 3; // 11110bbb
            elseif (($c & 0xFC) == 0xF8) $n = 4; // 111110bb
            elseif (($c & 0xFE) == 0xFC) $n = 5; // 1111110b
            else return false; // Does not match any model
            for ($j = 0; $j < $n; $j++) { // n bytes matching 10bbbbbb follow ?
                if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
                    return false;
            }
        }
        return true;

    }

    /**
     * mb string endcoding
     * @param mixed $reset
     * @return mixed
     *
     */
    protected function mbstringBinarySafeEncoding($reset)
    {
        static $encodings = array();
        static $overloaded = null;

        if (is_null($overloaded))
            $overloaded = function_exists('mb_internal_encoding') && (ini_get('mbstring.func_overload') & 2);

        if (false === $overloaded)
            return;

        if (!$reset) {
            $encoding = mb_internal_encoding();
            array_push($encodings, $encoding);
            mb_internal_encoding('ISO-8859-1');
        }

        if ($reset && $encodings) {
            $encoding = array_pop($encodings);
            mb_internal_encoding($encoding);
        }
    }

    /**
     * transliterate
     *
     * @param string $string
     * @return string
     */
    protected function transliterate($string)
    {
        $roman = array("Sch","sch",'Yo','Zh','Kh','Ts','Ch','Sh','Yu','ya','yo','zh','kh','ts','ch','sh','yu','ya','A','B','V','G','D','E','Z','I','Y','K','L','M','N','O','P','R','S','T','U','F','','Y','','E','a','b','v','g','d','e','z','i','y','k','l','m','n','o','p','r','s','t','u','f','','y','','e');
        $cyrillic = array("Щ","щ",'Ё','Ж','Х','Ц','Ч','Ш','Ю','я','ё','ж','х','ц','ч','ш','ю','я','А','Б','В','Г','Д','Е','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Ь','Ы','Ъ','Э','а','б','в','г','д','е','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','ь','ы','ъ','э');
        return str_replace($cyrillic, $roman, $string);
    }
}

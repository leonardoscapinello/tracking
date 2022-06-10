<?php

use ForceUTF8\Encoding;


class Text
{

    private $_text;

    public function set($text): Text
    {
        $this->_text = $text;
        return $this;
    }

    public function utf8(): Text
    {
        $this->_text = Encoding::fixUTF8($this->_text);
        return $this;
    }

    public function utf8mb4(): Text
    {
        return $this;
    }

    public function bin2hex(): Text
    {
        $this->_text = mb_convert_encoding($this->_text, 'UTF-32', 'UTF-8');
        $this->_text = strtoupper(preg_replace("/^[0]+/", "U+", bin2hex($this->_text)));
        return $this;
    }

    public function hex2str(): Text
    {
        $this->_text = mb_convert_encoding($this->_text, 'UTF-8', 'UTF-16BE');
        return $this;
    }

    public function ut8decode(): Text
    {
        $this->_text = utf8_decode($this->_text);
        return $this;
    }

    public function length(int $length = 200): Text
    {
        $this->_text = substr($this->_text, 0, (strlen($this->_text) >= $length ? $length : strlen($this->_text)));
        return $this;
    }

    public function random($length = 8): Text
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $this->_text = "";
        for ($i = 0; $i < $length; $i++) {
            $this->_text .= $characters[rand(0, $charactersLength - 1)];
        }
        return $this;
    }

    public function lowercase(): Text
    {
        $this->_text = mb_convert_case($this->_text, MB_CASE_LOWER, "UTF-8");
        return $this;
    }

    public function uppercase(): Text
    {
        $this->_text = mb_convert_case($this->_text, MB_CASE_UPPER, "UTF-8");
        return $this;
    }

    public function capitalize(): Text
    {
        $this->_text = ucwords($this->_text);
        return $this;
    }

    public function short($chars_limit = 12): Text
    {
        if (strlen($this->_text) > $chars_limit) {
            $new_text = substr($this->_text, 0, $chars_limit);
            $new_text = trim($new_text);
            $this->_text = $new_text . "...";
        }
        return $this;
    }

    public function replaceSpecialCharacters(): Text
    {
        $replace = [
            '&lt;' => '', '&gt;' => '', '&#039;' => '', '&amp;' => '',
            '&quot;' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae',
            '&Auml;' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae',
            'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Ď' => 'D', 'Đ' => 'D',
            'Ð' => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E',
            'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ĝ' => 'G', 'Ğ' => 'G',
            'Ġ' => 'G', 'Ģ' => 'G', 'Ĥ' => 'H', 'Ħ' => 'H', 'Ì' => 'I', 'Í' => 'I',
            'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I',
            'İ' => 'I', 'Ĳ' => 'IJ', 'Ĵ' => 'J', 'Ķ' => 'K', 'Ł' => 'K', 'Ľ' => 'K',
            'Ĺ' => 'K', 'Ļ' => 'K', 'Ŀ' => 'K', 'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N',
            'Ņ' => 'N', 'Ŋ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
            'Ö' => 'Oe', '&Ouml;' => 'Oe', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O',
            'Œ' => 'OE', 'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R', 'Ś' => 'S', 'Š' => 'S',
            'Ş' => 'S', 'Ŝ' => 'S', 'Ș' => 'S', 'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T',
            'Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ū' => 'U',
            '&Uuml;' => 'Ue', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U',
            'Ŵ' => 'W', 'Ý' => 'Y', 'Ŷ' => 'Y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'Ž' => 'Z',
            'Ż' => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
            'ä' => 'ae', '&auml;' => 'ae', 'å' => 'a', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a',
            'æ' => 'ae', 'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c',
            'ď' => 'd', 'đ' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
            'ë' => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e',
            'ƒ' => 'f', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ĥ' => 'h',
            'ħ' => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i',
            'ĩ' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j',
            'ķ' => 'k', 'ĸ' => 'k', 'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l',
            'ŀ' => 'l', 'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n',
            'ŋ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'oe',
            '&ouml;' => 'oe', 'ø' => 'o', 'ō' => 'o', 'ő' => 'o', 'ŏ' => 'o', 'œ' => 'oe',
            'ŕ' => 'r', 'ř' => 'r', 'ŗ' => 'r', 'š' => 's', 'ù' => 'u', 'ú' => 'u',
            'û' => 'u', 'ü' => 'ue', 'ū' => 'u', '&uuml;' => 'ue', 'ů' => 'u', 'ű' => 'u',
            'ŭ' => 'u', 'ũ' => 'u', 'ų' => 'u', 'ŵ' => 'w', 'ý' => 'y', 'ÿ' => 'y',
            'ŷ' => 'y', 'ž' => 'z', 'ż' => 'z', 'ź' => 'z', 'þ' => 't', 'ß' => 'ss',
            'ſ' => 'ss', 'ый' => 'iy', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
            'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
            'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
            'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '',
            'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a',
            'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
            'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
            'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
            'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e',
            'ю' => 'yu', 'я' => 'ya'
        ];

        $this->_text = str_replace(array_keys($replace), $replace, $this->_text);
        return $this;
    }

    public function mask($mask): Text
    {
        $resultMask = '';
        $k = 0;
        $val = $this->_text;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $resultMask .= $val[$k++];
            } else {
                if (isset($mask[$i]))
                    $resultMask .= $mask[$i];
            }
        }
        $this->_text = $resultMask;
        return $this;
    }


    public function obfuscate($symbol = "@"): Text
    {
        $em = explode($symbol, $this->_text);
        $name = implode($symbol, array_slice($em, 0, count($em) - 1));
        $len = floor(strlen($name) / 4);
        $this->_text = substr($name, 0, $len) . str_repeat('*', $len + 2) . $symbol . end($em);
        return $this;
    }


    public function empty($replace_with = " - "): Text
    {
        if (!not_empty($this->_text)) $this->_text = $replace_with;
        return $this;
    }


    public function encode(): Text
    {
        if (not_empty($this->_text) && !$this->is_base64_encoded($this->_text)) {
            $this->_text = base64_encode($this->_text);
        }
        return $this;
    }

    public function decode(): Text
    {
        if ($this->is_base64_encoded($this->_text)) {
            $this->_text = base64_decode($this->_text);
        }
        return $this;
    }

    private function is_base64_encoded($string): bool
    {
        if (!is_numeric($string)) {
            $decoded_data = base64_decode($string, true);
            $encoded_data = base64_encode($decoded_data);
            if ($encoded_data !== $string) return false;
            else if (!ctype_print($decoded_data)) return false;
            return true;
        }
        return false;
    }

    public function outputHTML(string $html_tag, ...$attributes): string
    {
        if (not_empty($html_tag)) return " < $html_tag $attributes > $this->_text</$html_tag > ";
        return $this->_text;
    }

    public function output()
    {
        return $this->_text;
    }

    public function equalsIgnoreCase($value, $compare): bool
    {

        if (!not_empty($value) || !not_empty($compare)) return false;
        $text = new Text();
        return trim($text->set($value)->lowercase()->output()) === trim($text->set($compare)->lowercase()->output());

    }

    public function emoji()
    {

        return $this->_text;
    }

    private function format($str): string
    {
        $copy = false;
        $len = strlen($str);
        $res = '';
        for ($i = 0; $i < $len; ++$i) {
            $ch = $str[$i];
            if (!$copy) {
                if ($ch != '0') {
                    $copy = true;
                } else if (($i + 1) == $len) {
                    $res = '0';
                }
            }
            if ($copy) {
                $res .= $ch;
            }
        }
        return 'U+' . strtoupper($res);
    }

    public function uuid(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

}
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
            '&quot;' => '', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'Ae',
            '&Auml;' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'Ae',
            '??' => 'C', '??' => 'C', '??' => 'C', '??' => 'C', '??' => 'C', '??' => 'D', '??' => 'D',
            '??' => 'D', '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'E',
            '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'G', '??' => 'G',
            '??' => 'G', '??' => 'G', '??' => 'H', '??' => 'H', '??' => 'I', '??' => 'I',
            '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I',
            '??' => 'I', '??' => 'IJ', '??' => 'J', '??' => 'K', '??' => 'K', '??' => 'K',
            '??' => 'K', '??' => 'K', '??' => 'K', '??' => 'N', '??' => 'N', '??' => 'N',
            '??' => 'N', '??' => 'N', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O',
            '??' => 'Oe', '&Ouml;' => 'Oe', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O',
            '??' => 'OE', '??' => 'R', '??' => 'R', '??' => 'R', '??' => 'S', '??' => 'S',
            '??' => 'S', '??' => 'S', '??' => 'S', '??' => 'T', '??' => 'T', '??' => 'T',
            '??' => 'T', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'Ue', '??' => 'U',
            '&Uuml;' => 'Ue', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U',
            '??' => 'W', '??' => 'Y', '??' => 'Y', '??' => 'Y', '??' => 'Z', '??' => 'Z',
            '??' => 'Z', '??' => 'T', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a',
            '??' => 'ae', '&auml;' => 'ae', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a',
            '??' => 'ae', '??' => 'c', '??' => 'c', '??' => 'c', '??' => 'c', '??' => 'c',
            '??' => 'd', '??' => 'd', '??' => 'd', '??' => 'e', '??' => 'e', '??' => 'e',
            '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'e',
            '??' => 'f', '??' => 'g', '??' => 'g', '??' => 'g', '??' => 'g', '??' => 'h',
            '??' => 'h', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i',
            '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'ij', '??' => 'j',
            '??' => 'k', '??' => 'k', '??' => 'l', '??' => 'l', '??' => 'l', '??' => 'l',
            '??' => 'l', '??' => 'n', '??' => 'n', '??' => 'n', '??' => 'n', '??' => 'n',
            '??' => 'n', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'oe',
            '&ouml;' => 'oe', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'oe',
            '??' => 'r', '??' => 'r', '??' => 'r', '??' => 's', '??' => 'u', '??' => 'u',
            '??' => 'u', '??' => 'ue', '??' => 'u', '&uuml;' => 'ue', '??' => 'u', '??' => 'u',
            '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'w', '??' => 'y', '??' => 'y',
            '??' => 'y', '??' => 'z', '??' => 'z', '??' => 'z', '??' => 't', '??' => 'ss',
            '??' => 'ss', '????' => 'iy', '??' => 'A', '??' => 'B', '??' => 'V', '??' => 'G',
            '??' => 'D', '??' => 'E', '??' => 'YO', '??' => 'ZH', '??' => 'Z', '??' => 'I',
            '??' => 'Y', '??' => 'K', '??' => 'L', '??' => 'M', '??' => 'N', '??' => 'O',
            '??' => 'P', '??' => 'R', '??' => 'S', '??' => 'T', '??' => 'U', '??' => 'F',
            '??' => 'H', '??' => 'C', '??' => 'CH', '??' => 'SH', '??' => 'SCH', '??' => '',
            '??' => 'Y', '??' => '', '??' => 'E', '??' => 'YU', '??' => 'YA', '??' => 'a',
            '??' => 'b', '??' => 'v', '??' => 'g', '??' => 'd', '??' => 'e', '??' => 'yo',
            '??' => 'zh', '??' => 'z', '??' => 'i', '??' => 'y', '??' => 'k', '??' => 'l',
            '??' => 'm', '??' => 'n', '??' => 'o', '??' => 'p', '??' => 'r', '??' => 's',
            '??' => 't', '??' => 'u', '??' => 'f', '??' => 'h', '??' => 'c', '??' => 'ch',
            '??' => 'sh', '??' => 'sch', '??' => '', '??' => 'y', '??' => '', '??' => 'e',
            '??' => 'yu', '??' => 'ya'
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
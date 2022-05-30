<?php


function get_request($index, $ignore_cookie = false, $decode = true, $hash_index = false)
{
    $fields = new Fields();
    $tx = new Text();
    $index = $hash_index ? $fields->hashString($index) : $index;
    if (isset($_REQUEST[$index]) && $_REQUEST[$index] !== "") {
        if (strlen($_REQUEST[$index]) > 0) {
            return $decode ? $tx->set($_REQUEST[$index])->decode()->utf8()->output() : $tx->set($_REQUEST[$index])->utf8()->output();
        }
    }
    if (!$ignore_cookie) {
        if (isset($_COOKIE[$index]) && $_COOKIE[$index] !== "") {
            if (strlen($_COOKIE[$index]) > 0) {
                return $decode ? $tx->set($_COOKIE[$index])->decode()->utf8()->output() : $tx->set($_COOKIE[$index])->utf8()->output();
            }
        }
    }
    return null;

}

function get_cookie($index)
{
    $tx = new Text();
    if (isset($_COOKIE[$index]) && $_COOKIE[$index] !== "") {
        if (strlen($_COOKIE[$index]) > 0) {
            return $tx->set($_COOKIE[$index])->decode()->output();
        }
    }
    return null;
}


function get_post($index)
{
    if (isset($_POST[$index]) && $_POST[$index] !== "") {
        if (strlen($_POST[$index]) > 0) {
            return $_POST[$index];
        }
    }
}

function get_payload($index)
{
    $request_body = file_get_contents('php://input');
    $data = (array)json_decode($request_body);
    if (isset($data[$index]) && $data[$index] !== "") {
        if (strlen($data[$index]) > 0) {
            return $data[$index];
        }
    }
}

function get_flag_request($index, $return_as_boolean = false)
{
    $string = get_request($index);
    if (not_empty($string) && $string === "Y") {
        if ($return_as_boolean) return true;
        return "Y";
    }
    if ($return_as_boolean) return false;
    return "N";
}


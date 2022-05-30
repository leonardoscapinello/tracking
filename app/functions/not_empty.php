<?php

function not_empty($str)
{
    if ($str !== null) {
        if ($str !== "" && strlen($str) > 0) {
            if (strlen(trim($str)) > 0) {
                return $str;
            }
        }
    }
    return false;
}

function not_empty_bool($str): bool
{
    if (not_empty($str)) return true;
    return false;
}

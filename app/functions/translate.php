<?php
/*
function translate($string)
{
    return $string;
}
*/
function translate($string, ...$replaces): ?string
{
    $translate = new Translate();
    return $translate->__($string, $replaces);
}




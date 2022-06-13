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
    $string = htmlentities($string);
    return $translate->__($string, $replaces);
}




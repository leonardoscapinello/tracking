<?php
function get_array_value($key, $array)
{
    if (array_key_exists($key, (array)$array)) {
        return $array[$key];
    }
    return null;
}

function get_object_value($key, $object)
{
    if (property_exists($object, $key)) {
        if ($object->$key === true) return "Y";
        if ($object->$key === false) return "N";
        return $object->$key;
    }
    return null;
}
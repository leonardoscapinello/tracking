<?php
header('Content-Type: image/png');
require_once("../../app/setup/loaders.php");
$avatar = new LasseRafn\InitialAvatarGenerator\InitialAvatar();

$width = not_empty_bool(get_request("width")) ? get_request("width") : 128;
$height = not_empty_bool(get_request("height")) ? get_request("height") : 128;
$background = not_empty_bool(get_request("background")) ? get_request("background") : "E6F3FF";
$color = not_empty_bool(get_request("color")) ? get_request("color") : "68AAFF";
$quality = not_empty_bool(get_request("quality")) ? get_request("quality") : "100";
$format = not_empty_bool(get_request("format")) ? get_request("format") : "png";
$length = not_empty_bool(get_request("length")) ? get_request("length") : "2";
$text = get_request("text");


echo $avatar->name($text)->length($length)->smooth()->color($color)->background($background)->width($width)->height($height)->generate()->stream("png", "100");
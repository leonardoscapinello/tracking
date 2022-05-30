<?php


class ProfilePictureInitials
{
    private $width = 600;
    private $height = 600;
    private $background_color = [0, 0, 0];
    private $text_color = [255, 255, 255];
    private $text = "ES";
    private $font_size = 140;
    private $font = DIRNAME . "../../static/fonts/proximanova/ProximaNova-Black.ttf";

    public function output()
    {
        try {

        } catch (Exception $exception) {
            logger($exception);
        }
    }

}
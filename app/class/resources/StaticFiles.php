<?php

class StaticFiles
{


    /**
     * @param $files
     * @return StaticFilesCSS
     */
    public function css($files, $is_theme = false): StaticFilesCSS
    {
        if (is_array($files) && count(array ($files)) > 0) return new StaticFilesCSS($files, $is_theme);
    }

    public function js($files, $is_theme = false): StaticFilesJS
    {
        if (is_array($files) && count(array ($files)) > 0) return new StaticFilesJS($files, $is_theme);
    }

    public function img($file, $is_theme = false): StaticFilesImage
    {
        return new StaticFilesImage($file, $is_theme);
    }

}
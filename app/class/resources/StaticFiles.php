<?php

class StaticFiles
{


    /**
     * @param $files
     * @return StaticFilesCSS
     */
    public function css($files): StaticFilesCSS
    {
        if (is_array($files) && count(array ($files)) > 0) return new StaticFilesCSS($files);
    }

    public function js($files): StaticFilesJS
    {
        if (is_array($files) && count(array ($files)) > 0) return new StaticFilesJS($files);
    }

    public function img($file): StaticFilesImage
    {
        return new StaticFilesImage($file);
    }

}
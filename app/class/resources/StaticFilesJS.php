<?php

class StaticFilesJS
{

    private $_path = DIRNAME . "../../static/js/";
    private $_filename = null;
    private $_js = null;
    private $_files;

    public function __construct($files)
    {
        if ((array)count($files) > 0) {
            $this->_files = $files;
        }
    }

    public function minify(): string
    {
        $output = "";
        $env = new Env();
        if ((array)count($this->_files) > 0) {
            foreach ($this->_files as $file) {
                $path_parts = pathinfo($file);

                $dirname = $path_parts['dirname'];
                if (not_empty($dirname) && $dirname !== ".") {
                    $filename = $dirname . "/" . $path_parts['basename'];
                } else {
                    $filename = $path_parts['basename'];
                }
                $path = $env->get("APP_URL") . "/js/" . $filename;
                $output .= "<script src=\"$path\"></script>";
            }
        }
        return $output;
    }


    public function embed(): string
    {
        $env = new Env();
        $text = new Text();
        $output = "";
        foreach ($this->_files as $file) {
            $path = $env->get("APP_STATIC") . "/js/" . $file . "?v=" . $text->random(32)->output();
            $output .= "<script src='" . $path . "'></script>" . PHP_EOL;
        }
        return $output;
    }

    public function error($error)
    {
        echo $error;
        return $this;
    }

}
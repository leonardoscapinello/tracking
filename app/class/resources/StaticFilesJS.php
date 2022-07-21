<?php

class StaticFilesJS
{

    private $_path;
    private $_filename = null;
    private $_js = null;
    private $_files;
    private $_is_theme;

    public function __construct($files, $is_theme = false)
    {
        $this->_is_theme = $is_theme;
        if ((array)count($files) > 0) {
            if ($is_theme) $this->_path = DIRNAME . "../../theme/";
            else $this->_path = DIRNAME . "../../static/js/";
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
                if ($this->_is_theme) $path = $env->get("APP_URL") . "/jst/" . $filename;
                $output .= "<script src=\"$path\"></script>";
            }
        }
        return $output;
    }


    public function embed(): ?string
    {
        $env = new Env();
        $output = null;
        foreach ($this->_files as $file) {
            $path = $this->_path . $file;
            if (file_exists($path)) {
                if ($this->_is_theme) $file_url = $env->get("APP_URL") . "/theme/" . $file . "?v=" . date("YmdHis");
                else  $file_url = $env->get("APP_STATIC") . "/js/" . $file . "?v=" . date("YmdHis");
                $output .= "<script src=\"" . $file_url . "\"></script>";
            }
        }
        return $output;
    }

    public function error($error): StaticFilesJS
    {
        echo $error;
        return $this;
    }

}
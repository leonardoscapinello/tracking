<?php

class StaticFilesCSS
{

    private $_path = DIRNAME . "../../static/css/";
    private $_filename = null;
    private $_css = null;
    private $_id;
    private $is_minified = false;
    private $_search;
    private $_replace;

    public function __construct($files, $is_theme = false)
    {
        if ((array)count($files) > 0) {
            foreach ($files as $file) {
                if ($is_theme) $this->_path = DIRNAME . "../../theme/";
                else $this->_path = DIRNAME . "../../static/css/";
                $path_parts = pathinfo($file);
                $file = $this->_path . ($is_theme ? $path_parts['dirname'] . "/" : "") . $path_parts['basename'];
                if (file_exists($file)) {
                    $this->_css .= file_get_contents($file);
                }
            }
        }
        $this->_path = DIRNAME . "../../static/css/";
    }

    public function replace($search, $replace): StaticFilesCSS
    {
        $this->_search = $search;
        $this->_replace = $replace;
        if (not_empty_bool($this->_search) && not_empty_bool($this->_replace)) {
            $this->_css = str_replace($this->_search, $this->_replace, $this->_css);
        }
        return $this;
    }

    public function isTheme(): StaticFilesCSS
    {
        $this->_is_theme = true;
        return $this;
    }

    public function minify(): StaticFilesCSS
    {
        $this->is_minified = true;
        $buffer = $this->_css;
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        $buffer = str_replace(': ', ':', $buffer);
        $buffer = str_replace(' {', '{', $buffer);
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
        $this->_css = $buffer;
        return $this;
    }

    public function id($id): StaticFilesCSS
    {
        $this->_id = $id;
        return $this;
    }

    public function html(): StaticFilesCSS
    {
        $css = $this->_css;
        $this->_css = "<style type=\"text/css\">";
        $this->_css .= $css;
        $this->_css .= "</style>" . PHP_EOL;
        return $this;
    }

    public function inline()
    {
        return print_r($this->_css);
    }

    public function output($filename)
    {
        $env = new Env();
        $this->_filename = $filename;
        $output_filename = $filename;
        if ($this->is_minified) {
            $output_filename = str_replace(".css", "", $output_filename);
            $output_filename = str_replace(".min", "", $output_filename);
            $output_filename = $output_filename . ".min.css";
        }
        $end_file = $this->_path . $output_filename;

        if ($env->get("APP_ENV") !== "local") {
            return $this;
        }
        // fix html
        $this->_css = str_replace("<style>", "", $this->_css);
        $this->_css = str_replace("</style>", "", $this->_css);
        $file = fopen($end_file, "w") or die("Unable to open file!");
        fwrite($file, $this->_css);
        fclose($file);
        return $this;
    }

    public function embed(): string
    {
        $env = new Env();
        $filename = $this->_filename;
        if ($this->is_minified) {
            $filename = str_replace(".min.css", ".css", $filename);
            $filename = str_replace(".css", ".min.css", $filename);
        }
        $path = $env->get("APP_STATIC") . "/css/" . $filename . "?v=" . date("YmdHis");
        $id = "";
        if (not_empty($this->_id)) {
            $id = "id=\"$this->_id\"";
        }
        $this->clean();
        return "<link href=\"$path\" type=\"text/css\" rel=\"stylesheet\" $id>" . PHP_EOL;
    }

    public function error($error): StaticFilesCSS
    {
        echo $error;
        return $this;
    }

    private function clean()
    {
        $this->_path = DIRNAME . "../../static/css/";
        $this->_filename = null;
        $this->_css = null;
        $this->_id = null;
        $this->is_minified = false;
        $this->_search = null;;
        $this->_replace = null;;
    }

}
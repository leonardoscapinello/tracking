<?php

class Translate
{

    private $language = "pt-br";
    private $path;
    private $lines = [];
    private $dictionary = [];

    public function __construct()
    {

        try {
            $this->path = DIRNAME . "../../app/translate/" . $this->language . ".lang";
            $this->read()->dictionary();
        } catch (Exception $exception) {
            logger($exception);
        }


    }

    private function read(): Translate
    {
        $handle = fopen($this->path, "r");
        while (!feof($handle)) {
            $this->lines[] = trim(fgets($handle));
        }
        fclose($handle);
        return $this;
    }

    private function dictionary()
    {
        try {
            foreach ($this->lines as $key => $value) {
                if (substr($value, 0, 1) !== "#" && substr($value, 0, 1) !== "") {
                    $line = explode("=", $value);
                    if (count($line) > 0 && array_key_exists(0, $line) && array_key_exists(1, $line)) {
                        $this->dictionary[strtolower($line[0])] = $line[1];
                    }
                }
            }
        } catch (Exception $exception) {
            logger($exception);
        }
    }

    public function write($string)
    {
        try {
            $string = PHP_EOL . $string . "=" . $string;
            file_put_contents($this->path, $string, FILE_APPEND | LOCK_EX);
        } catch (Exception $exception) {
            error_log($exception);
        }
    }


    public function __($string, ...$replaces): ?string
    {
        if (array_key_exists(strtolower($string), $this->dictionary)) {
            return vsprintf($this->dictionary[strtolower($string)], ...$replaces);
        } else {
            $this->write($string);
        }
        return vsprintf($string, ...$replaces);
    }
}



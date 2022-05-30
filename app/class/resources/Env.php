<?php

class Env
{

    public function get($index)
    {
        $environmentPath  = __DIR__ . "/../../../";
        $dotenv = Dotenv\Dotenv::createImmutable($environmentPath);
        $dotenv->load();

        if (isset($_ENV[$index]) && not_empty($_ENV[$index])) {
            return $_ENV[$index];
        }
        return null;
    }


}
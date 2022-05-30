<?php

class NovaExperienciaLoader
{
    public function __construct()
    {
        spl_autoload_extensions('.php');
        spl_autoload_register(array($this, 'load_resources'));
        spl_autoload_register(array($this, 'load_project'));
    }

    private function load_resources($className)
    {
        $extension = spl_autoload_extensions();
        $require = __DIR__ . '/resources/' . $className . $extension;
        if (file_exists($require)) require_once($require);
    }

    private function load_project($className)
    {
        $extension = spl_autoload_extensions();
        $require = __DIR__ . '/project/' . $className . $extension;
        if (file_exists($require)) require_once($require);
    }

}


new NovaExperienciaLoader();

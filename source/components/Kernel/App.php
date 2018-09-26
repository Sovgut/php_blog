<?php

namespace components\Kernel;

use controllers\MainController;
use components\Essentials\Environment;

/**
 * @author Sovgut Sergey
 */
class App
{

    /**
     * Application root
     */
    private $root;

    /**
     * Assoc configuration
     */
    private $config;

    public function __construct()
    {
        $this->root = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
        $this->FetchConfiguration();
    }

    /**
     * Load configuration file
     */
    private function FetchConfiguration()
    {
        $this->config = json_decode(file_get_contents($this->root.'config/app.json'), true);

        foreach ($this->config as $key => $value) {
            $this->{$key} = $value;
            if (gettype($key) === 'array') {
                $this->{$key} = new class($value) {
                    function __construct($data) {
                        foreach ($data as $key => $value) {
                            $this->{$key} = $value;
                        }
                    }
                };
            }
        }
    }

    /**
     * Application root
     *
     * @param  string $path
     * @return string
     */
    public function RootDirectory(string $path = '') : string
    {
        return $this->root.$path;
    }

    public function Run() : void {
        global $app; $app = $this;

        Environment::StartLogging();
        include_once Environment::Dir()->templates.$this->defaultTemplate.'.php';
        Environment::EndLogging($this->debug);
    }
}

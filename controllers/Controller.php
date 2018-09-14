<?php

namespace controllers;

use components\Kernel\App;
use components\Essentials\Environment;

/**
 * @author Sovgut Sergey
 */
class Controller
{
    protected $app;

    public function __construct()
    {
        $this->app = new App();
    }

    protected function Render(string $route, array $variables = []) : void
    {
        $this->ExtractVariables($variables);
        include_once Environment::Dir()->pages.$route.'.php';
        return;
    }

    private function ExtractVariables(array $variables) : void
    {
        foreach ($variables as $key => $value) {
            $this->{$key} = $value;
        }
    }
}

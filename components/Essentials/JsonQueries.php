<?php

namespace components\Essentials;

use components\Kernel\App;

/**
 * Fetch prepared queries from json config
 * 
 * @author Sovgut Sergey
 */
class JsonQueries {

    static function Queries() : object {
        $root   = (new App())->RootDirectory();
        $config = json_decode(file_get_contents($root.'config/jsonQueries.json'), true);

        return new class($config) {
            function __construct(array $config) {
                foreach ($config as $key => $value) {
                    $this->{$key} = $value;
                }
            }
        };
    }
}
<?php

namespace components\Essentials;

use components\Kernel\App;
use controllers\MainController;

/**
 * Fetch application path's from json config
 * 
 * @author Sovgut Sergey
 */
class Environment {
    
    /**
     * Read configuration json file and return object with routes
     */
    static function Dir() : object {
        $config = json_decode(file_get_contents((new App())->RootDirectory().'config/appRoutes.json'), true);

        return new class($config) {
            function __construct(array $config) {
                $root = (new App())->RootDirectory();

                foreach ($config as $key => $value) {
                    $this->{$key} = $root.$value.DIRECTORY_SEPARATOR;
                }
            }
        };
    }

    /**
     * Read url routes end calls controllers
     */
    static function Route() : void {
        if (isset(self::Request()->page)) {
            $pages = scandir(self::Dir()->pages);

            foreach ($pages as $page) {
                if (!is_dir($page)) {
                    $statement = str_replace('.php', '', $page);

                    if (strtolower(self::Request()->page) === $statement) {
                        $statement .= 'Action';
                        (new MainController())->$statement();
                        return;
                    }
                }
            }

            (new MainController())->errorAction();
            return;
        } else {
            (new MainController())->indexAction();
            return;
        }
    }

    /**
     * Fetch all request to object
     * Supports: GET, POST
     *
     * @param string $param
     */
    static function Request()
    {
        $request = [];
        self::FetchRequest($_GET,  $request);
        self::FetchRequest($_POST, $request);

        return new class($request) {
            function __construct(array $request) {
                foreach ($request as $node) {
                    foreach ($node as $key => $value) {
                        $this->{$key} = $value;
                    }
                }
            }
        };
    }

    static function StartLogging() {
        $GLOBALS['log'] = [];
    }

    static function AddLog($data) {
        $GLOBALS['log'][] = $data;
    }

    /**
     * Show log on application
     */
    static function EndLogging(bool $debug) {
        if (!empty($GLOBALS['log']) && $debug) {
            echo '<div class="server_log"><div class="close" onClick="document.querySelector(\'.server_log\').remove()">X</div><pre>';
            var_dump($GLOBALS['log']);
            echo '</pre></div>';
        }
    }

    /**
     * Fetch request if available
     *
     * @param array $request
     * @param array &$linkedData
     */
    static private function FetchRequest(array $request, array &$linkedData) : void
    {
        if (!empty($request)) {
            foreach ($request as $key => $value) {
                $linkedData[] = [$key => $value];
            }
        }
    }
}
<?php

namespace components\Essentials;

use components\Kernel\App;
use controllers\MainController;

/**
 * Executes parsing of json, queries, url and returns 
 * as an object with the properties whose name is the 
 * name of the passed parameter, and the value.
 * 
 * @author Sovgut Sergey
 * @license MIT
 * @version 1.0.0
 */
class Environment 
{
    /**
     * Recursively fetches raw data for getting clean object with data
     * 
     * @since 0.8.3
     * @author Sovgut Sergey
     * @param array $array Raw data like json, assoc array, sql data
     * @return object Like array, but this is object :)
     */
    static public function BuildObjectRecursive ( array $array ) : object 
    {
        return new class($array) 
        {
            function __construct ( array $array ) 
            {
                foreach ( $array as $key => $value ) 
                {
                    if ( gettype( $value ) === 'array' )
                        $this->{$key} = Environment::BuildObjectRecursive( $value );
                    else {
                        $this->{$key} = $value;
                    }
                }
            }
        };
    }

    /**
     * Read configuration json file and return object with routes
     */
    static function Dir() : object {
        $root   = (new App())->RootDirectory();
        $config = json_decode(file_get_contents($root.'config/fileSystem.json'), true);

        foreach ($config as $key => &$value) {
            if (gettype($value) === 'array')
                foreach ($value as $keySec => &$valueSec) 
                    $valueSec = $root.$valueSec.DIRECTORY_SEPARATOR;
            else 
                $value = $root.$value.DIRECTORY_SEPARATOR;
        }

        return self::BuildObjectRecursive($config);
    }

    static private function FetchRoutes() : object {
        $root   = (new App())->RootDirectory();
        $config = json_decode(file_get_contents($root.'config/routes.json'), true);

        return self::BuildObjectRecursive($config);
    }

    static public function FetchDatabase() : object {
        $root   = (new App())->RootDirectory();
        $config = json_decode(file_get_contents($root.'config/database.json'), true);

        return self::BuildObjectRecursive($config);
    }

    /**
     * Read url routes end calls controllers
     */
    static function Route() : void {
        $request    = self::Request();
        $controller = new MainController();

        if (isset($request->page)) {
            $routes = self::FetchRoutes();

            foreach ($routes as $route) {
                $statement = str_replace('Action', '', $route);

                if (strtolower($request->page) === $statement) {
                    $controller->$route();
                    return;
                }
            }

            $controller->errorAction();
            return;
        } else {
            $controller->indexAction();
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
        self::FetchRequest($_FILES, $request);

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

    static function Redirect(string $url, array $variables = []) : void {
        
        $buffer = '';
        foreach ($variables as $key => $value) {
            $buffer .= '&'.$key.'='.$value;
        }

        header('Location: /?page='.$url.$buffer);
    }

    static function CreateRoute(string $url, array $variables = []) : string {

        $buffer = '';
        foreach ($variables as $key => $value) {
            $buffer .= '&'.$key.'='.$value;
        }

        return '/?page='.$url.$buffer;
    }

    static function HtmlRoute(string $url, array $variables = []) : void {

        $buffer = '';
        foreach ($variables as $key => $value) {
            $buffer .= '&'.$key.'='.$value;
        }

        echo '/?page='.$url.$buffer;
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
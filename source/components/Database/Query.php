<?php

namespace components\Database;

use components\Essentials\Environment;

class Query {

    static private function Connect() : object {
        $db = Environment::FetchDatabase();
        $instance = new \PDO('mysql:host='.$db->host.';dbname='.$db->database, $db->login, $db->pass);
        $instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $instance;
    }

    static public function BuildObjectRecursive(array $array) : object {
        return new class($array) {
            function __construct(array $array) {
                foreach ($array as $key => $value) {
                    if (gettype($value) === 'array')
                        $this->{$key} = Environment::BuildObjectRecursive($value);
                    else {
                        $this->{$key} = $value;
                    }
                }
            }
        };
    }

    static public function Fetch(string $query, array $binds) : object {
        $instance = self::Connect();
        $stmt = $instance->prepare($query);

        Environment::AddLog($query);
        Environment::AddLog($binds);

        foreach ($binds as $key => $value) {
            $type = 0;
            
            switch(gettype($value)) {
                case 'string' : $type = \PDO::PARAM_STR; break;
                case 'integer': $type = \PDO::PARAM_INT; break;
            }

            $stmt->bindValue(':'.$key, $value, $type);
        }

        $stmt->execute();

        return self::BuildObjectRecursive($stmt->fetchAll());
    }

    static public function Execute(string $query, array $binds) : void {

    }
}
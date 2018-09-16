<?php

namespace components\Database;

use components\Essentials\Environment;

/**
 * @author Sovgut Sergey
 */
class SQLite
{
    static private function Connect() : object {
        $instance = new \PDO('sqlite:'.Environment::Dir()->databases.'SQLite3.sqlite');
        $instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $instance->beginTransaction();
        return $instance;
    }

    static private function Build($result) : array {
        $objects = [];
        foreach ($result as $row) {
            $objects[] = new class($row) {
                function __construct($row) {
                    foreach ($row as $key => $value) {
                        $variable = SQLite::Clean($key);
                        $this->{$variable} = $value;
                    }
                }
            };
        }
        return $objects;
    }

    static function Execute(string $query, array $binds = []) : void {
        $stmt = self::Connect()->prepare($query);
        $stmt->execute($binds);
    }

    static function Fetch(string $query, array $binds = []) : array
    {
        $stmt = self::Connect()->prepare($query);
        $stmt->execute($binds);
        $result = $stmt->fetchAll();

        return self::Build($result);
    }

    static function Clean(string $variable) : string {
        $pattern = '([*,(,)])';
        return preg_replace($pattern, '', $variable);
    }
}
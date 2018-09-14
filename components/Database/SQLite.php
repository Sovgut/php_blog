<?php

namespace components\Database;

use components\Essentials\Environment;

/**
 * @author Sovgut Sergey
 */
class SQLite
{
    static private function Connect() {
        $instance = new \PDO('sqlite:'.Environment::Dir()->databases.'blog.sqlite');
        $instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $instance->beginTransaction();
        return $instance;
    }

    static private function BuildArray($result) {
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

    static private function BuildOne($result) {
        return new class($result[0]) {
            function __construct($data) {
                foreach ($data as $key => $value) {
                    $variable = SQLite::Clean($key);
                    $this->{$variable} = $value;
                }
            }
        };
    }

    static function Fetch(string $query, array $binds = [])
    {
        $stmt = self::Connect()->prepare($query);
        $stmt->execute($binds);
        $result = $stmt->fetchAll();

        if (count($result) > 1) {
            return self::BuildArray($result);
        } else {
            return self::BuildOne($result);
        }
    }

    static function Clean(string $variable) : string {
        $pattern = '([*,(,)])';
        return preg_replace($pattern, '', $variable);
    }
}
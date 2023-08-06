<?php

class Db {
    private static $conn;

    public static function getConnection() {
        $config = parse_ini_file(__DIR__ . "/../config.ini");

        if (! $config) {
            throw new Exception('No configuration file defined.');
        }
        
        if (self::$conn === null) {
            self::$conn = new PDO('mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'] . ';port=' . $config['db']['port'], $config['db']['user'], $config['db']['password']);
        }

        return self::$conn;
    }
}
<?php
    class Database {
        private static ?PDO $connection = null;

        private const HOST = "127.0.0.1";
        private const DB_NAME = "MyRecordingStudio";
        private const USER = "root";
        private const PASS = "";

        public static function getConnection(): PDO {
            if (self::$connection === null) {
                try {
                    $dsn = "mysql:host=" .self::HOST . ";dbname=" .self::DB_NAME . ";charset=utf8mb4";
                    self::$connection = new PDO($dsn, self::USER, self::PASS,[
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]) 
            } catch (PDOException $e) {
                die ("Database connection failed: " . htmlspecialchars($e->getMessage()));
            }
        }
        return self::$connection;
    }
}
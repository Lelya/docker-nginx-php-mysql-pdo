<?php

namespace App\Acme;


final class Init
{
        private $host = "localhost";
        private $user = "dev";
        private $pass = "dev";
        private $database = "test";
        private $dbcharset = "utf-8";
        public $dbh;

        public function create()
        {
                $dsn = "mysql:host=$this->host;dbname=$this->database;charset=$this->dbcharset";
                $opt = [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                try {
                        $pdo = new PDO($dsn, $this->user, $this->pass, $opt);
                        return 'MySQL PDO';
                }
                catch (PDOException $e) {
                        echo '<br /><p align="center"><strong>Connect error:</strong> '.$e->getMessage().'</p>';
                        exit();
//                        die('Подключение не удалось: ' . $e->getMessage());
                }
        }

//        private function fill()
//        {
//                return 'Nginx PHP MySQL';
//        }
//
//        public function get()
//        {
//                return 'Nginx PHP MySQL';
//        }
}

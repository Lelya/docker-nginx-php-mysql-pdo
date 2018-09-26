<?php
/**
 * Файл класса Init
 *
 * @author Lelya <kovyrshina.olga@yandex.ru>
 * @version 1.0
 */

namespace App\Acme;
use PDO,PDOException;

/**
 * Класс Init
 * от которого нельзя сделать наследника
 */
final class Init
{
        /**
         * @var string адрес сервера mysql
         */
        private $host = "mysql";
        /**
         * @var string имя пользователя
         */
        private $user = "dev";
        /**
         * @var string пароль для пользователя
         */
        private $pass = "dev";
        /**
         * @var string имя базы данных
         */
        private $database = "test";
        /**
         * @var \PDO соединение с базой
         */
        private $connection;


        function __construct() {
                try {
                        $this->connection = new PDO("mysql:host={$this->host};dbname={$this->database};", $this->user, $this->pass);
                        $this->connection->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                }
                catch (PDOException $e) {
                        die('Подключение не удалось: ' . $e->getMessage());
                }
                $this->create();
                $this->fill();
        }

        /**
         * Создает таблицу test, содержащую 5 полей
         *   - id целое, автоинкрементарное;
         *   - script_name строковое, длиной 25 символов;
         *   - start_time timestamp с автозаполнением;
         *   - sort_index целое (значения не превышают 3-х разрядов);
         *   - result один вариант из 'normal', 'illegal', 'failed', 'success';
         */
        private function create()
        {
                //запрос на создание таблицы
                $query = $this->connection->prepare("CREATE TABLE IF NOT EXISTS `test` (
                        `ID` INT NOT NULL AUTO_INCREMENT,
                        `script_name` varchar(25),
                        `start_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        `sort_index` INT,
                        `result` ENUM('normal', 'illegal', 'failed', 'success'),
                        PRIMARY KEY(`ID`))");
                $query->execute();

        }

        /**
         * Заполняет таблицу test случайными данными;
         */
        private function fill()
        {
                $array_result = array("normal", "success", "illegal", "failed");

                for ($i = 1; $i <= 50; $i++) {

                        $rand_result = array_rand($array_result, 2);
                        $rand_scriptname = substr(md5(microtime()),rand(0,26),10);
                        $rand_sortindex = rand(0, 999);

                        $statement = $this->connection->prepare('INSERT INTO test (script_name, sort_index, result)
                              VALUES (:script_name, :sort_index, :result)');

                        $statement->execute([
                                'script_name' => $rand_scriptname,
                                'sort_index' => $rand_sortindex,
                                'result' => $array_result[$rand_result[0]]
                        ]);
                }
        }

        /**
         * Выбирает из таблицы test, данные по критерию: result среди значений 'normal' и 'success';
         * @return array массив значений
         */
        public function get()
        {
                $statement = $this->connection->prepare("SELECT * FROM test WHERE result IN ('normal','success')");
                $statement->execute();

                $data = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $data;
        }

}

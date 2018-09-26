<?php
/**
 * Подключаем файл
 */
include '../app/vendor/autoload.php';
/**
 * Инициализация объекта класса Init
 */
$init = new App\Acme\Init();

?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Test</title>
    </head>
    <body>
        <h4>Выборка из таблицы test данных по критерию: result среди значений 'normal' и'success';</h4>
        <?php

        $result = $init->get();

        /**
         * Вывод на страничку в виде таблицы
         */
        echo "<table border=1>
                <tr><th>id</th><th>script_name</th><th>sort_index</th><th>start_time</th><th>result</th></tr>";

                //вывод построчно
                for($i = 0; $i < count($result); $i++){
                        echo
                        "<tr>
                                <td>".$result[$i]['ID']."</td>
                                <td>".$result[$i]['script_name']."</td>
                                <td>".$result[$i]['sort_index']."</td>
                                <td>".$result[$i]['start_time']."</td>
                                <td>".$result[$i]['result']."</td>
                        </tr>";
                }
                        echo "</table>";

        ?>
    </body>
</html>

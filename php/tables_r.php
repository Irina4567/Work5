<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include '../config.php';
include '../bd.php';

if (array_key_exists("r", $_GET)) $r = $_GET["r"]; else $r = 1;
if (array_key_exists("v", $_GET)) $v = $_GET["v"]; else $v = 0;

try{
    $sql = "";

    if ($r == 1) {
      $sql="SELECT * FROM `users`";
    }

    if ($r == 2) {
      $sql="SELECT * FROM `orders`";
    }

    if ($r == 3) {
      $sql="SELECT `email`, COUNT(`email`) AS count FROM `users` GROUP BY `email` HAVING COUNT(`email`) > 1";
    }

    if ($r == 4) {
      $sql="SELECT
      `users`.`login` AS login
    FROM `users` WHERE `users`.`id` NOT IN(SELECT `orders`.`user_id` FROM `orders`)";
    }

    if ($r == 5) {
      $sql="SELECT `users`.`login` AS login, COUNT(`users`.`id`) AS count_orders FROM `orders` LEFT JOIN
      `users` ON `orders`.`user_id` = `users`.`id` GROUP BY `users`.`id` HAVING COUNT(`users`.`id`) > 2";
    }

    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response_rows[] = $rows;
    $response_rows[] = $sql;


		echo json_encode($response_rows);
}catch (PDOException $err) {
	echo $err->getMessage();
}
?>
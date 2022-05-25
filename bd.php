<?php
try {
    $db = new PDO('mysql:host='.DBSERVER.';dbname='.DATABASE, DBUSER, DBPASSWORD, array(
    	PDO::ATTR_PERSISTENT => true
    	));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    $e->getMessage() . "<br/>";
    die();
}
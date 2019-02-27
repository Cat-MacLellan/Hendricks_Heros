<?php

$username  = 'root';
$password  = '';
$result = 0;
try {
    $dbconn = new PDO('mysql:host=localhost;dbname=Hendricks_Heroes_Music', $username, $password);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

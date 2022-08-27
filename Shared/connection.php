<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_j_store";

date_default_timezone_set("Etc/GMT-5");

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $conn = null;
    exit;
}
?>
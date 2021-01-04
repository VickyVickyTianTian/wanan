<?php

require_once("dotenv.php");

$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv("DB_PASSWORd");
$dbname = getenv('DB_NAME');

$con = new mysqli($servername, $username, $password, $dbname);


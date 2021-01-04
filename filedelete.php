<?php

require_once("dotenv.php");
require_once("connection.php");

header('Content-Type: application/json'); // set json response headers

$preview = $config = $errors = [];

$fileID = $_POST['key'];

exit(json_encode($_POST));

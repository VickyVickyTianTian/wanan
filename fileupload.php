<?php

require_once("dotenv.php");

header('Content-Type: application/json'); // set json response headers

$outData = upload(); // a function to upload the bootstrap-fileinput files

exit(json_encode($outData)); // return json data

function upload()
{
    $preview = $config = $errors = [];

    $input = 'file-upload';

    if (empty($_FILES[$input])) {
        return [];
    }

    $baseURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . getenv('BASE_DOMAIN');

    $total = count($_FILES[$input]['name']);
    $path = __DIR__ . '/storage/';
    for ($i = 0; $i < $total; $i++) {
        $tmpFilePath = $_FILES[$input]['tmp_name'][$i]; // the temp file path
        $fileName = $_FILES[$input]['name'][$i]; // the file name
        $fileSize = $_FILES[$input]['size'][$i]; // the file size

        if ($tmpFilePath) {
            $newFilePath = $path . $fileName;
            $newFileUrl = $baseURL . '/storage/' . $fileName;

            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $fileId = $fileName.$i; // some unique key to identify the file
                $preview[] = $newFileUrl;
                $config[] = [
                    'key' => $fileId,
                    'caption' => $fileName,
                    'size' => $fileSize,
                    'downloadUrl' => $newFileUrl, // the url to download the file
                    'url' => $baseURL . '/filedelete.php', // server api to delete the file based on key
                ];
            } else {
                $errors[] = $fileName;
            }
        } else {
            $errors[] = $fileName;
        }
    }
    $out = ['initialPreview' => $preview, 'initialPreviewConfig' => $config, 'initialPreviewAsData' => true];
    if (!empty($errors)) {
        $img = count($errors) === 1 ? 'file "'.$errors[0].'" ' : 'files: "'.implode('", "', $errors).'" ';
        $out['error'] = 'Oh snap! We could not upload the '.$img.'now. Please try again later.';
    }
    return $out;
}

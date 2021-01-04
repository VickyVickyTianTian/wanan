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

    // make sure the directory `storage/[YYYY-mm-dd]` exists
    $path = __DIR__ . '/storage/' . date('Y-m-d');
    if (!mkdir($path) && !is_dir($path)) {
        return ['error' => 'Oh snap! We could not upload the file now. Please try again later.'];
    }

    for ($i = 0; $i < $total; $i++) {
        $tmpFilePath = $_FILES[$input]['tmp_name'][$i]; // the temp file path
        $oldFileName = $_FILES[$input]['name'][$i]; // the file name
        $fileSize = $_FILES[$input]['size'][$i]; // the file size

        // only jpg, jpeg, png, pdf files are allowed to upload
        $ext = pathinfo($oldFileName, PATHINFO_EXTENSION);
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'pdf'])) {
            return ['error' => 'Please upload .jpg, .jpeg, .png, .pdf files.'];
        }

        // generate new fileName
        $fileName = md5($oldFileName . microtime(true)) . '.' . $ext;

        if ($tmpFilePath) {
            $newFilePath = $path . '/' . $fileName;
            $newFileUrl = $baseURL . '/storage/' . $fileName;

            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $fileId = $fileName.$i; // some unique key to identify the file
                $preview[] = $newFileUrl;
                $config[] = [
                    'key' => $fileId,
                    'caption' => $oldFileName,
                    'size' => $fileSize,
                    'downloadUrl' => $newFileUrl, // the url to download the file
                    'url' => $baseURL . '/filedelete.php', // server api to delete the file based on key
                ];
            } else {
                $errors[] = $oldFileName;
            }
        } else {
            $errors[] = $oldFileName;
        }
    }
    $out = ['initialPreview' => $preview, 'initialPreviewConfig' => $config, 'initialPreviewAsData' => true];
    if (!empty($errors)) {
        $img = count($errors) === 1 ? 'file "'.$errors[0].'" ' : 'files: "'.implode('", "', $errors).'" ';
        $out['error'] = 'Oh snap! We could not upload the '.$img.'now. Please try again later.';
    }
    return $out;
}

<?php

require_once("dotenv.php");
require_once("connection.php");

header('Content-Type: application/json'); // set json response headers


$preview = $config = $errors = [];

if (isset($_FILES['passport-photo'])) {
    $input = 'passport-photo';
} elseif ($_FILES['IC-photo']) {
    $input = 'IC-photo';
} else {
    exit(json_encode([]));
}

$baseURL = getenv('BASE_DOMAIN');

$total = count($_FILES[$input]['name']);

// make sure the directory `storage/[YYYYmmdd]` exists

$path = 'storage/'.date('Ymd');

$fullPath = __DIR__ . '/' . $path;

if (!mkdir($fullPath) && !is_dir($fullPath)) {
    exit(json_encode(['error' => 'Oh snap! We could not upload the file now. Please try again later.']));
}

for ($i = 0; $i < $total; $i++) {
    $tmpFilePath = $_FILES[$input]['tmp_name'][$i]; // the temp file path
    $oldFileName = $_FILES[$input]['name'][$i]; // the file name
    $fileSize = $_FILES[$input]['size'][$i]; // the file size

    // only jpg, jpeg, png, pdf files are allowed to upload
    $ext = pathinfo($oldFileName, PATHINFO_EXTENSION);
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'pdf', 'gif'])) {
        exit(json_encode(['error' => 'Please upload .jpg, .jpeg, .png, .pdf, .gif files.']));
    }

    // generate new fileName
    $fileName = md5($oldFileName.microtime(true)).'.'.$ext;

    if ($tmpFilePath) {
        $newFilePath = $fullPath. '/' .$fileName;
        $newFileUrl = $baseURL. '/' . $path . '/' . $fileName;

        if (move_uploaded_file($tmpFilePath, $newFilePath)) {
            $sql = "INSERT INTO storage(path) VALUES('{$path}/{$fileName}')";
            $res = mysqli_query($con, $sql);
            if (!$res) {
                exit(json_encode(['error' => 'Oh snap! We could not upload the file now. Please try again later.']));
            }
            $fileId = $con->insert_id;
            $preview[] = $newFileUrl;
            $config[] = [
                'key' => $fileId,
                'caption' => $oldFileName,
                'size' => $fileSize,
                'downloadUrl' => $newFileUrl, // the url to download the file
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
exit(json_encode($out));

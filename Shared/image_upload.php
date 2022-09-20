<?php

function DeleteImage($fileName)
{
    if (file_exists($fileName)) {
        unlink($fileName);
        return true;
    } else {
        echo 'Could not delete';
        return false;
    }
}

function isFileAlreadyExist($fileName)
{
    if (file_exists($fileName)) {
        return true;
    } else {
        return false;
    }
}

function UploadImage($file, $mode = "admin")
{
    $uploadFileError = "";
    $uploadFileSuccess = "";
    $uploadedFile = "";

    if ($mode == "admin")
        $filePath = "../Uploads/";
    else
        $filePath = "Uploads/";

    $uploadFileError = "";
    if (isset($file) && $file['error'] == 0) {
        $allowedFormats = array(
            "JPG" => "image/jpg",
            "jpg" => "image/jpg",
            "JPEG" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "GIF" => "image/gif",
            "gif" => "image/gif",
            "PNG" => "image/png",
            "png" => "image/png"
        );

        $fileName = $file['name'];
        $fileType = $file['type'];
        $fileSize = $file['size'];
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        if (!array_key_exists($ext, $allowedFormats)) {
            return ['status' => 'error', 'msg' => "Please select a valid format (jpg, png, gif)"];
        }

        $maxSize = 5 * 1024 * 1024; 

        if ($fileSize > $maxSize) {
            return ['status' => 'error', 'msg' => "File size cannot exceed 5Mbs"];
        }

        if (in_array($fileType, $allowedFormats)) {
            $file_exists = isFileAlreadyExist($filePath . $fileName);
            if ($file_exists) {
                return ['status' => 'error', 'msg' => "Image with name \"" . $fileName . "\" already exists"];
            } else {
                move_uploaded_file($file['tmp_name'], $filePath . $fileName);
                return ['status' => 'success', 'msg' => "File uploaded successfully!", 'uploadedFile' => $filePath . $fileName];
            }
        }
    } else {
        return ['status' => 'error', 'msg' => "Please upload a file."];
    }
}
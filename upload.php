<?php

    $errorcode = 0;
    $message = '';

    if (isset($_POST['filename']))
    {
        // flag for upload
        $uploadOK = true;

        // check bytes size
        if ($_FILES['sheet']['size'] > 	26214400) // 25mb
        {
            $message .= 'File size too big';
            $uploadOK = false;
        }

        // check file extention
        $fileExt = strtolower(pathinfo($_FILES['sheet']['name'], PATHINFO_EXTENSION));
        if ($fileExt != 'xlsx')
        {
            $message .= 'File is not an excel sheet';
            $uploadOK = false;
        }

        // execute the upload
        if ($uploadOK)
        {
            $target_file = 'assets/' . $_POST['filename'] . '.xlsx';
            if (move_uploaded_file($_FILES["sheet"]["tmp_name"], $target_file)) 
            {
                $message .= "Success: ". htmlspecialchars( basename( $_FILES["sheet"]["name"])). " werd geupload.";
            } 
            else 
            {
                $message .= "Sorry, there was an error uploading your file.";
            }
        }

        $errorcode = !$uploadOK;
    }
    else
    {
        $message .= 'No name given... upload canceled';
    }

    echo json_encode(array('errorcode' => $errorcode, 'message' => $message));
?>
<?php
session_start();

header('Content-type:application/json;charset=utf-8');

try {
    if (
        !isset($_FILES['file']['error']) ||
        is_array($_FILES['file']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    switch ($_FILES['file']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    //$filepath = sprintf('files/%s_%s', uniqid(), $_FILES['file']['name']);

	$path_parts = pathinfo($_FILES["file"]["name"]);
	$extension = $path_parts['extension'];
	$filename=uniqid().".".$extension;
	$from=$_POST['from'];
	if ($from=="allegati_utili") {
		$sub="allegati/documenti_utili";
		@mkdir($sub);
	}
	
	$filepath = "$sub/".$filename;
    if (!move_uploaded_file(
        $_FILES['file']['tmp_name'],
        $filepath
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }


	
    // All good, send the response
    echo json_encode([
        'status' => 'ok',
        'path' => $filepath,
		'filename' =>$filename,
		'from' =>$from
	]);

} catch (RuntimeException $e) {
	// Something went wrong, send the err message as JSON
	http_response_code(400);

	echo json_encode([
		'status' => 'error',
		'message' => $e->getMessage()
	]);
}
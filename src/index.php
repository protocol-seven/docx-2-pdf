<?php
/**
 * docx-2-pdf service
 */

require_once 'functions.php';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_FILES['file'])) {
        displayErrorMessage();        
    }

    $uploadedFile = $_FILES['file'];

    if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
        displayErrorMessage();
    }

    $result = convertIncomingFile();

    // The above would have thrown an exception if something went wrong, so we can assume the
    // file is there and we can carry on.
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="converted.pdf"');
    readfile($result['output']);

    // Clean up immediately
    unlink($result['input']); // Clean up the input file
    unlink($result['output']); // Clean up the output file

    // And we're done! Don't send any more output.
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once 'home.html';
    exit;
}

// If the user gets here, they done messed up!
http_response_code(422);
echo 'Unprocessable Entity';
exit;
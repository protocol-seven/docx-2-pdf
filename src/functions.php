<?php

function displayErrorMessage() : void {
    http_response_code(400);
    header('Content-Type: text/plain');
    echo "Error: No file uploaded.\n\n";
    echo "Sample CURL command to use:\n";
    echo "curl -F 'file=@yourfile.docx' " . $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "\n";
    exit;
}

function getWorkingFilename(string $filename = "input.docx") : string {
    $folder = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'docx2pdf_workdir';
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }
    return $folder . DIRECTORY_SEPARATOR . $filename;
}

function getUUID() : string {
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // set version to 0100
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // set bits 6-7 to 10
    $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    $now = date("YmdHis");
    return $now . substr($uuid, strlen($now));
}

function convertIncomingFile(array $uploadedFile) : array {

    // We don't need the filename for anything - we'll go with a timestamped UUID instead
    $uuid = getUUID();

    // Move the file there - a later process will purge it.
    $incomingFilePath = getWorkingFilename($uuid . '.docx');
    move_uploaded_file($uploadedFile['tmp_name'], $incomingFilePath);

    // Create a temporary file for the conversion process
    $outputFolder = getWorkingFilename("");
    $outputFilename = $uuid . '.pdf';
    $outputFilePath = $outputFolder . $outputFilename;

    $cmd = sprintf(
        'libreoffice --headless --convert-to pdf --outdir %s %s',
        escapeshellarg($outputFolder),
        escapeshellarg($incomingFilePath)
    );

    try {

        exec($cmd, $output, $retval);

        // If it all worked, we should see a file here:
        if (file_exists($outputFilePath)) {
            return [
                'input' => $incomingFilePath,
                'output' => $outputFilePath
            ];
        } else {
            throw new RuntimeException("PDF conversion failed, no output file found.");
        }

    } catch (\Throwable $e) {
        // TODO: Might want other error reporting here
        throw $e;
    }

}
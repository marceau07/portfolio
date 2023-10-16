<?php 
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';

$dir = $_GET['dir'];
$exploded = explode('/', $dir);
$dirname = array_pop($exploded);
$zipname = str_replace(array("/", "#"), array("", ""), $dirname).".zip";

// Autoload the dependencies
require str_replace('public', '', $_SERVER["CONTEXT_DOCUMENT_ROOT"]) . 'includes/vendor/autoload.php';

// enable output of HTTP headers
$options = new ZipStream\Option\Archive();
$options->setSendHttpHeaders(true);

// create a new zipstream object
$zip = new ZipStream\ZipStream($zipname, $options);

$rootPath = realpath(LIEN_RACINE."/$dir");
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

$fileSize = 0;
foreach ($files as $name => $file) {
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);
        
        // Add current file to archive
        $zip->addFileFromPath($relativePath, $filePath);
        $fileSize += filesize($filePath);
    }
}

// add a file named 'some_image.jpg' from a local file 'path/to/image.jpg'
//$zip->addFileFromPath('some_image.jpg', 'path/to/image.jpg');
header("Content-Length: ".$fileSize);
// finish the zip stream
$zip->finish();
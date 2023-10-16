<?php
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';

$filename = $_GET['file'];

// required for IE, otherwise Content-disposition is ignored
if(ini_get('zlib.output_compression')) ini_set('zlib.output_compression', 'Off');

$file_extension = strtolower(substr(strrchr($filename,"."),1));

if($filename == "") {
  echo "<html><title>eLouai's Download Script</title><body>ERROR: download file NOT SPECIFIED. USE force-download.php?file=filepath</body></html>";
  exit;
} elseif (!file_exists(LIEN_RACINE."/$filename")) {
  echo "<html><title>eLouai's Download Script</title><body>ERROR: File not found. USE force-download.php?file=filepath</body></html>";
  exit;
}

switch($file_extension) {
    case "gz": $ctype = "application/x-gzip"; break;
    case "tgz": $ctype = "application/x-gzip"; break;
    case "zip": $ctype = "application/zip"; break;
    case "pdf": $ctype = "application/pdf"; break;
    case "png": $ctype = "image/png"; break;
    case "gif": $ctype = "image/gif"; break;
    case "txt": $ctype = "text/plain"; break;
    case "htm": $ctype = "text/html"; break;
    case "html": $ctype = "text/html"; break;
    case "exe": $ctype = "application/octet-stream"; break;
    case "doc": $ctype = "application/msword"; break;
    case "xls": $ctype = "application/vnd.ms-excel"; break;
    case "ppt": $ctype = "application/vnd.ms-powerpoint"; break;
    case "gif": $ctype = "image/gif"; break;
    case "jpeg":
    case "jpg": $ctype = "image/jpg"; break;
    default: $ctype = "application/octet-stream";
}
header("Pragma: public"); // required
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false); // required for certain browsers 
header("Content-Type: $ctype");
header("Content-Disposition: attachment; filename=\"".basename(LIEN_RACINE."/$filename")."\";" );
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize(LIEN_RACINE."/$filename"));
readfile(LIEN_RACINE."/$filename");
exit();
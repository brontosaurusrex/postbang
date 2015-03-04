<?php

$file = $_GET["file"];
$start = (isset($_GET["start"])) ? intval($_GET["start"]): 0;

header("Content-Type: video/x-flv");
header('Content-Length: ' . filesize($file));

if($start > 0) {
print("FLV");
print(pack('C',1));
print(pack('C',1));
print(pack('N',9));
print(pack('N',9));
}

$fh = fopen($file,'rb');
fseek($fh, $start);
while (!feof($fh)) { print(fread($fh, 16384)); }
fclose($fh);

?>


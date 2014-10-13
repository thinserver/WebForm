<?php

require_once('overlay.php');

$json = json_decode($_POST['json'], true);

$prefix = "/tmp/webform";
$pages  = "";
$output = $prefix.'-new.pdf';

// overlay all pages
for ($page=0; $page<sizeof($json); $page++)
{
    $original = $prefix.'-'.$page.'-original.pdf';
    $overlay  = $prefix.'-'.$page.'-overlay.pdf';
    $result   = $prefix.'-'.$page.'.pdf';
    exec('pdftk '.$prefix.'.pdf cat '.$page.' output '.$original);
    overlay($json[$page], $overlay);
    exec('pdftk '.$original.' stamp '.$overlay.' output '.$result);
    $pages .= " ".$result;
}

// concatenate all pages
exec('pdftk '.$pages.' cat output '.$output);

// return created PDF
header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
header("Cache-Control: public");
header("Content-Type: application/pdf");
header("Content-Transfer-Encoding: Binary");
header("Content-Length:".filesize($output));
header("Content-Disposition: attachment; filename=webform.pdf");
readfile($output);

?>
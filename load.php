<?php

if (isset($_FILES["file"])) {
    // settings
    $path = "/tmp/";
    $prefix = "webform";
    $tempfile = $path.$prefix.".pdf";
    $decimals = 3;

    // delete temp documents
    unlink($tempfile);
    for ($i=0; $i<20; $i++)
    {
        unlink($path.$prefix."-".str_pad($i,$decimals,"0",STR_PAD_LEFT).".jpg");
    }

    // make a copy, not to loose it after session
    copy($_FILES["file"]["tmp_name"], $tempfile);

    // create JPEGs
    exec("convert -density 120 \"".$tempfile."\" ".$path.$prefix."-%0".$decimals."d.jpg");

    $count = sizeof(glob($path.$prefix."-*.jpg"));
    header("Location: ./edit.php?prefix=".$prefix."&start=0&count=".$count."&decimals=".$decimals);

} else {
    // No file? Back to upload page.
    header('Location: ./index.html');
}

?>
<html

<head>
<script src="jquery.js"></script>
<style>
    body {
        margin: 50px;
    }
    img {
        cursor: text;
        border: 1px solid black;
        margin: 10px;
    }
    textarea {
        width: 40%;
        height: 30%;
    }
</style>
</head>

<body>
<?php

$prefix = $_GET['prefix'];
$count = $_GET['count'];
$start = $_GET['start'];
$decimals = $_GET['decimals'];

function embed_image($path)
{
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    return 'data:image/'.$type.';base64,'.base64_encode($data);
}

for ($i=$start; $i<$start+$count; $i++)
{
    echo "<h3>Page ".($i+1)." / ".$count.":</h3>";
    $file = "/tmp/".$prefix."-".str_pad($i,$decimals,"0",STR_PAD_LEFT).".jpg";
    echo '<img src="'.embed_image($file).'"/>';
    echo "<br/>";
}

?>
<form action="export.php" method="post" enctype="multipart/form-data">
    Text to be inserted:
    <br/>
    <textarea readonly="true" id="json"></textarea>
    <br/>
    <input type="submit" value="Insert now"/>
</form>
</body>

</html>
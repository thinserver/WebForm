<html

<head>
<script src="jquery.js"></script>
<style>
    body {
        margin: 50px;
    }
    div {
        border: 1px solid black;
        margin: 10px;
    }
    img {
        cursor: text;
    }
    input[type="text"] {
        resize: both;
    }
    textarea {
        width: 40%;
        height: 30%;
    }
</style>
<script>
    clicked = function(event)
    {
        console.log(event);
        var page = $(event.srcElement).parent();
        console.log(page);
        var x = event.offsetX + page[0].offsetLeft;
        var y = event.offsetY + page[0].offsetTop + 50; // body margin-top
        var element = $('<input type="text" value="123" style="z-index:20;position:absolute;left:'+x+'px;top:'+y+'px;" id="'+x+y+'"/>');
        console.log(element);
        page.append(element);
        element.focus();
        updateJSON();
        element.keyup( function(event)
                        {
                            if (event.keyCode == 27)
                            {
                                console.log('ESC');
                                $(this).remove();
                            }
                            updateJSON();
                        }
                        );
    }
    
    updateJSON = function()
    {
        var json = [];
        pages = $('div.page');
        for (var i=0; i<pages.length; i++)
        {
            var currentPage = [];
            var texts = $('.page#page'+i+' input[type="text"]');
            for (var j=0; j<texts.length; j++)
            {
                var text = $('#'+texts[j].id);
                console.log(text);
                var x = text.css('left').replace('px','')-$('#page'+i)[0].offsetLeft;
                var y = text.css('top').replace('px','')-$('#page'+i)[0].offsetTop-50;
                t = {"x":x,"y":y,"text":text.val()};
                currentPage.push(t);
            }
            json.push(currentPage);
        }
        console.log(json);
        $('#json').html( JSON.stringify(json,null,"    ") );
    }
</script>
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
    echo '<div class="page" id="page'.$i.'">';
    echo "<h3>Page ".($i+1)." / ".$count.":</h3>";
    $file = "/tmp/".$prefix."-".str_pad($i,$decimals,"0",STR_PAD_LEFT).".jpg";
    echo '<img src="'.embed_image($file).'" onclick="clicked(event);"/>';
    echo "</div>";
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
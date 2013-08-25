<!DOCTYPE html>
<html>
<head>
    <?php

    $temp = file_get_contents(__DIR__.'/css/bootstrap.css');
    $match = array();
    preg_match_all('/\.(glyphicon-[^:]+):before/', $temp, $match);
    $glyphicons = $match[1];

    $temp = file_get_contents(__DIR__.'/css/font-awesome.css');
    $match = array();
    preg_match_all('/\.(icon-[^:]+):before/', $temp, $match);
    $awesome = $match[1];

    ?>
    <meta charset="UTF-8"/>
    <title>Witaj, Świecie!</title>

    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">

    <link rel="stylesheet" href="/css/main.css">


    <script src="/js/jquery.js"></script>

    <link rel="icon" type="image/x-icon" href="/favicon.ico"/>
</head>
<body style="margin:32px;">

<h1>Glyphicons</h1>

<div style="-moz-column-width: 22em; -moz-columns: 22em; -webkit-columns: 22em; columns: 22em;">
<?php foreach($glyphicons as $class): ?>
<i style="line-height:1.5em;vertical-align:middle;font-size: 3em" class="glyphicon <?php echo $class ?>"></i>&nbsp;&nbsp;<?php echo $class ?><br/>
<?php endforeach; ?>
</div>

<hr/>

<h1>Awesome Fonts</h1>

<div style="-moz-column-width: 22em; -moz-columns: 22em; -webkit-columns: 22em; columns: 22em;">
<?php foreach($awesome as $class): ?>
    <i style="line-height:1.5em;vertical-align:middle;font-size: 3em" class="<?php echo $class ?>"></i>&nbsp;&nbsp;<?php echo $class ?><br/>
<?php endforeach; ?>
</div>


</body>
</html>
<?php
    $font = !empty($_GET['font'])? $_GET['font'] : 72;
    $color = !empty($_GET['color'])? $_GET['color'] : '333333';
    $background = !empty($_GET['background'])? $_GET['background'] : 'efefef';
    $label = $_GET['label'];
?>

<html>
<head>
    <title><?=$label?></title>
    <style>
        html, body, div, span {
            background-color: #<?=$background?>;
            color: #<?=$color?>;
            font-family: "Courier New", Courier, monospace;
            font-size: <?=$font?>px;
            text-shadow: 5px 5px 5px rgba(0, 0, 0, .5);
        }

    </style>
</head>
<body>
    <div style="text-align: center; padding-top: 200px">
        <div style="text-align: center">
            <span class="label">
                <?=$label?>
            </span>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
    $file = "python/downloads/LYU-8IFcDPw.mp3";
    $size = [];
    $size[0] = filesize($file);
    // kb -> size[1],  mb -> size[2], gb -> size[3]
    for ($i = 0; $i < 4; $i++){
        $size[$i + 1] = $size[$i] / 1024;
    }

    //Rounded down to the second decimal place
    function round_down($number, $precision = 0){
        return floor($number * pow(10, $precision)) / pow(10, $precision);
    }

    for ($i = 0; $i < 4; $i++){
        $size[$i] = round_down($size[$i], 2);
    }

    if ($size[3] > 1){
        echo "<br>File size $size[3] GB";
    }else if ($size[2] > 1){
        echo "<br>File size $size[2] MB";
    }else if ($size[1] > 1){
        echo "<br>File size $size[1] KB";
    }else{
        echo "<br>File size $size[0] bytes";
    }

?>
</body>
</html>


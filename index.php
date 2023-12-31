<?php
session_start();
$config_json = "./config/config.json";
$config = json_decode(file_get_contents($config_json), true);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OFFLINE VIDS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--    SCRIPTS     -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--    STYLES     -->
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/progress.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        p {
            margin-top: 0;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
<?php

global $config;
$title = $config['title'];
$SVG = "./style/offline-vids.svg";

if ($title === 'true') {
    if(file_exists($SVG)){
        echo "<object data=$SVG width='514'></object>";
    }else{
        echo "<h1>OFFLINE VIDS</h1>";
    }
}else{
    echo "<h1>OFFLINE VIDS</h1>";
}

?>

<!--    SEARCH FORM     -->
<form action="process.php" method="post">
    <div class="input-group">
        <input type="text" name="url" id="url" placeholder="URL">

        <select name="format" id="format">
            <option value="default">Default</option>
            <option value="mp4">MP4</option>
            <option value="mp3">MP3</option>
        </select>
    </div>
    <input class="submit" type="submit" value="OFFLINE!">
</form>

<!--    PROGRESS BAR     -->
<div class="progress">
    <b class="progress-bar">
    <span class="progress-text">
      Progress: <em>0%</em>
    </span>
    </b>
</div>

<script src="./script/animation.js"></script>

<!--    DOWNLOAD DATA     -->
<div id="offline">
<?php
if (isset($_GET['file'])) {
    $file = $_GET['file'];

    $json_path = './python/meta/meta.json';
    $meta_json = json_decode(file_get_contents($json_path), true);

    $video_id = pathinfo($file, PATHINFO_FILENAME);
    $meta_data = null;

    $name = '';
    foreach ($meta_json as $video) {
        if ($video['id'] === $video_id) {
            $name = $video['title'];
            $meta_data = $video;
            break;
        }
    }

    if ($_GET['format'] === "mp4") {
        echo "<video width='320' height='180' controls class='download-data'><source src='$file' type='video/mp4'>動画はサポートされていません</video>";
    } else {
        echo "<audio controls class='download-data'><source src='$file' type='audio/mpeg'>音声はサポートされていません</audio>";
    }

    echo "
    <script>
    $(document).ready(function() {
    // video/audio volume set to 50%.
    $('.download-data').prop('volume', 0.5);
    });
    </script>
    ";

    echo "
        <a href='$file' download='$name'>
        <button class='donload-btn'>
            <!-- Download Icon -->
            <svg class='download-icon' width='18' height='22' viewBox='0 0 18 22' fill='none' xmlns='http://www.w3.org/2000/svg'>
                <path class='download-arrow' d='M13 9L9 13M9 13L5 9M9 13V1' stroke='#F2F2F2' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                <path d='M1 17V18C1 18.7956 1.31607 19.5587 1.87868 20.1213C2.44129 20.6839 3.20435 21 4 21H14C14.7956 21 15.5587 20.6839 16.1213 20.1213C16.6839 19.5587 17 18.7956 17 18V17' stroke='#F2F2F2' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
            </svg>
            Download
        </button>
        </a>
    ";

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

    function view_data($index){
        $size_unit = ['bytes', 'KB', 'MB', 'GB'];

        global $size;
        echo "
        <p>
            File size : $size[$index]$size_unit[$index]
        </p>
        ";
    }

    for ($i = 0; $i < 4; $i++){
        $size[$i] = round_down($size[$i], 2);
    }

    for ($i = sizeof($size) - 1; $i >= 0; $i--){
        if ($size[$i] > 1 || $i === 0){
            view_data($i);
            break;
        }
    }

    $id = $meta_data['id'];
    $id = hash('sha256', $id);
    echo "<p>ID : $id</p>";

    $name = $meta_data['title'];
    echo "<p>Name : $name</p>";

    $video_quality = $meta_data['video_quality'];
    $audio_quality = $meta_data['audio_quality'];

    if($video_quality !== null){
        echo "<p>Video Quality : $video_quality</p>";
    }

    if($audio_quality !== null){
        echo "<p>Audio Quality : $audio_quality kbps</p>";
    }
}
?>
</div>
</body>
</html>
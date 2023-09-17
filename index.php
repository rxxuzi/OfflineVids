<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OFFLINE VIDS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--    SCRIPTS     -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--      CSS       -->
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/progress.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body>

<?php

$config = json_decode(file_get_contents('config.json'), true);
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

<form action="process.php" method="post">
    <div class="input-group">
        <input type="text" name="url" id="url" placeholder="URL">

        <select name="format" id="format">
            <option value="mp4">MP4</option>
            <option value="mp3">MP3</option>
        </select>
    </div>
    <input class="submit" type="submit" value="OFFLINE!">
</form>

<div class="progress">
    <b class="progress-bar">
    <span class="progress-text">
      Progress: <em>0%</em>
    </span>
    </b>
</div>

<script src="animation.js"></script>

<div id="offline">
<?php
if (isset($_GET['file'])) {
    $file = $_GET['file'];

    // タイトル情報をJSONファイルから取得
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
    // videoの音量を50%に設定
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

    $id = $meta_data['id'];
    $id = hash('sha256', $id);
    echo "<br>ID : $id";

    $name = $meta_data['title'];
    echo "<br>Name : $name";
}
?>
</div>

</body>
</html>
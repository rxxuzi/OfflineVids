<?php
session_start();

// config.json を読み込む
$config = json_decode(file_get_contents('config.json'), true);

// セッション変数をチェック
if (!isset($_SESSION['from_process'])) {
    // auto_cleanup が true なら、downloads ディレクトリのファイルを削除する
    if (isset($config['auto_cleanup']) && $config['auto_cleanup'] === "true") {
        $files = glob('./python/downloads/*'); // downloads ディレクトリのファイルをすべて取得
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);  // ファイルを削除
            }
        }
    }
} else {
    // セッション変数をリセット
    unset($_SESSION['from_process']);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>online video downloader</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="progress.js"></script>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>

<h1>Offline Vids</h1>

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

<div class="pgv">
    <progress value="0" max="100" id="progress"></progress>
</div>

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
        echo "<video width='320' height='180' controls><source src='$file' type='video/mp4'>動画はサポートされていません</video>";
    } else {
        echo "<audio controls><source src='$file' type='audio/mpeg'>音声はサポートされていません</audio>";
    }

    echo "
        <a href='$file' download='$name'>
        <button>
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
}
?>
</div>
<div class='download'>

</div>

</body>
</html>





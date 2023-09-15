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
    URL: <input type="text" name="url"><br>
    フォーマット:
    <select name="format">
        <option value="mp4">MP4</option>
        <option value="mp3">MP3</option>
    </select><br>
    <input type="submit" value="OFFLINE!">
</form>

<div>
    <progress value="0" max="100" id="progress"></progress>
</div>

<div id="download-link"></div>
    <?php
    if (isset($_GET['file'])) {
        $file = $_GET['file'];
        if ($_GET['format'] === "mp4") {
            echo "<video width='320' height='240' controls><source src='$file' type='video/mp4'>動画はサポートされていません</video>";
        } else {
            echo "<audio controls><source src='$file' type='audio/mpeg'>音声はサポートされていません</audio>";
        }
        echo "<br><a href='$file'>ダウンロード</a>";
        $size = filesize($file);
        echo "<br>File size.$size";
    }
    ?>

</body>
</html>





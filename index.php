<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ビデオコンバーター</title>
    <script src="progress.js"></script>
</head>
<body>

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
    }
?>
</body>
</html>





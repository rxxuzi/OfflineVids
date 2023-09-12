<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_time_limit(0);

if (isset($_POST['url']) && isset($_POST['format'])) {
    $url = escapeshellarg($_POST['url']);
    $format = escapeshellarg($_POST['format']);

    $interpreterPath = "python";

    $command = "$interpreterPath ./python/download.py $url $format 2>&1";
    $output = shell_exec($command);
    $lines = explode(PHP_EOL, $output);
    $output = end($lines); // 最後の行だけを取得


    if ($output !== null) {
        $output = trim($output);
    }


    // Windowsの場合、パスのスラッシュの方向を変更する
    $corrected_output = str_replace('\\', '/', $output);
    $corrected_document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);

    if ($output !== null) {
        $output = trim($corrected_output);
    }

    $relative_path = str_replace($corrected_document_root, '', $output);
    $web_accessible_path = ltrim($relative_path, '/');

    if ($output && file_exists($corrected_output)) {
        header("Location: index.php?file=" . urlencode($web_accessible_path) . "&format=" . $_POST['format']);
        exit;  // リダイレクト後にスクリプトの実行を停止
    } else {
        echo "コマンドの実行: " . htmlspecialchars($command) . "<br>"; // コマンドの内容を表示
        echo "エラーが発生しました: " . htmlspecialchars($output); // エラーメッセージも表示
    }
}



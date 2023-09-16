<?php
session_start();

$_SESSION['from_process'] = true;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_time_limit(0);

$config = json_decode(file_get_contents('config.json'), true);
$interpreterPath = $config['interpreter_path'];

if (!filter_var($_POST['url'], FILTER_VALIDATE_URL)){
    echo "
    <h1>ERROR</h1>
Invalid URL. Please enter a correct URL.<br>";
    exit;
}
if (isset($_POST['url']) && isset($_POST['format'])) {

    $start_time = microtime(true);

    $url = escapeshellarg($_POST['url']);

    $format = escapeshellarg($_POST['format']);

    $command = "$interpreterPath ./python/download.py $url $format 2>&1";
    $output = shell_exec($command);
    if ($output === null) {
        die("コマンドの実行に失敗しました。");
    }
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

    $end_time = microtime(true);
    $execution_time = $end_time - $start_time; //実行時間

    if ($output && file_exists($corrected_output)) {
        header("Location: index.php?file=" . urlencode($web_accessible_path) . "&format=" . $_POST['format']);
        write_log($execution_time, "success");
        exit;  // リダイレクト後にスクリプトの実行を停止
    } else {
        write_log($execution_time, "error");
        echo "コマンドの実行: " . htmlspecialchars($command) . "<br>"; // コマンドの内容を表示
        echo "エラーが発生しました: " . htmlspecialchars($output); // エラーメッセージも表示
    }
}

function write_log($execution_time , $result){
    $filename = $_SERVER['SCRIPT_NAME'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "direct access";

    $log_content = sprintf(
        "Time: %s\nFile: %s\nExecution Time: %.4f seconds\nResult: %s\nUser Agent: %s\nIP Address: %s\nReferrer: %s\n---\n",
        date("Y-m-d H:i:s"),
        $filename,
        $execution_time,
        $result,
        $user_agent,
        $ip_address,
        $referrer
    );

    $log_dir = './log/';
    $log_file = $log_dir . 'log_' . date("Y-m-d") . '.log';

    // ディレクトリが存在しない場合、ディレクトリを作成
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0777, true); // 第3引数に true を指定して、再帰的にディレクトリを作成
    }

    file_put_contents($log_file, $log_content, FILE_APPEND);


}

<?php

set_time_limit(0);

if (isset($_POST['url']) && isset($_POST['format'])) {
    $url = escapeshellarg($_POST['url']);
    $format = escapeshellarg($_POST['format']);

    $interpreterPath = "python";

    $command = "$interpreterPath ./python/download.py $url $format";
    $output = shell_exec($command);

    if ($output !== null) {
        $output = trim($output);
    }

    if ($output && file_exists($output)) {
        header("Location: index.php?file=" . urlencode($output));
    } else {
        echo "エラーが発生しました: " . $output;
    }
}



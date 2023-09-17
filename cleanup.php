<?php

/*
 * Use a cron job to run this script automatically
 */

$config = json_decode(file_get_contents('config.json'), true);

if (isset($config['auto_cleanup']) && $config['auto_cleanup'] === "true") {
    // downloadsディレクトリのクリーンアップ
    $files = glob('./python/downloads/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }

    // meta.jsonのクリーンアップ
    if (file_exists('./python/meta/meta.json')) {
        unlink('./python/meta/meta.json');
    }
}

?>

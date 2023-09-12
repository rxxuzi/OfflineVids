document.addEventListener("DOMContentLoaded", function() {
    function updateProgress() {
        fetch('/get_progress.php')
            .then(response => response.text())
            .then(progress => {
                const progressBar = document.getElementById('progress');
                progressBar.value = progress;
            });
    }

    // 1秒おきに進捗情報をチェック
    setInterval(updateProgress, 1000);
});


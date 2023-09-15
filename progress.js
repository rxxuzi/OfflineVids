const progressText = document.getElementById("progress-text");

function checkProgress() {
    let timestamp = new Date().getTime(); // タイムスタンプを取得
    $.getJSON('./python/progress.json?_=' + timestamp, function(data) { // タイムスタンプをURLに追加
        let progress = data.progress || 0;
        console.log("Current Progress: " + progress); // デバッグ用
        document.getElementById("progress").value = progress;

        progressText.innerText = Math.min(progress, 100) + "%";

        if (progress < 100) {
            setTimeout(checkProgress, 1000);
        }

    });
}


document.addEventListener('DOMContentLoaded', function() {
    checkProgress();
});


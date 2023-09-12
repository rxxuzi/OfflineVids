function updateProgress() {
    // このサンプルでは、サーバーが進行状況をJSON形式で返すことを仮定しています
    // 例: { progress: 50 } は進行状況が50%であることを示します

    fetch('./get_progress.php').then(response =>
        response.json()
    ).then(data => {
        const progressBar = document.getElementById('progress');
        progressBar.value = data.progress;

        if (data.progress < 100) {
            setTimeout(updateProgress, 1000); // 1秒ごとに更新
        }
    }).catch(error => {
        console.error('Error:', error);
    });
}

// ページの読み込みが完了したら進行状況の更新を開始
window.onload = updateProgress;

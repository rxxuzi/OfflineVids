import $ from 'jquery';
function checkProgress() {
    $.getJSON('./python/progress.json', function(data) {
        let progress = data.progress || 0;
        document.getElementById("progress").value = progress;

        if (progress < 100) {
            setTimeout(checkProgress, 1000);
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    checkProgress();
});


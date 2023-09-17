// DOM要素の参照
var $progress = $(".progress"),
    $bar = $(".progress-bar"),
    $text = $(".progress-text");
// submitボタン
const $submit = $(".submit");

// 各色のしきい値
const cb = {
    red: 0,
    orange: 25,
    yellow: 50,
    green: 75,
    blue: 100
};

let isSuccess = false;

// パーセンテージの初期値
let percent = 0;

// クラスをクリアする関数
clear = () => {
    $bar.removeClass('progress-bar--red progress-bar--orange progress-bar--yellow progress-bar--green progress-bar--blue');
    $progress.removeClass("progress--complete");
};

// 進行状況を更新する関数
update = function() {

    console.log("Updating with percent: ", percent);  // デバッグ用のログを追加

    if (percent === 0 ){
        $text.find("em").text("None");
    }else{
        $progress.fadeIn();
        $text.find("em").text( percent + "%" );
    }

    // 進行状況のパーセンテージに基づいて色を更新
    if (percent >= cb["blue"]) {
        isSuccess = true;
        percent = 100;
        $progress.addClass("progress--complete");
        $bar.addClass("progress-bar--blue");
        $text.find("em").text("Complete");
        console.log("Complete");

    } else {
        if (percent >= cb["green"]) {
            $bar.addClass("progress-bar--green");
        } else if (percent >= cb["yellow"]) {
            $bar.addClass("progress-bar--yellow");
        } else if (percent >= cb["orange"]) {
            $bar.addClass("progress-bar--orange");
        } else {
            $bar.addClass("progress-bar--red");
        }
    }

    if (isSuccess){
        setTimeout(() => {
            $progress.fadeOut();
        }, 3000); // 3秒後に実行
    }

    // プログレスバーの幅を更新
    $bar.css({ width: percent + "%", opacity: 1 });
};

let tmp = 0;

// JSONから進行状況の値を取得し、進行状況を更新する関数
function fetchAndUpdate() {
    $.getJSON('python/progress.json?' + new Date().getTime(), function(data)  {

        percent = data.progress; // JSON内の進行状況の値を取得
        if(percent < tmp){
            percent = tmp;
        }else {
            tmp = percent;
        }

        update();
    }).fail(function(jqXHR, textStatus, errorThrown) {
    console.error('Error status:', jqXHR.status);
    console.error('Error message:', errorThrown.message);
    console.error('Full response:', jqXHR.responseText);
});
}

// ページの読み込みが完了したときに実行
document.addEventListener('DOMContentLoaded', function() {
    // すべてのクラスをクリア
    clear();

    isSuccess = false;
    // 初回の取得
    fetchAndUpdate();

    // 0.5秒ごとに定期的にJSONから進行状況の値を取得
    setInterval(fetchAndUpdate, 500);
});

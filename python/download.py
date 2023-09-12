import os
import sys
import yt_dlp
import json

# 進捗情報の保存先を定義
PROGRESS_FILE = "./python/progress.json"

def update_progress(percentage):
    with open(PROGRESS_FILE, "w") as f:
        f.write(json.dumps({"progress": percentage}))
        f.close()

def download_video(url, format):

    def hook(d):
        if d['status'] == 'downloading':
            # 進捗率を取得してファイルに書き込む
            p = d['_percent_str'].replace('%', '')
            update_progress(float(p))

        if d['status'] == 'finished':
            # 出力ファイルの拡張子を設定
            ext = 'mp3' if format == 'mp3' else 'mp4'
            # ファイル名から品質コード部分を削除
            filename = d['filename'].rsplit('.', 1)[0]
            filename = filename.rsplit('.f', 1)[0]  # ここで品質コードを削除
            filename += '.' + ext  # 正しい拡張子を追加
            print("\n" + filename)
            # 進捗を100%に設定
            update_progress(100.0)



    if format == "mp3":
        postprocessors = [{
            'key': 'FFmpegExtractAudio',
            'preferredcodec': 'mp3',
        }]
    else:  # for mp4 format
        postprocessors = [{
            'key': 'FFmpegVideoConvertor',
            'preferedformat': 'mp4',
        }]

    ydl_opts = {
            'format': 'bestaudio' if format == 'mp3' else 'bestvideo[ext=mp4]+bestaudio[ext=m4a]/best[ext=mp4]/best',
            'outtmpl': os.path.abspath('./python/downloads/%(id)s.%(ext)s'),  # full path
            'postprocessors': postprocessors,
            'quiet': True,      # Make yt_dlp quiet
            'no_warnings': True, # Do not print out warnings
            'progress_hooks': [hook],
            'noplaylist': True,  # Prevent downloading of playlists
            'nopart': True       # Do not create .part files
        }


    with yt_dlp.YoutubeDL(ydl_opts) as ydl:
        ydl.download([url])
        # ダウンロード終了後、進捗をリセット
        update_progress(0)

if __name__ == "__main__":
    url = sys.argv[1]
    format = sys.argv[2]
    download_video(url, format)


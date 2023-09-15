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
            # Retrieve the rate of progress and write it to a file
            p = d['_percent_str'].replace('%', '')
            update_progress(float(p))
        if d['status'] == 'finished':
            # Set output file extension
            ext = 'mp3' if format == 'mp3' else 'mp4'
            # Remove quality code portion from file name
            filename = d['filename'].rsplit('.', 1)[0]
            filename = filename.rsplit('.f', 1)[0]  # Delete quality code here
            filename += '.' + ext  # Add correct extension
            print("\n" + filename)
            # Set progress to 100%.
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
        # Reset progress after download is complete
        update_progress(0)

if __name__ == "__main__":
    url = sys.argv[1]
    format = sys.argv[2]
    download_video(url, format)


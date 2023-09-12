import sys
import yt_dlp

def download_video(url, format):
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
        'outtmpl': './python/downloads/%(id)s.%(ext)s',
        'postprocessors': postprocessors,
        'quiet': True,      # Make yt_dlp quiet
        'no_warnings': True, # Do not print out warnings
        'progress_hooks': [hook],
    }

    def hook(d):
        if d['status'] == 'finished':
            print(d['filename'])

    with yt_dlp.YoutubeDL(ydl_opts) as ydl:
        ydl.download([url])

if __name__ == "__main__":
    url = sys.argv[1]
    format = sys.argv[2]
    download_video(url, format)


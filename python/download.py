import os
import sys
import yt_dlp
import json
import time

# Define where progress information is stored
PROGRESS_FILE = "./python/progress.json"

def update_progress(percentage):
    with open(PROGRESS_FILE, "w") as f:
        f.write(json.dumps({"progress": percentage}))

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
            'outtmpl': os.path.abspath('./python/downloads/%(id)s.%(ext)s'),  # full path based on video id
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

    info_dict = ydl.extract_info(url, download=False)
    video_title = info_dict.get('title', None)
    video_id = info_dict.get('id', None)
    file_path = os.path.abspath(f'./python/downloads/{video_id}.{format}')
    file_size = os.path.getsize(file_path) if os.path.exists(file_path) else 0

    format_info = info_dict.get('format')
    if isinstance(format_info, dict):
        video_quality = format_info.get('format_note', None)
    else:
        video_quality = None

#     video_quality = info_dict.get('format', {}).get('format_note', None)  # 例：720p, 1080pなど
    audio_quality = info_dict.get('abr', None)  # 音質（平均ビットレート）
    current_time = time.time()  # Get current timestamp


    meta_json_path = './python/meta/meta.json'
    data = []
    if os.path.exists(meta_json_path):
        with open(meta_json_path, 'r') as f:
            file_content = f.read()
            if file_content:  # If the JSON file is not empty
                try:
                    data = json.loads(file_content)
                except json.JSONDecodeError:
                    print("Error: Invalid JSON content in the file.")
    else:
        # If the file does not exist, an empty list is set as the initial value
        data = []


    file_type = 'audio' if format == 'mp3' else 'video'

    # Add new information
    data.append({
        'id': video_id,
        'title': video_title,
        'video_quality': video_quality,
        'audio_quality': audio_quality,
        'byte': file_size,
        'time': current_time,
        'url': url,
        'type': file_type
    })

    # Keep updated information on file
    with open(meta_json_path, 'w') as f:
        json.dump(data, f, indent=4)


if __name__ == "__main__":
    start_time = time.time()  # start
    url = sys.argv[1]
    format = sys.argv[2]
    download_video(url, format)
    end_time = time.time()  # end



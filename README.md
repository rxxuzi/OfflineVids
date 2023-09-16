# OfflineVids

OfflineVids is a web-based application that provides a user-friendly interface to download videos. It uses a combination of PHP, JavaScript, and other web technologies to achieve smooth user experience and efficient video processing.

## Features

- **User-friendly Interface**: A clean and intuitive interface for users to input video URLs and initiate downloads.
- **Download Progress Bar**: A dynamic progress bar updates users about the download status.
- **Automatic Cleanup**: The application periodically cleans up downloaded data and meta-information to ensure optimal server performance.
- **Logging**: All operations, including downloads and errors, are logged for administrative purposes.

## Getting Started

To set up OfflineVids, you need a server environment that supports PHP. Download or clone this repository to your server's web directory.

## Configuration

### 1. Setting Up Python Interpreter Path

For OfflineVids to run smoothly, especially for the video downloading feature, you need to specify the absolute path to your Python interpreter in the `config.json` file. You can set the path as follows:

```json
{
  "interpreter_path" : "YOUR_PYTHON_INTERPRETER_PATH"
}
```

Replace ` YOUR_PYTHON_INTERPRETER_PATH` with the actual Python interpreter path.

This step is crucial for environments where the default Python interpreter might not be directly accessible or when multiple versions of Python are installed.

### 2. Auto-Cleanup Configuration

In the `config.json` file, you can also configure the auto-cleanup feature:

- `"auto_cleanup": "true"` or `"auto_cleanup": "false"` to enable or disable the auto cleanup.
- `"cleanup_interval": NUMBER` to specify the cleanup interval in seconds.

## Dependencies

- **jQuery**: Used for front-end scripting and AJAX interactions.
- **PHP**: For server-side scripting and managing download operations.

## How to Use

1. Navigate to the application's URL.
2. Input the desired video URL.
3. Click the "Submit" button.
4. Monitor the download progress through the progress bar.
5. when the download is complete, the video/audio download button and their information will be displayed with a message

## Maintenance

- If you've enabled automatic cleanup, the server will periodically delete downloaded videos and meta-information based on the interval you've set.
- Logs are generated and saved in the `./log/` directory. These can be used for administrative or debugging purposes.

## Known Issues

- When the download completes, the appearance of the `<div id="offline">` can cause layout shifts. This is currently being addressed.

## Credits

This application was developed with the help of various libraries and resources:

- jQuery
- PHP

## External Tools

- **yt-dlp**: OfflineVids utilizes `yt-dlp`, a powerful command-line tool, to fetch and download videos. It's an essential component of the backend processing. For more details and to understand its capabilities, visit the official [yt-dlp repository](https://github.com/yt-dlp/yt-dlp).

## SETUP

1. Clone or download the repository.
~~~shell
git clone https://github.com/rxxuzi/OfflineVids.git
~~~

2. Run the [SETUP.sh](SETUP.sh) script to set up the required directories and dependencies.

3. Adjust the configurations in [config.json](config.json) as described in the Configuration section above.

4. Start your web server and navigate to the project directory.

## License

This project is licensed under the Apache License 2.0. For detailed terms and conditions, please refer to the included [LICENSE](LICENSE) file.

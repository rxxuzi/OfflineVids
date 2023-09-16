#!/bin/bash

echo "Starting OfflineVids setup..."

# Creating necessary directories
echo "Creating necessary directories..."
mkdir -p ./python/downloads
mkdir -p ./python/meta
mkdir -p ./log

# Creating meta.json if it doesn't exist
META_FILE="./python/meta/meta.json"
if [[ ! -f "$META_FILE" ]]; then
    echo "{}" > "$META_FILE"
    echo "Created meta.json..."
else
    echo "meta.json already exists. Skipping creation."
fi

# Installing yt-dlp
echo "Installing yt-dlp..."
pip install -U yt-dlp

# Permissions (Optional, but sometimes required for web servers)
chmod -R 755 ./python/downloads
chmod -R 755 ./python/meta
chmod -R 755 ./log

echo "Setup complete! OfflineVids is now ready to run."

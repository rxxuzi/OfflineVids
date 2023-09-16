rm -rf ./downloads/*
rm -rf ./logs/*
rm -rf ./meta/meta.json

# get now time
now=$(date +"%Y-%m-%d %H:%M:%S")
echo "start at $now" > ./logs/log.txt
# ffmpeg examples

## copy video, transcode audio

    ffmpeg -i input -c:v copy -c:a aac -b:a 128k out.mp4

## crf example (bitrate will vary wildly depending on input complexity)

    ffmpeg -i input -c:v libx264 -preset fast -crf 22 -c:a aac -b:a 128k out.mp4
    
(Not recommended if you need to keep bitrate under control)

## crf example with bitrate limiter

    ffmpeg -i input -c:v libx264 -crf 20 -maxrate 2000k -bufsize 4000k out.mp4

## 1 pass vbr example

    ffmpeg -i input -c:v libx264 -preset fast -b:v 1000k -c:a aac -b:a 128k out.mp4
    
There are various presets (ultrafast,superfast, veryfast, faster, fast, medium, slow, slower, veryslow, placebo), tunes and profiles, see here <https://trac.ffmpeg.org/wiki/Encode/H.264>

## 1 pass vbr example with buffer and maxrate limiter

    ffmpeg -i input -c:v libx264 -b:v 1000k -maxrate 4000k -bufsize 2000k out.mp4

## 1 pass vbr example with some video downscaling and specifical color sampling (420p)

    ffmpeg -i input -pix_fmt yuv420p -vf scale=1024:576 -c:v libx264 -preset fast -b:v 1000 -c:a aac -b:a 128k out.mp4
    
## If you suspect the device is slow to decode stuff

add

    -tune fastdecode 

after -c:v libx264    

    

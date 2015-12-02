work in progress ...

# 32 bit AviSynth on 64 bit Debian Jessie linux (2015/16)

## install

What we want is a 32 bit wine layer, since AviSynth and most of the plugins are 32 bit apps

    sudo -i
    dpkg --add-architecture i386 && apt update
    exit

    sudo apt-get install wine32
    sudo apt-get install mpv

Download the latest 32 bit AviSynth from <http://avisynth.nl/index.php/Main_Page>, like

    wget http://sourceforge.net/projects/avisynth2/files/latest/download
    mv download avisynth.exe # if you get a "download" filename

wine, run the installer

    wine32 avisynth.exe

will download mono ...
and present you with the installer questions, clicky clicky and it should install.

Figure out the windows $PATH

	wine32 cmd
	echo %PATH%
	exit

should return something like C:\windows\system32;C:\windows;C:\windows\system32\wbema

Back in linux cli
    
    cd
    cd .wine/drive_c/windows/
    wget http://akuvian.org/src/avisynth/avs2yuv/avs2yuv.exe

Read the <http://akuvian.org/src/avisynth/avs2yuv/> to get an idea on what asv2yuv is.

## first script

    cd
    vi version.avs
    # and paste
    Version()
    # save and quit

Now

    wine32 avs2yuv version.avs - | mpv -

Should show an AviSynth version in player window.

### named pipes ?

    fail:
    
    mkfifo new.y4m
    wine32 avs2yuv version.avs -o new.y4m 
    version.avs: 464x82, 24 fps, 240 frames
    converting RGB -> YV12
    fopen("╥■@") failed
    
    
to be continued ...

## links
<http://ubuntuforums.org/showthread.php?t=1333264> < an older guide  
<http://www.vapoursynth.com/> < alternative to avisynth



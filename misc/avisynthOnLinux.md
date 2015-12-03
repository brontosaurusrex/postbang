work in progress ...

# 32 bit AviSynth on 64 bit Debian Jessie Linux (2015/16)

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
    wget http://akuvian.org/src/avisynth/avs2yuv/avs2yuv.exehttp://avisynth.nl/index.php/QTGMC

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

### less typing script for preview (preview)

    #!/bin/bash
    # preview avs script in mpv
    wine32 avs2yuv "$1" - | mpv -
    
Save this to ~/bin as preview and chmod +x preview
    
## QTGMC 

"A very high quality deinterlacer with a range of features for both quality and convenience."

<http://avisynth.nl/index.php/QTGMC>

What we need is a bunch of plugins and an avsi script installed in correct directories, see requirements <http://avisynth.nl/index.php/QTGMC#Requirements>  
But to make things a bit simpler, lets just download a bundle

    cd ~/downloads
    mkdir qtgmc && cd qtgmc
    wget http://www.spirton.com/uploads/QTGMC/QTGMC%2032-bit%20Plugins.zip
    
    sudo apt-get install unp
    unp -u *    # the -u switch will generate a subdir 
    
Lets make a destination path variable like (change USER to your actual user name)

    plugins="/home/USER/.wine/drive_c/Program Files/AviSynth 2.5/plugins/"
    
And start copiing stuff to avisynth Plugins directory

    cd QTGMC(TAB)
    cp 32-bit\ System\ dlls/*.dll ~/.wine/drive_c/windows/system32/
    cp Avisynth\ 32-bit\ Plugins/2.6x\ Plugins/*.dll "$plugins"
    
And the main QTGMC script itself

    wget http://www.spirton.com/uploads/QTGMC/QTGMC-3.33.avsi
    cp QTGMC-3.33.avsi "$plugins"
    
Another base filter one would like to have is ffmpeg based media source reader  
<http://avisynth.nl/index.php/FFmpegSource>, so

    cd ~/.downloads/qtgmc/
    wget https://github.com/FFMS/ffms2/releases/download/2.22/ffms2-2.22-msvc.7z
    unp -u ffms(TAB)
    cd ffms2-2.22-msvc/ffms2-2.22-msvc/
    cp *.avsi "$plugins"
    cp x86/* "$plugins"
    
Testing ...

    cd && cd tmp
    
vi qtgmcTest.avs

    # YourSource("yourfile")    # DGDecode_mpeg2source, FFVideoSource, AviSource, whatever your source requires
    
    Version().ConvertToYV12().BilinearResize(240,180)  # This generates some fake input, so we don't have to load the actual media file just yet
    
    QTGMC( Preset="Slow" )
    SelectEven()              # Add this line to keep original frame rate, leave it out for smoother doubled frame rate
    
Now run the preview

    preview qtgmcTest.avs
    
Nothing special, just an ugly avisynth version display, but qtgmc seems to be working ...



to be continued ...

## links
<http://avisynth.nl/index.php/Getting_started>
<http://ubuntuforums.org/showthread.php?t=1333264> < an older guide  
<http://www.vapoursynth.com/> < alternative to avisynth



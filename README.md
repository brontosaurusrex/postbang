# Jessie from netinstall

the manual approach to post-banging the Jessie, work in progress

## install Debian Jessie

Base is what we want and nothing else

<https://www.debian.org/devel/debian-installer>

as su

    apt-get install openbox xserver-xorg xinit
    apt-get install terminator vim thunar tint2 geany gmrun
    apt-get install htop mc inxi

Note: terminator and thunar will bring in lots of stuff

### sudo

as su

    apt-get install sudo
    adduser <username> sudo

reboot ?

### virtualbox related

Insert Guest additions cd, it should appear in /media/cdrom

    cd
    mkdir /home/user/tmp
    (copy the cd to tmp)

sudo

    apt-get install dkms
    cd /home/user/tmp
    ./VBoxLinuxAdditions.run
    reboot

### cont

sudo

    apt-get install compton

to test

    compton & tint2 &

configure vim (if in vbox, now might be a good time to enable bidirectional clipboard)

sudo

    vi /etc/vim/vimrc.local

(that is a new file), paste:

    set number
    syntax on
    set ruler

### network manager

sudo

    apt-get install network-manager-gnome
    rm /etc/network/interfaces

    vi /etc/NetworkManager/NetworkManager.conf

change ifupdown managed to true.

    sudo service network-manager restart

warning: fix

As noted on the #! forum, one should not remove /etc/network/interfaces , so lets fix that:

    sudo vi /etc/network/interfaces

paste

    # This file describes the network interfaces available on your system
    # and how to activate them. For more information, see interfaces(5).

    # The loopback network interface
    auto lo
    iface lo inet loopback

:wq

as user

    openbox --exit
    startx

    compton & tint2 &

The network applet should show up on tint2

    scrot -d 5

<a href="http://shrani.si/f/2o/PM/1aLoN4EY/network.png"><img src="http://shrani.si/t/2o/PM/1aLoN4EY/network.jpg" style="border: 0px;" alt="Shrani.si"/></a>

### openbox autostart, basic

    cd
    vi .config/openbox/autostart 

(a new file), paste something like

    compton & tint2 &

test

    openbox --exit
    startx

and compton and tint2 should sit there.

### terminator

sudo

    apt-get install xfonts-terminus
    apt-get install git

user

    cd
    mkdir source
    cd source
    git clone https://github.com/brontosaurusrex/postbang
    cd postbang
    ls -lha
    cp -b .config/terminator/config ~/.config/terminator/

and bash related (backup those first, if you will)

    cp -b .bashrc ~
    cp -b .bash_aliases ~

And your terminator should look as fancy as this;

<a href="http://shrani.si/f/3c/sE/3GAWHHaD/terminator.png"><img src="http://shrani.si/t/3c/sE/3GAWHHaD/terminator.jpg" style="border: 0px;" alt="Shrani.si"/></a>

user

    alias

should list your new (and old aliases). To get transfer.sh cababilities;

sudo

    apt-get install curl

then you can use transfer alias to upload files from command line, more info at <http://transfer.sh>.

### default xdg directories

    cd /etc/xdg

sudo

    cp user-dirs.defaults user-dirs.defaults.bak
    vi user-dirs.defaults

paste

    DOWNLOAD=downloads
    DOCUMENTS=documents
    MUSIC=music
    PICTURES=images
    VIDEOS=videos

user

    cd 
    cd .config
    rm user-dirs.*
    xdg-user-dirs-update
    cd
    ls

voila. More <https://wiki.archlinux.org/index.php/Xdg_user_directories#Creating_custom_directories>



### usability & preparing for scripts

Certainly my idea of "usability" can be just mine, so you can skip this part freely.

Video player than many of my scripts are using is called mpv and it seems to be in Jessie repos now.

sudo

    apt-get install mpv youtube-dl mediainfo mkvtoolnix

If you are running in virtualbox and mpv is crashy

    echo "vo=x11" > ~/.config/mpv/mpv.conf

Make a user bin directory, as user

    cd
    mkdir bin
    source ~/.profile

alsa, sudo

    apt-get install alsa-base alsa-tools alsa-tools-gui alsa-utils alsa-oss alsamixergui libalsaplayer0
    reboot

more <http://crunchbang.org/forums/viewtopic.php?id=23930>

user

    cd
    cd source/postbang
    git pull
    cp ./bin/pt ~/bin/

pt is a simple mpv/youtube-dl script that plays video, example:

    pt https://www.youtube.com/watch?v=0KSOMA3QBU0

should give you some Katy Perry..., I know.

<a href="http://shrani.si/f/P/Cu/4rISkwzH/pt.png"><img src="http://shrani.si/t/P/Cu/4rISkwzH/pt.jpg" style="border: 0px;" alt="Shrani.si"/></a>

a browser and an image viewer and a command line unpacker, sudo

    apt-get install iceweasel viewnior unp

I would add an Adblock Plus extension right away

<a href="http://shrani.si/f/12/Bf/3dU9hsA9/iceweasel.png"><img src="http://shrani.si/t/12/Bf/3dU9hsA9/iceweasel.jpg" style="border: 0px;" alt="Shrani.si"/></a>

test the alias "open" and viewnior, as user

    cd
    wget http://shrani.si/f/44/HW/4c0Czz2S/swirllux.png && open swirllux.png
    rm swirllux.png

ffmpeg_static, user

    cd ~/downloads
    wget http://johnvansickle.com/ffmpeg/builds/ffmpeg-git-64bit-static.tar.xz

Change to 32 if your Debian is like that.

    unp ffmpeg-git-64bit-static.tar.xz
    cp ffmpeg-git-xxxxxxxx-64bit-static/ffmpeg ~/bin/ffmpeg_static
    rm -r ./ffmpeg-git-xxxxxxxx-64bit-static/ ffmpeg-git-64bit-static.tar.xz

test ffmpeg_static with some noio to null encoding

    cd ~/source/postbang/ && git pull && cp ./bin/noio ~/bin/
    noio

### makeup

#### infinality font stuff

sudo

    apt-get install devscripts fakeroot
    apt-get install docbook-to-man libx11-dev x11proto-core-dev libz-dev quilt

as user

    cd 
    cd source
    git clone https://github.com/chenxiaolong/Debian-Packages

run both build.sh scripts, install all the debs with

sudo

    dpkg -i *.deb

#### openbox, openbox menu logic and beauty

    cp -b ~/source/postbang/.config/openbox/rc.xml ~/.config/openbox/
    openbox --restart

Should give you:
- bronto-manual tiling, try mod+a,1 or mod+a,2 or mod+a,enter
- decoration rules (mpv is undecorated)
- additional mice behaviour, for example middle mouse button on tint2 will kill that app
- and so on

#### tint2

as user

    cp -b ~/source/postbang/.config/tint2/tint2rc ~/.config/tint2/

(-b stands for: backup dest file, btw)

    mkdir ~/.fonts/
    cp ~/source/postbang/.fonts/* ~/.fonts/
    killall tint2
    tint2 &

Everything should look like this now

<a href="http://shrani.si/f/3O/Yh/4QvwRs8F/likethis.png"><img src="http://shrani.si/t/3O/Yh/4QvwRs8F/likethis.jpg" style="border: 0px;" alt="Shrani.si"/></a>

#### icons

sudo

    apt-get install lxappearance librsvg2-bin zenity imagemagick

user

    cd
    mkdir .icons
    cd source
    clone https://github.com/jcubic/Clarity
    cp -r ./Clarity/ ~/.icons/
    cd ~/.icons/Clarity
    ./configure
    make

Copy a custom black theme from my git

    cp ~/source/postbang/.icons/Clarity/src/template_postbang.svg ~/.icons/Clarity/src/
    cd ~/.icons/Clarity/
    ./change-theme

Select postbang theme and run, then 

    cp ~/source/postbang/.gtkrc-2* ~


#### themes

    cd
    mkdir .themes
    cp -r ~/source/postbang/.themes/FlatStudioLightBronto ~/.themes

sudo

    apt-get install dmz-cursor-theme
    apt-get install gtk2-engines-murrine gtk2-engines-pixbuf

user

    cp -b ~/source/postbang/.config/openbox/rc.xml ~/.config/openbox/

logout, login and it could look like this:

<a href="http://shrani.si/f/2e/8N/2kLZW6BM/themed.png"><img src="http://shrani.si/t/2e/8N/2kLZW6BM/themed.jpg" style="border: 0px;" alt="Shrani.si"/></a>

#### some more fonts and gdebi

sudo apt-get install --no-install-recommends fonts-dejavu fonts-droid ttf-freefont ttf-liberation ttf-mscorefonts-installer gdebi

#### wallpaper

sudo

    apt-get install nitrogen

user, change the autostart to include nitrogen

    vi ~/.config/openbox/autostart

make it look like:

    nitrogen --restore & compton & tint2 &

user

    cd && cd images && mkdir wallpapers
    cd && cd source/postbang && git pull
    cp ~/source/postbang/images/wallpapers/*.png ~/images/wallpapers/
    nitrogen

Add directory images/wallpapers, select one of the wallpapers ....
(btw: as with most of this guide this is single-user approach,)

#### obmenu and obapps

sudo

    apt-get install obmenu
    
user

    cd 
    cd downloads
    wget sourceforge.net/projects/obapps/files/obapps-0.1.7.tar.gz
    unp obapps-0.1.7.tar.gz 
    cd obapps-0.1.7/
    
sudo    
    
    python setup.py install
    apt-get install python-xlib python-wxtools

user

    obapps & obmenu

### openbox menu, the king of the castle

Came up with this, totally "passive";

<a href="http://shrani.si/f/E/6k/3CBVQrJO/justmenu.png"><img src="http://shrani.si/t/E/6k/3CBVQrJO/justmenu.jpg" style="border: 0px;" alt="Shrani.si"/></a>

from my git, as user

    cd ~/source/postbang && git pull
    cp -b ~/source/postbang/.config/openbox/menu.xml ~/.config/openbox/

scrot

<a href="http://shrani.si/f/3E/bN/3lmn3xpx/almost.png"><img src="http://shrani.si/t/3E/bN/3lmn3xpx/almost.jpg" style="border: 0px;" alt="Shrani.si"/></a>

### geany makeup

user
    
    cd ~/source/postbang && git pull
    cp -rb ~/source/postbang/.config/geany/* ~/.config/geany/

(copy recursive and backup if anything is going to be overwriten in destination)

<a href="http://shrani.si/f/L/xp/tdwm7S0/geany.png"><img src="http://shrani.si/t/L/xp/tdwm7S0/geany.jpg" style="border: 0px;" alt="Shrani.si"/></a>

### conky

sudo

    apt-get install conky

add conky to ~/.config/openbox/autostart

    nitrogen --restore & compton & tint2 & conky & 

scrot

<a href="http://shrani.si/f/3F/Gv/aUcIXtT/prettyclean.png"><img src="http://shrani.si/t/3F/Gv/aUcIXtT/prettyclean.jpg" style="border: 0px;" alt="Shrani.si"/></a>

### gimp

sudo

    apt-get install gimp gimp-plugin-registry

### 3rd party apps

Blender, Sublime, fadein <http://www.fadeinpro.com/page.pl?content=download>

### Some hotkeys

    super + (super is right cmd on osx)
    t       terminator
    w       browser
    f       file manager
    e       text editor
    v       alsamixer
    space   obmenu
    tab     combined-menu

    alt +
    f2      gmrun



### disabling services

sudo 

    systemctl disable ModemManager.service


### custom scripts 
Which ones would make sense
(duh,ura,radio,r128,play,rplay,gaga?,invaders,r128?,r128graph,snapConfigs?,makethumbwd,myip)

Problems to fix:
- Add that flat-pinkMarker theme
- xdg? seems to be making a Desktop folder in user home ....

Note: to be continued ...


[gimmick:theme](amelia)


[gimmick:theme](amelia)

<img src="https://raw.githubusercontent.com/brontosaurusrex/titles/master/example.png" style="border:0;">

# Jessie from netinstall = postbang

## What is this

- Setting up a simple but effective system using openbox as your window manager with Debian stable as base
- Hugely influenced by now defunct CrunchBang
- Consideration to stay Debian all the way and not invent any more abstraction layers
- Learning tool to get you closer to the state where you can roll your own/personalized Linux desktop system
- Reaching that next level of geekness

Attention: This is brainstorming and execution in single step, so consider this document experimental.

## install Debian Jessie

Base is what we want and nothing else

<https://www.debian.org/devel/debian-installer>
or with non-free stuff  
<http://cdimage.debian.org/cdimage/unofficial/non-free/cd-including-firmware/>

Basic eth0 `/etc/network/interfaces`

    auto eth0
    iface eth0 inet dhcp

as su

    apt-get install openbox xserver-xorg xinit
    apt-get install terminator rxvt-unicode vim vim-gtk thunar tint2 geany gmrun
    apt-get install htop mc inxi xsettingsd i3lock

Note: They say that vim-gtk gives ability to yank (copy) text to x clipboard (either "*y or "+y should work)  
Note: terminator and thunar will bring in lots of stuff    
Note: Installing xsettingsd stoped tint2 to fill .xsession-errors <https://code.google.com/p/xsettingsd/>

p.s. Possible future replacement for gmrun is called rofi

    rofi -show run -lines 3 -font "Cuprum 11" -bw 0 -separator-style none -width 7 -hide-scrollbar -padding 1 

Colors can be set in .Xresources

## sudo

as su

    apt-get install sudo
    adduser <username> sudo

reboot

## virtualbox related

Insert Guest additions cd, it should appear in /media/cdrom

    cd
    mkdir /home/user/tmp
    (copy the cd to tmp)

sudo

    apt-get install dkms
    cd /home/user/tmp
    ./VBoxLinuxAdditions.run
    reboot

## cont 

sudo 

    apt-get install gvfs gvfs-backends sshfs

(should provide you with the working "Browse Network" option in thunar.)

configure vim (if in vbox, now might be a good time to enable bidirectional clipboard)

sudo

    vi /etc/vim/vimrc.local

(that is a new file), paste:

    set number
    syntax on
    set ruler

## network manager

sudo

    apt-get install network-manager-gnome
    sudo vi /etc/network/interfaces
    
paste

    # This file describes the network interfaces available on your system
    # and how to activate them. For more information, see interfaces(5).

    # The loopback network interface
    auto lo
    iface lo inet loopback
    
configure network manager

    vi /etc/NetworkManager/NetworkManager.conf

change ifupdown managed to true.

    sudo service network-manager restart

as user

    openbox --exit
    startx

    tint2 &

The network applet should show up on tint2 (Exec name is nm-applet and it should get autostarted from entrie in /etc/xdg/autostart/).

<a href="http://shrani.si/f/2o/PM/1aLoN4EY/network.png"><img src="http://shrani.si/t/2o/PM/1aLoN4EY/network.jpg" style="border: 0px;" alt="Shrani.si"/></a>

## openbox autostart, basic

    cd
    vi .config/openbox/autostart 

(a new file), paste something like

    tint2 &

test

    openbox --exit
    startx

and tint2 should sit there.

## terminator

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

Note: You will probably want to adjust the `size = 850, 400` part of the terminator config.

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

## default xdg directories

    cd /etc/xdg

sudo

    cp user-dirs.defaults user-dirs.defaults.bak
    vi user-dirs.defaults

paste

    DESKTOP=downloads
    # we need to define desktop due to firefox weird behaviour.
    DOWNLOAD=downloads
    DOCUMENTS=documents
    MUSIC=music
    PICTURES=images
    VIDEOS=videos

user

    cd 
    cd .config
    rm user-dirs.*
    LC_ALL=C xdg-user-dirs-update
    cd
    ls

voila. More <https://wiki.archlinux.org/index.php/Xdg_user_directories#Creating_custom_directories>



## usability & preparing for scripts

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

Update: <https://forums.bunsenlabs.org/viewtopic.php?id=578>

a browser and an image viewer and a command line unpacker, sudo

    apt-get install iceweasel viewnior unp

I would add an Adblock Plus extension right away

<a href="http://shrani.si/f/2E/MY/4dE12bs6/iceweasel.png"><img src="http://shrani.si/t/2E/MY/4dE12bs6/iceweasel.jpg" style="border: 0px;" alt="Shrani.si"/></a>

note: Scrollbar anywhere and dwm wm do not like each other.

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

## makeup

### openbox, openbox menu logic and beauty

    cp -b ~/source/postbang/.config/openbox/rc.xml ~/.config/openbox/
    openbox --restart

Should give you:
- bronto-manual tiling, try `mod+a,1` or `mod+a,2` or `mod+a,enter` (or `alt+y,x,c` for some more used ones)
- decoration rules (mpv is undecorated)
- additional mice behaviour, for example middle mouse button on tint2 will kill that app
- and so on

### tint2

as user

    cp -b ~/source/postbang/.config/tint2/tint2rc ~/.config/tint2/

(-b stands for: backup dest file, btw)

    mkdir ~/.fonts/
    cp ~/source/postbang/.fonts/* ~/.fonts/
    killall tint2
    tint2 &

Everything should look like this now

<a href="http://shrani.si/f/3O/Yh/4QvwRs8F/likethis.png"><img src="http://shrani.si/t/3O/Yh/4QvwRs8F/likethis.jpg" style="border: 0px;" alt="Shrani.si"/></a>

### icons

sudo

    apt-get install lxappearance lxappearance-obconf librsvg2-bin zenity imagemagick

user

    cd
    mkdir .icons
    cd source
    git clone https://github.com/jcubic/Clarity
    cp -r ./Clarity/ ~/.icons/
    cd ~/.icons/Clarity
    ./configure
    make

Copy a custom black theme from my git

    cp ~/source/postbang/.icons/Clarity/src/template_postbang.svg ~/.icons/Clarity/src/
    cd ~/.icons/Clarity/
    ./change-theme

Select postbang theme and run ... 

warning: building may take a long time on a slow machine (like asus eee)

then 

    cp ~/source/postbang/.gtkrc-2* ~

p.s. some other interesting icon sets:  
<http://pobtott.deviantart.com/art/Any-Color-You-Like-175624910>  
<https://github.com/varlesh/papirus-suite/files/263230/papirus-gtk-icon-theme-1.0.tar.gz>


### themes

    cd
    mkdir .themes
    cd .themes
    cp -r ~/source/postbang/.themes/* .

sudo

    apt-get install dmz-cursor-theme
    apt-get install gtk2-engines-pixbuf gtk2-engines-murrine gtk2-engines-oxygen gtk2-engines-xfce --no-install-recommends

user

    cp -b ~/source/postbang/.config/openbox/rc.xml ~/.config/openbox/

logout, login and it could look like this:

<a href="http://shrani.si/f/2e/8N/2kLZW6BM/themed.png"><img src="http://shrani.si/t/2e/8N/2kLZW6BM/themed.jpg" style="border: 0px;" alt="Shrani.si"/></a>

p.s. some other interesting themes:  
<https://github.com/horst3180/Arc-theme>  
(Combination of 'Arc' for gtk and 'Bang Dark Green Bronto' for openbox is used by default here)  
<https://github.com/tista500/Adapta>  
(Adapta & 'Bang Dark Green Bronto Adapta')  

<a href="http://shrani.si/f/D/6Y/1O2xtgRS/arcbang.png"><img src="http://shrani.si/t/D/6Y/1O2xtgRS/arcbang.jpg" style="border: 0px;" alt="Shrani.si"/></a>

### some more fonts and gdebi

sudo

    apt-get install --no-install-recommends fonts-dejavu fonts-droid ttf-freefont ttf-liberation gdebi

### wallpaper

sudo

    apt-get install nitrogen

user, change the autostart to include nitrogen

    vi ~/.config/openbox/autostart

make it look like:

    nitrogen --restore & tint2 &

user

    cd && cd images && mkdir wallpapers
    cd && cd source/postbang && git pull
    cp ~/source/postbang/images/wallpapers/*.png ~/images/wallpapers/
    nitrogen

Note: The magic background color is `#32383B`

Add directory images/wallpapers, select one of the wallpapers ....
(btw: as with most of this guide this is single-user approach,)

p.s. luxRender Debian-like swirl 
<http://shrani.si/f/44/HW/4c0Czz2S/swirllux.png>
<http://shrani.si/f/2q/10a/11vEdHZ2/swirldali3post28bpc.png>

### obmenu and obapps

sudo

    apt-get install obmenu
    
user

    cd 
    cd source
    wget sourceforge.net/projects/obapps/files/obapps-0.1.7.tar.gz
    unp obapps-0.1.7.tar.gz 
    cd obapps-0.1.7/
    
sudo    
    
    python setup.py install
    apt-get install python-xlib python-wxtools

user

    obapps & obmenu

## openbox menu, the king of the castle

Came up with this, totally "passive";

<img src="http://shrani.si/f/E/6k/3CBVQrJO/justmenu.png">

sudo

    apt-get install xfce4-appfinder --no-install-recommends

from my git, as user

    cd ~/source/postbang && git pull
    cp -b ~/source/postbang/.config/openbox/menu.xml ~/.config/openbox/

scrot

<a href="http://shrani.si/f/3E/bN/3lmn3xpx/almost.png"><img src="http://shrani.si/t/3E/bN/3lmn3xpx/almost.jpg" style="border: 0px;" alt="Shrani.si"/></a>

## opening openbox menu with a button on tint2 (optional)

Add launcher to `tint2rc`, like

    launcher_item_app = /usr/share/applications/menu.desktop

make /usr/share/applications/menu.desktop with content like

    [Desktop Entry]
    Encoding=UTF-8
    Name=Tint2 Openbox Menu
    Comment=Tint2 Openbox Menu Hack
    X-GNOME-FullName=Openbox Menu
    Exec=xdotool key super+space
    Terminal=false
    X-MultipleArgs=false
    Type=Application
    Icon=/home/ticho/images/debian.png
    Categories=Menu;
    MimeType=
    StartupNotify=true
    
install xdotool and make sure that [png icon](https://raw.githubusercontent.com/brontosaurusrex/postbang/master/images/debian.png "This is the one I'am using") is on the correct spot. Should look like this:

<a href="http://shrani.si/f/t/Om/QnmtcB7/openboxmenubutton.png"><img src="http://shrani.si/t/t/Om/QnmtcB7/openboxmenubutton.jpg" style="border: 0px;" alt="Shrani.si"/></a>  

## geany makeup

user
    
    cd ~/source/postbang && git pull
    cp -rb ~/source/postbang/.config/geany/* ~/.config/geany/

(copy recursive and backup if anything is going to be overwriten in destination)

<a href="http://shrani.si/f/L/xp/tdwm7S0/geany.png"><img src="http://shrani.si/t/L/xp/tdwm7S0/geany.jpg" style="border: 0px;" alt="Shrani.si"/></a>

## conky

sudo

    apt-get install conky-all

user

    cp -b ~/source/postbang/.conkyrc ~

add conky to ~/.config/openbox/autostart

    nitrogen --restore & tint2 & conky & 

scrot

<a href="http://shrani.si/f/3F/Gv/aUcIXtT/prettyclean.png"><img src="http://shrani.si/t/3F/Gv/aUcIXtT/prettyclean.jpg" style="border: 0px;" alt="Shrani.si"/></a>

<a href="http://shrani.si/f/1d/JI/1iOXK5j4/pretty.png"><img src="http://shrani.si/t/1d/JI/1iOXK5j4/pretty.jpg" style="border: 0px;" alt="Shrani.si"/></a>

## volti

Only if you really need to have that volume applet

sudo

    apt-get install volti

as user, add 

    volti &

to

    .config/openbox/autostart

<a href="http://shrani.si/f/2R/Rf/3kz8EUdL/volti2.png"><img src="http://shrani.si/t/2R/Rf/3kz8EUdL/volti2.jpg" style="border: 0px;" alt="Shrani.si"/></a>

or alternative: volumeicon-alsa <http://crunchbang.org/forums/viewtopic.php?pid=435857#p435857>


## gimp and inkscape

sudo

    apt-get install gimp gimp-plugin-registry inkscape

You will probably want to install some custom icon theme for gimp, like

<http://android272.deviantart.com/art/Flat-GIMP-icon-Theme-V-2-1-375010811>

<a href="http://shrani.si/f/U/E6/4JbrV9sC/gimp.png"><img src="http://shrani.si/t/U/E6/4JbrV9sC/gimp.jpg" style="border: 0px;" alt="Shrani.si"/></a>

Note: A nice script to compile the latest version of gimp:
<https://forums.bunsenlabs.org/viewtopic.php?id=446>

## utility

sudo

    apt-get install galculator gpick font-manager

(p.s. I know there was something cuter than font-manager ...)
offtopic, a nice online font thingy <http://bluejamesbond.github.io/CharacterMap/>


## 3rd party apps

Blender, Sublime, fadein <http://www.fadeinpro.com/page.pl?content=download>

## Some hotkeys

    super + (super is right cmd on osx)
    t       terminal
    w       browser
    f       file manager
    e       text editor
    v       alsamixer
    m       toggle mute
    s       toggle show desktop
    space   obmenu  
    tab     combined-menu

    alt +
    f2      gmrun
    
    super + a + 1,2,7,enter     windows move around (a lot more in rc.xml)
    alt + y,x,c,v               duplicated for most used actions                            
    
    +-------------+  +-------------+  +-------------+  +-------------+
    |------|      |  |      |------|  |   |-----|   |  |-------------|
    |------|      |  |      |------|  |   |-----|   |  |-------------|
    |------|      |  |      |------|  |   |-----|   |  |-------------|
    +-------------+  +-------------+  +-------------+  +-------------+
    left             right            center           fullwidth
    
    p.s. super + arrows is another interesting option.
    
### urxvt related 
    
    font-size extension (see ~./Xresources)
    alt +
    up      increase font size
    down    decrease font size
    left    reset font size
    
    tabbed extension (defaults)    
    shift+
    down    new tab
    left    go to left tab
    right   go to right tab
    
    ctrl+
    left    move tab to the left
    right   move tab to the right
    d       close tab 
    
    
### OSX as host, or how to use left cmd as super

VirtualBox/preferences/input/Virtual Machine/Host Key Combination < change this to Right cmd

<a href="http://shrani.si/f/23/Yj/3dibjEKn/keys.png"><img src="http://shrani.si/t/23/Yj/3dibjEKn/keys.jpg" style="border: 0px;" alt="keys"/></a>

## Darth Vader breathing, login sound

user

    cd ~/source/postbang/
    cp -R ./audio_fx/ ~
    vi ~/.config/openbox/autostart

add

    mpv ~/audio_fx/vader.mp3 &

so autostart might look like

    nitrogen --restore & tint2 & conky & volti &  mpv ~/audio_fx/vader.mp3 & 

## time, timezone

<https://wiki.archlinux.org/index.php/Time>

user

    timedatectl list-timezones | grep Yourcity

sudo

    apt-get install ntp
    timedatectl set-ntp true
    timedatectl set-timezone Europe/Yourcity

user

    timedatectl 


## disabling services

sudo 

    systemctl disable ModemManager.service


## custom scripts 

duh,radio,play,rplay,invaders,myip    
(Check postbang/bin and copy needed to user bin)

## login manager and autologin

sudo

    apt-get install lightdm

To autologin:

gksu 

    geany /etc/lightdm/lightdm.conf

and add

    autologin-user=someuser

under

    [SeatDefaults]

Note: Lightdm does not source .profile, so make sure that user bin path is added in `.xsessionrc` instead

## more stuff

sudo

    apt-get install atril vlc smplayer catfish
    apt-get install --no-install-recommends engrampa 

note: atril is mate version of evince & engrampa is mate version of file-roller
note: something about .config/Trolltech.conf and qt4-qtconfig (qtconfig)

Neither vlc, nor smplayer are working correctly in vbox enviroment for me, possibly missing stuff.

## flash

### the new - better way (Yes it will work in iceweasel as well)
    
sudo 

    apt-get install browser-plugin-freshplayer-pepperflash
    
Make sure backports are enabled in `/etc/apt/sources.list`

### the old way (Only if the 'new - better way' would not want to cooperate for some reason)

sudo 

    apt-get install flashplugin-nonfree

to update flash, sudo

    update-flashplugin-nonfree --install

## things

### ms fonts

sudo

    apt-get install ttf-mscorefonts-installer
    
should provide nicer looking web pages.

### wbar (running dock without compositor), optional

sudo

    apt-get install wbar wbar-config

<a href="http://shrani.si/f/q/VV/hJrkXOD/wbar.png"><img src="http://shrani.si/t/q/VV/hJrkXOD/wbar.jpg" style="border: 0px;" alt="wbar scrot"/></a>

notes:

- you will probably want to start this via openbox/autostart with some delay, so remember to remove `/etc/xdg/autostart/wbar.desktop`
- wbar does not autohide and will not take place on desktop < fix it with some openbox margins (bottom in this case)
- wbar does not behave very friendly in multi-monitor enviroments.
- wbar config is local floating point character dependant (usually either , or .)

bronto-moded `.wbar` should be in this gits root.  
Update: provided .wbar now uses intermediate script called launchee, which should provide a bit smarter behaviour than just starting the app again.  
Update: there is now .wbar.papirus (Nice size seems to be 42px on smaller screens and 46px on larger)

### notify-send and replacing notification thingy

sudo 

    apt-get install libnotify-bin
    apt-get remove notification-daemon
    apt-get install dunst

to test, user

    notify-send "My sweater is black ......... not!"

bronto-moded dunstrc should be in `.config/dunst/dunstrc`

<a href="http://shrani.si/f/15/Do/WW1ZmZ6/dunst.png"><img src="http://shrani.si/t/15/Do/WW1ZmZ6/dunst.jpg" style="border: 0px;" alt="Shrani.si"/></a>

### various system tools (optional)

sudo 

    apt-get install e2fsprogs xfsprogs reiserfsprogs reiser4progs jfsutils ntfs-3g fuse gvfs gvfs-fuse fusesmb

from : <http://crunchbang.org/forums/viewtopic.php?id=38994> (wally)

### keyboard layout changer (from command line)

#### set keyboard layout

    setxkbmap si
    setxkbmap us
    setxkbmap de

#### read layout

    setxkbmap -query # or
    setxkbmap -query | grep layout # or
    setxkbmap -query | grep layout | tr -d 'layout:' | tr -d ' '
    
### languages

<https://wiki.debian.org/Locale> (Severly out of date, but you should get an idea)

## Bleeding edge

### Libreoffice

sudo

    apt-get install -t jessie-backports libreoffice libreoffice-gtk libreoffice-style-breeze

In writer select Tools/options/View - and set Icon style to Breeze.

### inkscape

sudo

    apt-get install -t jessie-backports inkscape

### mpv

    sudo apt-get install libncurses5-dev liblua5.1-0.dev devscripts equivs

git clone the mpv-build git <https://github.com/mpv-player/mpv-build> and cd to source dir
(remove any mpv-build-deps**.deb)

    mk-build-deps
    sudo dpkg -i mpv-build-deps(TAB).deb 
    sudo apt-get install -f

    ./rebuild -j2

(replace 2 with # of cores)

after everything happens you can just copy mpv-build/mpv/build/mpv to ~/bin.

Interesting, untested: <http://2ion.github.io/mpv-bash-completion/>

### gimp    

Read here <https://forums.bunsenlabs.org/viewtopic.php?id=446>

### blender

    cd
    mkdir apps
    cd apps && mkdir blender
    
from <https://builder.blender.org/download/> wget the correct version and unpack.

## Problems to fix/things to add/modify:
- none.

This is probably pretty much it.

## the magic install-all apt-get lines

See the example /etc/apt/sources.list in this git before running this.

sudo

    apt-get install openbox xserver-xorg xinit terminator vim thunar tint2 geany gmrun htop mc inxi xsettingsd i3lock sudo dkms network-manager-gnome xfonts-terminus git curl mpv youtube-dl mediainfo mkvtoolnix alsa-base alsa-tools alsa-tools-gui alsa-utils alsa-oss alsamixergui libalsaplayer0 iceweasel viewnior unp lxappearance lxappearance-obconf librsvg2-bin zenity imagemagick dmz-cursor-theme nitrogen obmenu python-xlib python-wxtools conky volti gimp gimp-plugin-registry inkscape galculator gpick font-manager rxvt-unicode catfish atril 
    
    apt-get install flashplugin-nonfree ttf-mscorefonts-installer
    
    apt-get install gtk3-engines-*
    
    apt-get install --no-install-recommends fonts-dejavu fonts-droid ttf-freefont ttf-liberation gdebi gtk2-engines-pixbuf gtk2-engines-murrine gtk2-engines-oxygen gtk2-engines-xfce xfce4-appfinder engrampa
    
    apt-get install -t jessie-backports libreoffice libreoffice-gtk libreoffice-style-breeze
    
    apt-get install ntp
    
    apt-get install lightdm
    
    apt-get install xdotool xrandr tree
    
    # optional
    apt-get install e2fsprogs xfsprogs reiserfsprogs reiser4progs jfsutils ntfs-3g fuse gvfs gvfs-fuse fusesmb

## Real hardware

### some i3 desktop

<a href="http://shrani.si/f/1/ML/2e0k1KMM/realhardwarei3.png"><img src="http://shrani.si/t/1/ML/2e0k1KMM/realhardwarei3.jpg" style="border: 0px;" alt="Shrani.si"/></a>

### some i5 desktop

<a href="http://shrani.si/f/2Z/e3/1NwPW8AG/i5.png"><img src="http://shrani.si/t/2Z/e3/1NwPW8AG/i5.jpg" style="border: 0px;" alt="Shrani.si"/></a>

Compton appears to actually fix tearing issues on this machine:

    compton --backend glx --paint-on-overlay
    # or one running in virtualbox (with some shadows)
    compton -cCG --paint-on-overlay
    # with some deep shadows and transparency
    compton -cCG --paint-on-overlay -o 0.9 -r 30 -l -9 -t -9 -i 0.8 -e 0.75 -m 0.9
    
<a href="http://shrani.si/f/1I/yv/DHvj8ou/alotofcompton.png"><img src="http://shrani.si/t/1I/yv/DHvj8ou/alotofcompton.jpg" style="border: 0px;" alt="Shrani.si"/></a>

### asus eee

<a href="http://shrani.si/f/17/2c/4znL9NdL/eee.png"><img src="http://shrani.si/t/17/2c/4znL9NdL/eee.jpg" style="border: 0px;" alt="Shrani.si"/></a>

sudo

    apt-get install nvidia-detect

and so on..., when it craps out, add

/etc/X11/xorg.conf.d/20-nvidia.conf

    Section "Device"
            Identifier "My GPU"
            Driver "nvidia"
    EndSection

and reboot.

### arch

With slight reconfiguration the same make-up layer might work with other distros

<a href="http://shrani.si/f/3s/dW/iXkZZPh/reallyarchy.png"><img src="http://shrani.si/t/3s/dW/iXkZZPh/reallyarchy.jpg" style="border: 0px;" alt="Shrani.si"/></a>

## Issues?

You may explain yourself here (Or better correct me)  
<https://github.com/brontosaurusrex/postbang/issues>

## brought to You by

<a href="http://brontosaurusrex.github.io"><img src="https://raw.githubusercontent.com/brontosaurusrex/postbang/master/logos/logoPost.png" style="border:0;"></a>





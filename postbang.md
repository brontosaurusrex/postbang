# Jessie from netinstall (postbang)

Manual approach to post-banging the Debian Jessie, work in progress

Attention: This is brainstorming and execution in single step, so consider this document experimental.


## install Debian Jessie

Base is what we want and nothing else

<https://www.debian.org/devel/debian-installer>
or with non-free stuff  
<http://cdimage.debian.org/cdimage/unofficial/non-free/cd-including-firmware/>

Basic eth0 /etc/network/interfaces

    auto eth0
    iface eth0 inet dhcp

as su

    apt-get install openbox xserver-xorg xinit
    apt-get install terminator rxvt-unicode vim thunar tint2 geany gmrun
    apt-get install htop mc inxi xsettingsd i3lock

Note: terminator and thunar will bring in lots of stuff    
Note: Installing xsettingsd stoped tint2 to fill .xsession-errors <https://code.google.com/p/xsettingsd/>

## sudo

as su

    apt-get install sudo
    adduser <username> sudo

reboot ?

Hint: if there is no root password entered in the Debian installer, sudo is installed and the first user created (ie, in the installer) is added to the "sudo" group automatically.
<http://crunchbang.org/forums/viewtopic.php?pid=430287#p430287>

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

(should provide you with the working "Browse Network" thingy in thunar, however that does not work as expected)

sudo

    apt-get install compton

(optional, personally not using compton)

to test

    compton & tint2 &

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

as user

    openbox --exit
    startx

    compton & tint2 &

The network applet should show up on tint2

    scrot -d 5

<a href="http://shrani.si/f/2o/PM/1aLoN4EY/network.png"><img src="http://shrani.si/t/2o/PM/1aLoN4EY/network.jpg" style="border: 0px;" alt="Shrani.si"/></a>

## openbox autostart, basic

    cd
    vi .config/openbox/autostart 

(a new file), paste something like

    compton & tint2 &

test

    openbox --exit
    startx

and compton and tint2 should sit there.

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

### infinality font stuff

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

Note: If this infinality thing doesn't want to cooperate, skip it, font rendering is pretty good in jessie by default.

### openbox, openbox menu logic and beauty

    cp -b ~/source/postbang/.config/openbox/rc.xml ~/.config/openbox/
    openbox --restart

Should give you:
- bronto-manual tiling, try mod+a,1 or mod+a,2 or mod+a,enter
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

p.s. another nice icon set:
<http://pobtott.deviantart.com/art/Any-Color-You-Like-175624910>


### themes

    cd
    mkdir .themes
    cp -r ~/source/postbang/.themes/FlatStudioLightBronto ~/.themes
    cp -r ~/source/postbang/.themes/FlatStudioBrontoMod/ ~/.themes

sudo

    apt-get install dmz-cursor-theme
    apt-get install gtk2-engines-pixbuf gtk2-engines-murrine gtk2-engines-oxygen gtk2-engines-xfce --no-install-recommends

user

    cp -b ~/source/postbang/.config/openbox/rc.xml ~/.config/openbox/

logout, login and it could look like this:

<a href="http://shrani.si/f/2e/8N/2kLZW6BM/themed.png"><img src="http://shrani.si/t/2e/8N/2kLZW6BM/themed.jpg" style="border: 0px;" alt="Shrani.si"/></a>

### some more fonts and gdebi

sudo

    apt-get install --no-install-recommends fonts-dejavu fonts-droid ttf-freefont ttf-liberation gdebi

### wallpaper

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

Note: The magic background color is #32383B

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

Add launcher to tint2rc, like

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

    nitrogen --restore & compton & tint2 & conky & 

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


## utility

sudo

    apt-get install galculator gpick font-manager

(p.s. I know there was something cuter than font-manager ...)
offtopic, a nice online font thingy <http://bluejamesbond.github.io/CharacterMap/>


## 3rd party apps

Blender, Sublime, fadein <http://www.fadeinpro.com/page.pl?content=download>

## Some hotkeys

    super + (super is right cmd on osx)
    t       terminator
    w       browser
    f       file manager
    e       text editor
    v       alsamixer
    m       toggle mute
    n       xfce4-appfinder
    s       toggle show desktop
    space   obmenu  
    tab     combined-menu

    alt +
    f2      gmrun

## Darth Vader breathing, login sound

user

    cd ~/source/postbang/
    cp -R ./audio_fx/ ~
    vi ~/.config/openbox/autostart

add

    mpv ~/audio_fx/vader.mp3 &

so autostart might look like

    nitrogen --restore & compton & tint2 & conky & volti &  mpv ~/audio_fx/vader.mp3 & 

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

Note: Lightdm does not source .profile, so make sure that user bin path is added in .xsessionrc instead

## more stuff and flash, optional

sudo

    apt-get install atril vlc smplayer catfish
    apt-get install --no-install-recommends engrampa 

note: atril is mate version of evince & engrampa is mate version of file-roller
note: something about .config/Trolltech.conf and qt4-qtconfig (qtconfig)

Neither vlc, nor smplayer are working correctly in vbox enviroment for me, possibly missing stuff.

sudo 

    apt-get install flashplugin-nonfree

to update flash, sudo

    update-flashplugin-nonfree --install

## things

### opensnap (optional)

Opensnap brings the Aero Snap feature to Openbox.
<https://github.com/lawl/opensnap>

sudo 

    apt-get install wmctrl

user

    cd ~/source/postbang/debs

sudo

    dpkg -i opensnap*.deb

(or compile your own)

test

    opensnap &

Move your windows around and check if opensnap gives you any errors and so on.
If everything seems peachy, add opensnap & to your autostart.

scrot

<a href="http://shrani.si/f/4/Rs/1jQqz1kh/opensnap.png"><img src="http://shrani.si/t/4/Rs/1jQqz1kh/opensnap.jpg" style="border: 0px;" alt="Shrani.si"/></a>

### notify-send and replacing notification thingy

sudo 

    apt-get install libnotify-bin
    apt-get remove notification-daemon
    apt-get install dunst

to test, user

    notify-send "My sweater is black ......... not!"

bronto-moded dunstrc should be in .config/dunst/dunstrc

<a href="http://shrani.si/f/15/Do/WW1ZmZ6/dunst.png"><img src="http://shrani.si/t/15/Do/WW1ZmZ6/dunst.jpg" style="border: 0px;" alt="Shrani.si"/></a>

### various system tools

sudo 

    apt-get install e2fsprogs xfsprogs reiserfsprogs reiser4progs jfsutils ntfs-3g fuse gvfs gvfs-fuse fusesmb

from : <http://crunchbang.org/forums/viewtopic.php?id=38994> (wally)

### nemo (behaves nicely with gnome-search-tool)

note: this did not make it, I'am back to thunar.

sudo

    apt-get install nemo dconf-tools

user

    dconf write /org/nemo/desktop/show-desktop-icons false

or use dconf-editor.

<http://shrani.si/f/1X/zx/2kVbese0/nemo.png>

## Problems to fix/things to add/modify:
- xfce4-power-manager?
- png wallpapers are fat, test bpg lossless (perhaps 8bpc 4:2:2)
- automounting usb drives? android phones?
- document custom scripts?
- add weechat config?
- disable root?
- when things settle down, do a script that installs everything.
- <s>theme errors</s> < seems like they mostly fixed themselves
- <s>better name than "postbang": icebreaker? iceberg?</s> 
- <s>gksu</s> < seems to be working
- <s>Add that flat-pinkMarker theme</s> < done
- <s>xdg? seems to be making a Desktop folder in user home ....</s> < fixed, was an iceweasel behaviour

Note: This is probably pretty much it.

## the magic install-all apt-get lines

sudo

    apt-get install openbox xserver-xorg xinit terminator vim thunar tint2 geany gmrun htop mc inxi xsettingsd i3lock sudo dkms compton network-manager-gnome xfonts-terminus git curl mpv youtube-dl mediainfo mkvtoolnix alsa-base alsa-tools alsa-tools-gui alsa-utils alsa-oss alsamixergui libalsaplayer0 iceweasel viewnior unp lxappearance lxappearance-obconf librsvg2-bin zenity imagemagick dmz-cursor-theme nitrogen obmenu python-xlib python-wxtools conky volti gimp gimp-plugin-registry inkscape galculator gpick font-manager rxvt-unicode catfish atril e2fsprogs xfsprogs reiserfsprogs reiser4progs jfsutils ntfs-3g fuse gvfs gvfs-fuse fusesmb

    apt-get install flashplugin-nonfree

    apt-get install gtk3-engines-* ?

    apt-get install --no-install-recommends fonts-dejavu fonts-droid ttf-freefont ttf-liberation gdebi gtk2-engines-pixbuf gtk2-engines-murrine gtk2-engines-oxygen gtk2-engines-xfce xfce4-appfinder engrampa

    apt-get install libreoffice libreoffice-gtk

    apt-get install ntp ?

    apt-get install lightdm

    apt-get install xdotool xrandr tree 

Infinality related stuff skipped here.

## Real hardware

### some i3 desktop

<a href="http://shrani.si/f/1/ML/2e0k1KMM/realhardwarei3.png"><img src="http://shrani.si/t/1/ML/2e0k1KMM/realhardwarei3.jpg" style="border: 0px;" alt="Shrani.si"/></a>

"Problems" were a. video playback related, tearing. I did not dig into compton setting this time, just removed it from autostart. b. gtk3 apps like catfish do look fugly. 

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

### Brought to you by:

<img src="http://shrani.si/f/1w/129/4XbHQNtV/brontolabs.png">

[gimmick:theme](yeti)

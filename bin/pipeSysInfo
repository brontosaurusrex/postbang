#!/bin/bash

# pipeSysInfo

# call from .config/openbox/menu.xml like
#        <menu id="sysInfo" label="sysInfo" execute="pipeSysInfo pipe" />

# or from cli: pypeSysinfo

# required awk, bc

if [[ "$1" == "pipe" ]]
then
    pipe="true"
fi

awk_round () {
    awk 'BEGIN{printf "%."'$1'"f\n", "'$2'"}'
}

# cpu usage in percents 
tmp=$(ps -A -o pcpu | tail -n+2 | paste -sd+ | bc)
cpu=$(awk_round 0 "$tmp")

# mem usage 245M/15G 
# https://unix.stackexchange.com/questions/119126/command-to-display-memory-usage-disk-usage-and-cpu-load
read -r memslash mem <<< $(free -m | awk 'NR==2{printf "%s/%sM %.0f%%", $3,$2,$3*100/$2 }')
mem="${mem//%}"

# /home disk usage in percents and free space in Gb (32% used, 65G free)
read -r _ _ _ availdisk usedisk _ <<< $(df -h ~ | tail -1)
usedisk="${usedisk//%}"


## upload/download speed (this needs a delay to get the delta calculus, so script will lag a bit)
#awk '{if(l1){print ($2-l1)/1024"kB/s",($10-l2)/1024"kB/s"} else{l1=$2; l2=$10;}}' <(tail -1 /proc/net/dev) <(sleep 1; tail -1 /proc/net/dev
# ugly stuff ahead
# disabled for now
#read -r download upload <<< $(awk '{if(l1){print ($2-l1)/1024,($10-l2)/1024} else{l1=$2; l2=$10;}}' <(grep enp10 /proc/net/dev) <(sleep 1; grep enp10 /proc/net/dev))
#download=$(awk_round 0 "$download")
#upload=$(awk_round 0 "$upload")

# host name, distroname
rel=$(lsb_release -ds 2>/dev/null || cat /etc/*release 2>/dev/null | head -n1 || uname -om)
kernel=$(uname -r)
distro="$HOSTNAME @ $rel"

# uptime and date
#read -r _ _ uptime _ _ _ _ _ <<< $(uptime)
uptime=$(uptime -p)
date=$(date)

if [[ "$pipe" == "true" ]]
then
# pipe display
cat << EOFMENU
<openbox_pipe_menu>
<item label="$cpu% cpu used" />
<item label="$memslash, $mem% mem used" />
<item label="$availdisk disk free, $usedisk% ~ used" />
<item label="$distro">
    <action name="Execute">
        <execute>scrot -q 0 -d 7 -e 'mv $f ~'</execute>
    </action>
</item>
</openbox_pipe_menu>
EOFMENU

# disabled
# <item label="dl $download, up $upload kB/s, $uptime" />

else
# normal cli display
echo "$cpu% cpu used"
echo "$memslash, $mem% mem used"
echo "$availdisk disk free, $usedisk% ~ used"
echo "$distro"
fi

# disabled
# echo "dl $download, up $upload kB/s, $uptime"

##########################################################################
# wanted (openbox pipeMenu display)

#       13:13 - 17.jun 2017
#       ||||   |||||||||||||||||||||||||||||| cpu 4%
#       |   ||||||||||||||||||||||||||||||||| mem 245M/15G used
#       ||||||||||||||||||||||   |||||||||||| disk ~ 32% used, 65G free
#       dl 1.1M, up 34k, uptime: 2h, node: i5

# when clicked copy to clipboard?

# even better

#       ||||   |||||||||||||||||||||||||||||| cpu
#       |   ||||||||||||||||||||||||||||||||| mem
#       ||||||||||||||||||||||   |||||||||||| 65G disk free
#       dl 14, up 248 kB/s

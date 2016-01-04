# Debian ssh/sftp & minidlna strežnik

## system

	System:    Host: debianserver Kernel: 3.16.0-4-686-pae i686 (32 bit) Console: tty 1 Distro: Debian GNU/Linux 8
	Machine:   Mobo: ASUSTeK model: A7V600-X v: REV 1.xx Bios: Award v: ASUS A7V600-X 1002 date: 11/12/2003
	CPU:       Single core AMD Athlon XP 2800+ (-UP-) cache: 512 KB clocked at 2070 MHz
	Network:   Card: VIA VT6102 [Rhine-II] driver: via-rhine
	           IF: eth0 state: unknown speed: 100 Mbps duplex: full mac: 00:0e:a6:9b:3d:d1
	Drives:    HDD Total Size: 443.0GB (14.8% used) ID-1: /dev/sda model: ST3160815A size: 160.0GB
	           ID-2: /dev/sdc model: Maxtor_6Y120L0 size: 122.9GB ID-3: /dev/sdb model: WDC_WD1600JB size: 160.0GB
	Partition: ID-1: / size: 406G used: 62G (16%) fs: ext4 dev: /dev/dm-0

Fizično prisotni trije diski, ki so "zvezani" s pomočjo LVM-ja, tako da so vidni kot en disk.

## strežnika (ssh/sftp/minidlna)

Preko ssh konekcije je možen popolen lupinski dostop do sistema (npr client putty)

	ssh ticho@ip # password: vprašaj

Preko sftp protokola je možno prenašanje datotek (client filezilla ali winscp), uporabnik: ticho, pass: vprašaj.

Minidlna je skonfiguriran tako, da avtomatsko najde datoteke v /home/ticho/videos, images in music.

## minidlna

Občasno bo potrebno minidlna ponovno zagnati:

	sudo service minidlna force-reload

status minidlna strežnika

	systemctl status minidlna.service

## system update

Občasna posodobitev system se zažene z ukazom "up", obremenitev sistema je možno spremljati z ukazom "htop".  
Med delovanje minidlna strežnika je obremenitev procesorja zelo nizka (~1%), med pretokom datotek na/iz sistem pa zelo visoka (100%).

## disk

	df -h 	# koliko je še prostora
	ncdu . 	# koliko diska zasedajo posamezne datoteke/direktoriji
	duh . 	# podobno

	





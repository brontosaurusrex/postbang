# dd an iso on osx

    diskutil list

find the usb drive and remember the /dev/disk# path.

    diskutil unmountDisk /dev/disk#

now type

   dd if=debian.iso of=/dev/disk# bs=1m

(bs=8192 should also work)

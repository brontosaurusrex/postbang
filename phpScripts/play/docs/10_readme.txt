FLVPLAYER.PHP

NOTE:
this docs are really not uptodate.

WHATIS: 
This is a (simple) php script that enables playback of some flash compatible video files from
a folder. It is targeted at footage review type of operation, more than typical web usage.

WHATISNOT: 
a youtube replacement

DOWNLOAD: 
no static url at the moment

INSTALL 
notes and thumbs are optional, the pulldown navigation is generated anyway, so minimal
install instructions would be: a. unpack to php5 enabled folder, b. drop some movie files into
movies subfolder. c. optional: name the movie files like: filename-x640y352.ext, so the script can
use this resolution hint to embed the file.

FEATURES: - autoadvance (server-side playlist kinda feature) and other cookie based prefs - thumbs
display (if there are any, possible to setup a bash script to make some) - simple regex compatible
filename filtering (see filter.txt)

BUGS: - none :p

DEMO: - none

FAKE STREAMING: to enable streaming mode you would put a string ’stream’ or ‘lighty’ into the file
name, example: http://somestuff.org/flashAVC_v4/?movie … 40y352.flv (stream: trigger php fake
streaming mode, lighty: triggers lighttpd streaming mode) p.s. i can’t get php streaming to work
with AVC streams muxed into flv container with this version of the player (try older versions if you
need that)…

don’t forget to inject metadata into your flv’s like: flvmdi file.flv /k to use lighttpd streaming
you would modify your lighty conf like: server.modules = ( ..., "mod_flv_streaming", ... )
flv-streaming.extensions = ( ".flv" )

edit: streaming functionality is totaly not tested in later versions, use at your own risk.

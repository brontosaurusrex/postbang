set ruler
syntax on

" expandtab  = use space instead of tab
" tabstop    = number of spaces when tab is pressed
" shiftwidth = number of spaces inserted for indentation 
set tabstop=4 shiftwidth=4 expandtab

" function key F10 is use to go to end of line
nmap <F10> $

" up down faster, but still using j and k (ctrl + key)

nmap <C-j> 10j
nmap <C-k> 10k

" Use mouse for visual selection mode as well, use shift + mouse to get primary clipboard selections
set mouse=a

" turn off swp and stuff that may clutter git
set nobackup
set noswapfile




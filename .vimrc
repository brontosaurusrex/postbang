" ignore case when /searching
set ignorecase 
set number
syntax on
set ruler
set mouse=a

" Search as you type.
set incsearch

" things that clutter git
set nobackup
set noswapfile

" ident
set tabstop=4 shiftwidth=4 expandtab

" f10 to go to end of line (so you have 0 and F10)
nmap <F10> $

" Change color when in insert mode
" http://vim.wikia.com/wiki/Change_statusline_color_to_show_insert_or_normal_mode
" first, enable status line always
set laststatus=2
" now set it up to change the status line based on mode
if version >= 700
  au InsertEnter * hi StatusLine term=reverse ctermbg=5 gui=undercurl guisp=Magenta
  au InsertLeave * hi StatusLine term=reverse ctermfg=0 ctermbg=2 gui=bold,reverse
endif
" end Change color when in insert mode

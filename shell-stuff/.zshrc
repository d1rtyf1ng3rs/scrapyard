# Lines configured by zsh-newuser-install
HISTFILE=~/.histfile
HISTSIZE=4000
SAVEHIST=4000
setopt inc_append_history autocd hist_ignore_space hist_ignore_all_dups prompt_subst share_history sharehistory 
# End of lines configured by zsh-newuser-install
# The following lines were added by compinstall
zstyle :compinstall filename '/home/max/.zshrc'

autoload -Uz compinit
autoload -U colors && colors

compinit

zstyle ':completion:*:processes' command 'ps -ax' 
zstyle ':completion:*:*:kill:*:processes' list-colors '=(#b) #([0-9]#)*=0=01;32'
zstyle ':completion:*:*:kill:*' menu yes select
zstyle ':completion:*:kill:*'   force-list always

zstyle ':completion:*:processes-names' command 'ps -e -o comm='
zstyle ':completion:*:*:killall:*' menu yes select
zstyle ':completion:*:killall:*'   force-list always

#zstyle ':completion:*:dir-names' command 'ls -Al'
zstyle ':completion:*:*:cd:*'	menu yes select
zstyle ':completion:*:cd:*'	force-list always

zstyle ':completion:*:*:cat:*'  menu yes select
zstyle ':completion:*:cat:*' 	force-list always 
alias cll="clear; ls -l"
alias ll="ls -l"
alias lla="ls -al"
alias lls="du -hs * | sort -h"
#alias ggfunc='git'
#ggfunc() {
#	echo Start
#	if [[ "$1" = "gud" ]]; then
#		git fetch;
#	else
#		git $*;
#	fi;
#	echo Done
#}
# alias aperr="cat /var/log/apache2/error.log | tail"
# alias mailerr="cat /var/log/mail.err | tail"

bindkey '\e[A' history-beginning-search-backward
bindkey '\e]B' history-beginning-search-forward
bindkey ';5D' emacs-backward-word
bindkey ';5C' emacs-forward-word
bindkey " " magic-space
bindkey '3~' magic-space
bindkey ';3D' backward-kill-line	# Alt-Left
bindkey ';3C' kill-line			# Alt-Right
bindkey '2~' kill-line
bindkey '^[^M' self-insert-unmeta

bindkey -s '5~' "clear; !!\n"

# Ctrl+W or Alt+BkSp - kill word from cursor to left
# Ctrl+R - history search; press Ctrl+R in searcn for 'find next'

# export WORDCHARS=''  # if upper two commands ain't stop at punctuation

source $HOME/.prompt
white_time_single_prompt

source $HOME/.spectrum
source $HOME/.bash_aliases
source $HOME/.shell_functions

alias -g пше="git"
alias -g сруслщ="checkout"
alias -g срусл="checkout"

source ~/.zsh/favconfig

alias grep='grep --color=auto'
alias egrep='egrep --color=auto'
alias fgrep='fgrep --color=auto'
alias cgs='clear;git status'
alias glog='git log --oneline -n 10'
alias gib='git branch -a'
alias gibm='git branch --merged=master'
fcll() {
	clear;
	echo "pwd: "; pwd;
	echo "cmd: $*";
	ls -Alh --group-directories-first $*
}
alias cll="fcll"
alias ll="ls -Alh --group-directories-first"
alias ftail="tail -n 30 -f"
alias gterm="gnome-terminal"
alias gittree="git log --graph --abbrev-commit --pretty=oneline --decorate"

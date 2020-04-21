#!/bin/bash

#################################################
# Informative git prompt for bash and fish      #
# https://github.com/magicmonty/bash-git-prompt #
#################################################

if [ ! -f "$HOME/.bash-git-prompt/gitprompt.sh" ]; then
    echo "$HOME/.bash-git-prompt/gitprompt.sh not found!"
    echo 'Use "git clone https://github.com/magicmonty/bash-git-prompt.git ~/.bash-git-prompt --depth=1"'
    return
fi

# Set config variables first
GIT_PROMPT_ONLY_IN_REPO=1

# GIT_PROMPT_FETCH_REMOTE_STATUS=0   # uncomment to avoid fetching remote status
# GIT_PROMPT_IGNORE_SUBMODULES=1 # uncomment to avoid searching for changed files in submodules
GIT_PROMPT_WITH_VIRTUAL_ENV=0 # uncomment to avoid setting virtual environment infos for node/python/conda environments

GIT_PROMPT_SHOW_UPSTREAM=1 # uncomment to show upstream tracking branch
GIT_PROMPT_SHOW_UNTRACKED_FILES=normal # can be no, normal or all; determines counting of untracked files

# GIT_PROMPT_SHOW_CHANGED_FILES_COUNT=0 # uncomment to avoid printing the number of changed files

# GIT_PROMPT_STATUS_COMMAND=gitstatus_pre-1.7.10.sh # uncomment to support Git older than 1.7.10

# GIT_PROMPT_START=...    # uncomment for custom prompt start sequence
# GIT_PROMPT_END=...      # uncomment for custom prompt end sequence

# as last entry source the gitprompt script
GIT_PROMPT_THEME=Chmike # use custom theme specified in file GIT_PROMPT_THEME_FILE (default ~/.git-prompt-colors.sh)
# GIT_PROMPT_THEME_FILE=~/.git-prompt-colors.sh
# GIT_PROMPT_THEME=Solarized # use theme optimized for solarized color scheme

# SET PROMPT WHEN NOT IN GIT REPO
export PROMPT_COMMAND=''
export PS1='${debian_chroot:+($debian_chroot)}\[\033[0;33m\]\u\[\e[0m\]@\h:\w\$\[\e[0m\] '

source $HOME/.bash-git-prompt/gitprompt.sh
echo "Informative git prompt loaded..."

#!/bin/bash                                                                                                            
#
# This script does this:
# launch an app if it isn't launched yet,
# focus the app if it is launched but not focused,
# minimize the app if it is focused.

app=$1
if [[ $app == terminator ]]; then
  process_name=usr/bin/terminator
  ##process_name='/usr/bin/python\s*/usr/bin/terminator'
else
  process_name=$app
fi

echo "Process name: $process_name"

#pid=$(pidof $process_name) # pidof didn't work for terminator
pid=$(pgrep -f $process_name)

echo "PID: $pid"

if [ -z $pid ]; then
    echo "Terminator not found; spawning..."
    $app
else
    foc=$(xdotool getactivewindow getwindowpid)
    echo "Is focused: $foc"
    if [[ $pid == $foc ]]; then
        xdotool getactivewindow windowminimize
    else
        wmctrl -x -R 'terminator'
  fi
fi

exit 0

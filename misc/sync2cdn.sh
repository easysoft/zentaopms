#!/bin/bash
#
# This shell is used to sync extension files.
#
# @author chunsheng wang<chunsheng@cnezsoft.com>
# @author yidong wang<wangyidong@cnezsoft.com>
#

# Judge the params, must give $src and $dest.
if [ $# -lt 1 ]; then
  echo './syncext.sh src'
  exit 1
fi

# Judge inotifywait tool exists or not.
which inotifywait > /dev/null
if [ $? -ne 0 ]; then
  echo 'No inotifywait. Please install inotify-tools, for debian or ubuntun run apt-get install inotify-tools.';
  exit 1
fi

# Get $src and $dest.
src=$1
dest=`basename $src`/;

# Watch the $src directory, and sync files to destination.
inotifywait -mrq --event create,modify,delete,move --format '%w %e %f' $src |\
while read watcher event file ; do

  # compute the $path2process.
  path2process=$watcher$file
  path2process=${path2process/$src/$dest}
  echo $path2process

  # Delete a file or a directory.
  if [ "$event" == "DELETE" ]; then

    #rm -fr $path2process

    echo `date "+%H:%M:%S"` $path2process deleted.;

  # If event is ngnored, continue.
  elif [ "$event" == "IGNORED" ]; then

    continue

  # Sync files.
  else

    echo `date "+%H:%M:%S"` $path2process copied.
    #rsync -aqP --exclude='*.svn' --exclude='db/' --exclude='doc/'  $src/ $dest
  fi

done

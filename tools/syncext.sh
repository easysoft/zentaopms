#!/bin/bash
#
# This shell is used to sync extension files.
#
# @author chunsheng wang<chunsheng@cnezsoft.com>
# @author yidong wang<wangyidong@cnezsoft.com>
#

# Judge the params, must give $src and $dest.
if [ $# -lt 2 ]; then
  echo './syncext.sh src dest'
  exit 1
fi

# Judge inotifywait tool exists or not.
which inotifywait > /dev/null
if [ $? -ne 0 ]; then
  echo 'No inotifywait. Please install inotify-tools, for debian or ubuntun run apt-get install inotify-tools.';
  exit 1
fi

# Get $src and $dest.
src=$1/
dest=$2/

# Rsync when the shell load first. 
rsync -aqP --exclude='*.svn' --exclude="db/" --exclude="doc/" --exclude='hook/' $src $dest
echo `date "+%H:%M:%S"` sync $src to $dest succssfully.

# Watch the $src directory, and sync files to destination.
inotifywait -mrq --event create,modify,delete,move --format '%w %e %f' --exclude=".svn" $src |\
while read watcher event file ; do

  # compute the $path2process.
  path2process=$watcher$file
  path2process=${path2process/$src/$dest}

  # Delete a file or a directory.
  if [ "$event" == "DELETE" -o "$event" == "DELETE,ISDIR" ]; then

    # If delete a file, use rm -fr, directory, use rmdir for security.
    if [ "$event" == "DELETE" ]; then
      rm -fr $path2process
    else
      rmdir $path2process
    fi

    echo `date "+%H:%M:%S"` $path2process deleted.;

  # If event is ngnored, continue.
  elif [ "$event" == "IGNORED" ]; then

    continue

  # Sync files.
  else

    echo `date "+%H:%M:%S"` $path2process copied.
    rsync -aqP --exclude='*.svn' --exclude='db/' --exclude='doc/' --exclude='hook/'  $src/ $dest
  fi

done

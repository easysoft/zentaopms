#!/bin/bash
#
# Watch a upload directory and remove dangder files.
#
# @author chunsheng wang<chunsheng@cnezsoft.com>

# Judge the params, must give $dir
if [ $# -lt 1 ]; then
  echo './zdog.sh directory'
  exit 1
fi

# Judge inotifywait tool exists or not.
which inotifywait > /dev/null
if [ $? -ne 0 ]; then
  echo 'No inotifywait. Please install inotify-tools, for debian or ubuntun run apt-get install inotify-tools.';
  exit 1
fi

# Get the directory to watch.
dir=$@

# Watch the $dir directory
if [ ! -d "/tmp/zdog" ]; then
  mkdir -v /tmp/zdog;
fi
echo `date "+%H:%M:%S"` begin watching $dir > /tmp/zdog/zdog.log
inotifywait -mrq --event create,modify --format '%w %e %f' $dir |\
while read watcher event file ; do

  # compute the $path2process.
  path2process=$watcher$file
  targetfile=/tmp/zdog/$file;
  echo $path2process | grep -q '.php';
  if [ $? -eq 0 ] && [ -s $path2process ]; then
    sudo mv -f $path2process $targetfile;
    echo `date "+%H:%M:%S"` $path2process moved to $targetfile >> /tmp/zdog/zdog.log 2>&1;
  fi
done

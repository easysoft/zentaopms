#!/bin/bash
echo "Input the path PHP:"
read phpPath
filelist=$(ls ./)
if [ -f $phpPath ]; then
  phpPath=${phpPath//\//\\\/}
  for filename in $filelist
  do
    if [ $0 != ./$filename ]; then
      echo "Change ./$filename"
      sed -i "1,2s/^[^ ]*php[^ ]* /$phpPath /g" ./$filename
    fi
  done
  echo "Success"
else
  echo "Error path for PHP"
fi

exit 0

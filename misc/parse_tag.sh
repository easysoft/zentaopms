#!/bin/bash

TAG=$1
FIELD=$2

frameCount=`echo $TAG| grep -o '_' | wc -l`

build_date=${TAG##*_}

build_id=''

if [ $frameCount -eq 3 ];then
  build_id=`echo $TAG | cut -d _ -f 3`
fi

case $FIELD in
  date)
     # must 8 numbers
     echo $build_date | egrep -E '^[0-9]{8}$'
  ;;
  id)
    echo $build_id
  ;;
  group)
    if [ -n "$build_id" ];then
      echo "$build_date.$build_id"
    else
      echo "$build_date.release"
    fi
  ;;
  type)
    if [ -n "$build_id" ];then
      echo snapshot
    else
      echo release
    fi
  ;;
esac

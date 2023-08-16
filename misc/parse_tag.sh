#!/bin/bash

TAG=$1
FIELD=$2

frameCount=`echo $TAG| grep -o '_' | wc -l`

build_id=''

if [ $frameCount -eq 3 ];then
  build_id=`echo $TAG | cut -d _ -f 3`
fi

case $FIELD in
  group)
    case $frameCount in
      1)
        echo release
        ;;
      *)
        build_date=${TAG##*_}
        if [ -n "$build_id" ];then
          echo "$build_date.$build_id"
        else
          echo "$build_date.release"
        fi
        ;;
    esac
  ;;
  type)
    if [ -n "$build_id" ];then
      echo snapshot
    else
      echo release
    fi
  ;;
esac

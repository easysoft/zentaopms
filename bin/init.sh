#!/bin/bash
# usage: ./init.sh /usr/bin/php http://localhost

phpcli=$1
pmsRoot=$2
basePath=$(cd "$(dirname "$0")"; pwd)
if [ ! -n "$1" ]; then
  while :; do
    echo "Please input your php path:(example: /usr/bin/php)"
    read phpcli
    if [ ! -f $phpcli ]; then
      echo "php path is error";
    elif [ "$phpcli"x != ""x ]; then
      break;
    fi
  done
fi
if [ ! -n "$2" ]; then
  while :; do
    echo "Please input zentao url:(example: http://localhost:88/zentao or http://localhost)"
    read pmsRoot
    if [ -z "$pmsRoot" ]; then
      echo "zentao url is error";
    else
      break;
    fi
  done
fi

pmsRoot=`echo "$pmsRoot" | sed 's/[/]$//g'`
$phpcli "$basePath/php/init.php" $phpcli $pmsRoot;
chmod 755 $basePath/*.sh

exit 0

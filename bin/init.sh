#!/bin/bash

phpCmd=$1
if [ $# -ne 2 ]; then
  echo "Please input your php path:(example: /usr/bin/php)"
  read phpCmd 
  if [ ! -f $phpCmd ]; then 
    echo "php path is error"
    exit 1
  fi
fi 

if [ "`cat ../config/my.php | grep -c 'PATH_INFO'`" != 0 ];then
  requestType='PATH_INFO';
else
  requestType='GET';
fi

backup="$phpCmd php/backup.php"
computeburn="$phpCmd php/computeburn.php"
ztcli="$phpCmd ztcli $*"
if [ $requestType == 'PATH_INFO' ]; then
  checkdb="$phpCmd ztcli 'http://localhost/admin-checkdb'";
else
  checkdb="$phpCmd ztcli 'http://localhost/?m=admin&f=checkdb'";
fi

echo $backup > backup.sh
echo $computeburn > computeburn.sh
echo $checkdb > checkdb.sh
echo $ztcli > ztcli.sh

chmod 755 backup.sh
chmod 755 computeburn.sh
chmod 755 checkdb.sh
chmod 755 ztcli.sh

exit 0

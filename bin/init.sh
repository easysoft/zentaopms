#!/bin/bash

phpCli=$1
if [ $# -ne 2 ]; then
  echo "Please input your php path:(example: /usr/bin/php)"
  read phpCli 
  if [ ! -f $phpCli ]; then 
    echo "php path is error"
    exit 1
  fi
fi 

if [ "`cat ../config/my.php | grep -c 'PATH_INFO'`" != 0 ];then
  requestType='PATH_INFO';
else
  requestType='GET';
fi

#backup database
backup="$phpCli php/backup.php"
echo $backup > backup.sh

#computeburn
if [ $requestType == 'PATH_INFO' ]; then
  computeburn="$phpCli ztcli 'http://localhost/project-computeburn'";
else
  computeburn="$phpCli ztcli 'http://localhost/?m=project&f=computeburn'";
fi
echo $computeburn > computeburn.sh

#ztcli
ztcli="$phpCli ztcli \$*"
echo $ztcli > ztcli.sh

#check database
if [ $requestType == 'PATH_INFO' ]; then
  checkdb="$phpCli ztcli 'http://localhost/admin-checkdb'";
else
  checkdb="$phpCli ztcli 'http://localhost/?m=admin&f=checkdb'";
fi
echo $checkdb > checkdb.sh

chmod 755 *.sh

exit 0

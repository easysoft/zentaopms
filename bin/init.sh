#!/bin/bash
function input()
{
  while :; do
    echo "Please input your php path:(example: /usr/bin/php)"
    read phpCli 
    if [ ! -f $phpCli ]; then 
      echo "php path is error";
    else
      return $phpCli;
    fi
  done
}

phpCli=$1
if [ $# -ne 2 ]; then
  phpCli= input
fi 

if [ "`cat ../config/my.php | grep -c 'PATH_INFO'`" != 0 ];then
  requestType='PATH_INFO';
else
  requestType='GET';
fi

#ztcli
ztcli="$phpCli ztcli \$*"
echo $ztcli > ztcli.sh
echo "ztcli.sh ok"

#backup database
backup="$phpCli php/backup.php"
echo $backup > backup.sh
echo "backup.sh ok"

#computeburn
if [ $requestType == 'PATH_INFO' ]; then
  computeburn="$phpCli ztcli 'http://localhost/project-computeburn'";
else
  computeburn="$phpCli ztcli 'http://localhost/?m=project&f=computeburn'";
fi
echo $computeburn > computeburn.sh
echo "computeburn.sh ok"

#check database
if [ $requestType == 'PATH_INFO' ]; then
  checkdb="$phpCli ztcli 'http://localhost/admin-checkdb'";
else
  checkdb="$phpCli ztcli 'http://localhost/?m=admin&f=checkdb'";
fi
echo $checkdb > checkdb.sh
echo "checkdb.sh ok"

chmod 755 *.sh

exit 0

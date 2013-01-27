#!/bin/bash
phpcli=$1
basePath=$(cd "$(dirname "$0")"; pwd)
if [ $# -ne 1 ]; then
  while :; do
    echo "Please input your php path:(example: /usr/bin/php)"
    read phpcli 
    if [ ! -f $phpcli ]; then 
      echo "php path is error";
    else
      break;
    fi
  done
fi 

if [ "`cat $basePath/../config/my.php | grep -c 'PATH_INFO'`" != 0 ];then
  requestType='PATH_INFO';
else
  requestType='GET';
fi

#ztcli
ztcli="$phpcli $basePath/ztcli \$*"
echo $ztcli > $basePath/ztcli.sh
echo "ztcli.sh ok"

#backup database
backup="$phpcli $basePath/php/backup.php"
echo $backup > $basePath/backup.sh
echo "backup.sh ok"

#computeburn
if [ $requestType == 'PATH_INFO' ]; then
  computeburn="$phpcli $basePath/ztcli 'http://localhost/project-computeburn'";
else
  computeburn="$phpcli $basePath/ztcli 'http://localhost/?m=project&f=computeburn'";
fi
echo $computeburn > $basePath/computeburn.sh
echo "computeburn.sh ok"

#check database
if [ $requestType == 'PATH_INFO' ]; then
  checkdb="$phpcli $basePath/ztcli 'http://localhost/admin-checkdb'";
else
  checkdb="$phpcli $basePath/ztcli 'http://localhost/?m=admin&f=checkdb'";
fi
echo $checkdb > $basePath/checkdb.sh
echo "checkdb.sh ok"

#cron
if [ ! -d "$basePath/cron" ]; then 
  mkdir $basePath/cron
fi
echo "# system cron." > $basePath/cron/sys.cron
echo "# minute hour day month week  command." >> $basePath/cron/sys.cron
echo "1 1  * * *   $basePath/backup.sh          # backup database and file." >> $basePath/cron/sys.cron
echo "1 23 * * *   $basePath/computeburn.sh     # compute burndown chart." >> $basePath/cron/sys.cron
cron="$phpcli $basePath/php/crond.php"
echo $cron > $basePath/cron.sh
echo "cron.sh ok"

chmod -R 755 $basePath/cron
chmod 755 $basePath/*.sh

exit 0

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
cat $basePath/../config/my.php |awk '$1!~/^\/\//&& $1~/\$config\->requestType/{requestType = $0} END{print requestType}'| grep -c 'PATH_INFO' > ./init.tmp
if [ "`cat ./init.tmp`" != 0 ];then
  requestType='PATH_INFO';
else
  requestType='GET';
fi
rm ./init.tmp

# ztcli
ztcli="$phpcli $basePath/ztcli \$*"
echo $ztcli > $basePath/ztcli.sh
echo "ztcli.sh ok"

# backup database
backup="$phpcli $basePath/php/backup.php"
echo $backup > $basePath/backup.sh
echo "backup.sh ok"

# computeburn
if [ $requestType == 'PATH_INFO' ]; then
  computeburn="$phpcli $basePath/ztcli '$pmsRoot/project-computeburn'";
else
  computeburn="$phpcli $basePath/ztcli '$pmsRoot/index.php?m=project&f=computeburn'";
fi
echo $computeburn > $basePath/computeburn.sh
echo "computeburn.sh ok"

# daily remind
if [ $requestType == 'PATH_INFO' ]; then
  checkdb="$phpcli $basePath/ztcli '$pmsRoot/report-remind'";
else
  checkdb="$phpcli $basePath/ztcli '$pmsRoot/index.php?m=report&f=remind'";
fi
echo $checkdb > $basePath/dailyreminder.sh
echo "dailyreminder.sh ok"

# check database
if [ $requestType == 'PATH_INFO' ]; then
  checkdb="$phpcli $basePath/ztcli '$pmsRoot/admin-checkdb'";
else
  checkdb="$phpcli $basePath/ztcli '$pmsRoot/index.php?m=admin&f=checkdb'";
fi
echo $checkdb > $basePath/checkdb.sh
echo "checkdb.sh ok"

# syncsvn.
if [ $requestType == 'PATH_INFO' ]; then
  syncsvn="$phpcli $basePath/ztcli '$pmsRoot/svn-run'";
else
  syncsvn="$phpcli $basePath/ztcli '$pmsRoot/index.php?m=svn&f=run'";
fi
echo $syncsvn > $basePath/syncsvn.sh
echo "syncsvn.sh ok"

# syncgit.
if [ $requestType == 'PATH_INFO' ]; then
  syncgit="$phpcli $basePath/ztcli '$pmsRoot/git-run'";
else
  syncgit="$phpcli $basePath/ztcli '$pmsRoot/index.php?m=git&f=run'";
fi
echo $syncgit > $basePath/syncgit.sh
echo "syncgit.sh ok"

# cron
if [ ! -d "$basePath/cron" ]; then 
  mkdir $basePath/cron
fi
echo "# system cron." > $basePath/cron/sys.cron
echo "#min   hour day month week  command." >> $basePath/cron/sys.cron
echo "0      1    *   *     *     $basePath/dailyreminder.sh   # dailyreminder."            >> $basePath/cron/sys.cron
echo "1      1    *   *     *     $basePath/backup.sh          # backup database and file." >> $basePath/cron/sys.cron
echo "1      23   *   *     *     $basePath/computeburn.sh     # compute burndown chart."   >> $basePath/cron/sys.cron
echo "1-59/2 *    *   *     *     $basePath/syncsvn.sh         # sync subversion."          >> $basePath/cron/sys.cron
echo "1-59/2 *    *   *     *     $basePath/syncgit.sh         # sync git"                  >> $basePath/cron/sys.cron
cron="$phpcli $basePath/php/crond.php"
echo $cron > $basePath/cron.sh
echo "cron.sh ok"

chmod 755 $basePath/*.sh

exit 0

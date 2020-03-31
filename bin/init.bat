@ECHO OFF
SET PATH=%systemRoot%;%systemRoot%\system32;%PATH%
SET lastDir=%cd%
SET baseDir=%~dp0
SET cronDir=%baseDir%cron\
set sysCron=%cronDir%sys.cron

:: get phpcli and pmsRoot
SET phpcli=%1
SET pmsRoot=%2
:input_php
IF "%phpcli%"=="" SET /P phpcli="Please input your php path:(example: c:\windows\php.exe)"
if "%phpcli%"=="" (
    echo php path is error
    goto input_php 
)
if not exist %phpcli% (
  echo php path is error
  goto input_php 
)
:input_url
IF "%pmsRoot%"=="" SET /P pmsRoot="Please input zentao url:(example: http://localhost or http://127.0.0.1:88)"
IF "%pmsRoot%"=="" (
  echo zentao url is error
  goto input_url 
)

:: get pmsRoot
if "%pmsRoot:~-1%" == "/" SET pmsRoot=%pmsRoot:~0,-1%
:: get requestType
SET requestType= 'PATH_INFO' 
for /f "tokens=3" %%f in ('find /c "'PATH_INFO'" "%baseDir%..\config\my.php"') do set count=%%f
if not defined count set count=1
if %count% == 0 SET requestType='GET'

:: create ztcli
SET ztcli= %phpcli% %baseDir%ztcli %%*
echo %ztcli% > %baseDir%ztcli.bat
echo ztcli.bat ok

:: create backup.bat
if %requestType% == 'PATH_INFO' (
  SET backup= %phpcli% %baseDir%ztcli "%pmsRoot%/backup-backup.html"
)else (
  SET backup= %phpcli% %baseDir%ztcli "%pmsRoot%/index.php?m=backup&f=backup"
)
echo %backup% > %baseDir%backup.bat
echo backup.bat ok

:: create dailyreminder.bat
if %requestType% == 'PATH_INFO' (
  SET computeburn= %phpcli% %baseDir%ztcli "%pmsRoot%/report-remind"
)else (
  SET computeburn= %phpcli% %baseDir%ztcli "%pmsRoot%/index.php?m=report&f=remind"
)
echo %computeburn% > %baseDir%dailyreminder.bat
echo dailyreminder.bat ok

:: create computeburn.bat
if %requestType% == 'PATH_INFO' (
  SET computeburn= %phpcli% %baseDir%ztcli "%pmsRoot%/project-computeburn"
)else (
  SET computeburn= %phpcli% %baseDir%ztcli "%pmsRoot%/index.php?m=project&f=computeburn"
)
echo %computeburn% > %baseDir%computeburn.bat
echo computeburn.bat ok

:: create computetaskeffort.bat
if %requestType% == 'PATH_INFO' (
  SET computetaskeffort= %phpcli% %baseDir%ztcli "%pmsRoot%/project-computetaskeffort"
)else (
  SET computetaskeffort= %phpcli% %baseDir%ztcli "%pmsRoot%/index.php?m=project&f=computetaskeffort"
)
echo %computetaskeffort% > %baseDir%computetaskeffort.bat
echo computetaskeffort.bat ok

:: create checkdb.bat
if %requestType% == 'PATH_INFO' (
  SET checkdb= %phpcli% %baseDir%ztcli "%pmsRoot%/admin-checkdb"
)else (
  SET checkdb= %phpcli% %baseDir%ztcli "%pmsRoot%/index.php?m=admin&f=checkdb"
)
echo %checkdb% > %baseDir%checkdb.bat
echo checkdb.bat ok

:: create syncsvn.bat
if %requestType% == 'PATH_INFO' (
  SET syncsvn= %phpcli% %baseDir%ztcli "%pmsRoot%/svn-run"
)else (
  SET syncsvn= %phpcli% %baseDir%ztcli "%pmsRoot%/index.php?m=svn&f=run"
)
echo %syncsvn% > %baseDir%syncsvn.bat
echo syncsvn.bat ok

:: create syncgit.bat.
if %requestType% == 'PATH_INFO' (
  SET syncgit= %phpcli% %baseDir%ztcli "%pmsRoot%/git-run"
)else (
  SET syncgit= %phpcli% %baseDir%ztcli "%pmsRoot%/index.php?m=git&f=run"
)
echo %syncgit% > %baseDir%syncgit.bat
echo syncgit.bat ok

:: async send mail.
if %requestType% == 'PATH_INFO' (
  SET sendmail= %phpcli% %baseDir%ztcli "%pmsRoot%/mail-asyncSend"
)else (
  SET sendmail= %phpcli% %baseDir%ztcli "%pmsRoot%/index.php?m=mail&f=asyncSend"
)
echo %sendmail% > %baseDir%sendmail.bat
echo sendmail.bat ok

:: async send webhook.
if %requestType% == 'PATH_INFO' (
  SET sendwebhook= %phpcli% %baseDir%ztcli "%pmsRoot%/webhook-asyncSend"
)else (
  SET sendwebhook= %phpcli% %baseDir%ztcli "%pmsRoot%/index.php?m=webhook&f=asyncSend"
)
echo %sendwebhook% > %baseDir%sendwebhook.bat
echo sendwebhook.bat ok

:: create cycle todo.
if %requestType% == 'PATH_INFO' (
  SET createcycle= %phpcli% %baseDir%ztcli "%pmsRoot%/todo-createCycle"
)else (
  SET createcycle= %phpcli% %baseDir%ztcli "%pmsRoot%/index.php?m=todo&f=createCycle"
)
echo %createcycle% > %baseDir%createcycle.bat
echo createcycle.bat ok

:: init queue.
if %requestType% == 'PATH_INFO' (
  SET initqueue= %phpcli% %baseDir%ztcli "%pmsRoot%/ci-initQueue"
)else (
  SET initqueue= %phpcli% %baseDir%ztcli "%pmsRoot%/index.php?m=ci&f=initQueue"
)
echo %initqueue% > %baseDir%initqueue.bat
echo initqueue.bat ok

:: check build status.
if %requestType% == 'PATH_INFO' (
  SET checkbuildstatus= %phpcli% %baseDir%ztcli "%pmsRoot%/ci-checkBuildStatus"
)else (
  SET checkbuildstatus= %phpcli% %baseDir%ztcli "%pmsRoot%/index.php?m=ci&f=checkBuildStatus"
)
echo %checkbuildstatus% > %baseDir%checkbuildstatus.bat
echo checkbuildstatus.bat ok

:: execute compile.
if %requestType% == 'PATH_INFO' (
  SET execcompile= %phpcli% %baseDir%ztcli "%pmsRoot%/ci-exec"
)else (
  SET execcompile= %phpcli% %baseDir%ztcli "%pmsRoot%/index.php?m=ci&f=exec"
)
echo %execcompile% > %baseDir%execcompile.bat
echo execcompile.bat ok

:: delete log.
if %requestType% == 'PATH_INFO' (
  SET deletelog= %phpcli% %baseDir%ztcli "%pmsRoot%/admin-deleteLog"
)else (
  SET deletelog= %phpcli% %baseDir%ztcli "%pmsRoot%/index.php?m=admin&f=deleteLog"
)
echo %deletelog% > %baseDir%deletelog.bat
echo deletelog.bat ok

:: create crond.bat
SET cron= %phpcli% %baseDir%php\crond.php
echo %cron% > %baseDir%crond.bat
echo crond.bat ok

:: create system cron.
if not exist %cronDir% md %cronDir%
echo # system cron. > %sysCron%
echo #min   hour day month week  command. >> %sysCron%
echo 0      1    *   *     *     %baseDir%dailyreminder.bat # daily reminder.           >> %sysCron%
echo 1      1    *   *     *     %baseDir%backup.bat        # backup database and file. >> %sysCron%
echo 1      23   *   *     *     %baseDir%computeburn.bat   # compute burndown chart.   >> %sysCron%
echo 1-59/2 *    *   *     *     %baseDir%syncsvn.bat       # sync subversion.          >> %sysCron%
echo 1-59/2 *    *   *     *     %baseDir%syncgit.bat       # sync git.                 >> %sysCron%
echo 1-59/5 *    *   *     *     %baseDir%sendmail.bat      # async send mail.          >> %sysCron%
echo 1-59/5 *    *   *     *     %baseDir%sendwebhook.bat   # async send webhook.       >> %sysCron%
echo 1      1    *   *     *     %baseDir%createcycle.bat   # create cycle todo.        >> %sysCron%
echo 30     1    *   *     *     %baseDir%deletelog.bat     # delete log.               >> %sysCron%
echo 1      0    *   *     *     %baseDir%initqueue.bat     # init queue.               >> %sysCron%
echo 1-59/5 *    *   *     *     %baseDir%checkbuildstatus.bat   # check build status.  >> %sysCron%
echo 1-59/5 *    *   *     *     %baseDir%execcompile.bat        # execute compile.     >> %sysCron%

:: return 0 when success.
exit /b 0

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
IF "%1"=="" SET /P phpcli="Please input your php path:(example: c:\windows\php.exe)"
if not exist %phpcli% (
  echo php path is error
  goto input_php 
)
:input_url
IF "%2"=="" SET /P pmsRoot="Please input zentao url:(example: http://localhost or http://127.0.0.1:88)"
if %pmsRoot% == '' (
  echo zentao url is error
  goto input_url 
)

:: get pmsRoot
if %pmsRoot:~-1% == / SET pmsRoot=%pmsRoot:~0,-1%
:: get requestType
SET requestType= 'PATH_INFO' 
for /f "tokens=3" %%f in ('find /c "'PATH_INFO'" "%baseDir%..\config\my.php"') do set count=%%f
if %count% == 0 SET requestType='GET'

:: create ztcli
SET ztcli= %phpcli% %baseDir%ztcli %*
echo %ztcli% > %baseDir%ztcli.bat
echo ztcli.bat ok

:: create backup.bat
SET backup= %phpcli% %baseDir%php\backup.php 
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

:: create syncgit.bat
if %requestType% == 'PATH_INFO' (
  SET syncgit= %phpcli% %baseDir%ztcli "%pmsRoot%/git-run"
)else (
  SET syncgit= %phpcli% %baseDir%ztcli "%pmsRoot%/index.php?m=git&f=run"
)
echo %syncgit% > %baseDir%syncgit.bat
echo syncgit.bat ok

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

:: return 0 when success.
exit /b 0

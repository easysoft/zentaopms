@ECHO OFF
SET PATH=%systemRoot%;%systemRoot%\system32;%PATH%
SET lastDir=%cd%
SET baseDir=%~dp0
SET cronDir=%baseDir%cron\
set sysCron=%cronDir%sys.cron

:: get phpcli
SET phpcli=%1
:input
IF "%1"=="" SET /P phpcli="Please input your php path:(example: c:\windows\php.exe)"
if not exist %phpcli% (
  echo php path is error
  goto input 
)
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
  SET computeburn= %phpcli% %baseDir%ztcli "http://localhost/report-remind"
)else (
  SET computeburn= %phpcli% %baseDir%ztcli "http://localhost/?m=report&f=remind"
)
echo %computeburn% > %baseDir%dailyreminder.bat
echo dailyreminder.bat ok

:: create computeburn.bat
if %requestType% == 'PATH_INFO' (
  SET computeburn= %phpcli% %baseDir%ztcli "http://localhost/project-computeburn"
)else (
  SET computeburn= %phpcli% %baseDir%ztcli "http://localhost/?m=project&f=computeburn"
)
echo %computeburn% > %baseDir%computeburn.bat
echo computeburn.bat ok

:: create checkdb.bat
if %requestType% == 'PATH_INFO' (
  SET checkdb= %phpcli% %baseDir%ztcli "http://localhost/admin-checkdb"
)else (
  SET checkdb= %phpcli% %baseDir%ztcli "http://localhost/?m=admin&f=checkdb"
)
echo %checkdb% > %baseDir%checkdb.bat
echo checkdb.bat ok

:: create syncsvn.bat
if %requestType% == 'PATH_INFO' (
  SET svnrun= %phpcli% %baseDir%ztcli "http://localhost/svn-run"
)else (
  SET svnrun= %phpcli% %baseDir%ztcli "http://localhost/?m=svn&f=run"
)
echo %svnrun% > %baseDir%svnrun.bat
echo svnrun.bat ok

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
echo 1-59/2 *    *   *     *     %baseDir%svnrun.bat        # sync subversion.          >> %sysCron%

:: return 0 when success.
exit /b 0

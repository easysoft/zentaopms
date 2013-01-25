@ECHO OFF
SET PATH=%systemRoot%;%systemRoot%\system32;%PATH%
SET lastDir=%cd%
SET baseDir=%~dp0
%baseDir:~0,2%
cd %baseDir% 

::get phpcli
SET phpcli=%1
:input
IF "%1"=="" SET /P phpcli="Please input your php path:(example: c:\windows\php.exe)"
if not exist %phpcli% (
  echo php path is error
  goto input 
)
:: get requestType
SET requestType= 'PATH_INFO' 
for /f "tokens=3" %%f in ('find /c "'PATH_INFO'" "..\config\my.php"') do set findNum=%%f
if %findNum% == 0 SET requestType= 'GET'

::ztcli
SET ztcli= %phpcli% ztcli $*
echo %ztcli% > ztcli.bat
echo ztcli.bat ok

::backup database
SET backup= %phpcli% php\backup.php 
echo %backup% > backup.bat
echo backup.bat ok

::compute burn
if %requestType% == 'PATH_INFO' (
  SET computeburn= %phpcli% ztcli 'http://localhost/project-computeburn'
)else (
  SET computeburn= %phpcli% ztcli 'http://localhost/?m=project&f=computeburn'
)
echo %computeburn% > computeburn.bat
echo computeburn.bat ok

::check database
if %requestType% == 'PATH_INFO' (
  SET checkdb= %phpcli% ztcli 'http://localhost/admin-checkdb'
)else (
  SET checkdb= %phpcli% ztcli 'http://localhost/?m=admin&f=checkdb'
)
echo %checkdb% > checkdb.bat
echo checkdb.bat ok

pause

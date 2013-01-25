@ECHO OFF
SET PATH=%systemRoot%;%systemRoot%\system32;%PATH%
SET lastDir=%cd%
SET baseDir=%~dp0
%baseDir:~0,2%
cd %baseDir% 

::get phpCli
SET phpCli=%1
IF "%1"=="" SET /P phpCli="Please input your php path:(example: c:\windows\php.exe)"

:: get requestType
SET requestType= 'PATH_INFO' 
for /f "tokens=3" %%f in ('find /c "'PATH_INFO'" "..\config\my.php"') do set findNum=%%f
if %findNum% == 0 SET requestType= 'GET'

::backup database
SET backup= %phpCli% php\backup.php 
echo %backup% > backup.bat

::compute burn
if %requestType% == 'PATH_INFO' (
  SET computeburn= %phpCli% ztcli 'http://localhost/project-computeburn'
)else (
  SET computeburn= %phpCli% ztcli 'http://localhost/?m=project&f=computeburn'
)
echo %computeburn% > computeburn.bat

::ztcli
SET ztcli= %phpCli% ztcli $*
echo %ztcli% > ztcli.bat

::check database
if %requestType% == 'PATH_INFO' (
  SET checkdb= %phpCli% ztcli 'http://localhost/admin-checkdb'
)else (
  SET checkdb= %phpCli% ztcli 'http://localhost/?m=admin&f=checkdb'
)
echo %checkdb% > checkdb.bat

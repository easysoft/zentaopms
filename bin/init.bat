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
%phpcli% %baseDir%php\init.php %phpcli% %pmsRoot%

:: return 0 when success.
exit /b 0

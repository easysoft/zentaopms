@ECHO OFF

if "%1" == "sfx" (
    cd xampp
)
if exist php\php.exe GOTO Normal
if not exist php\php.exe GOTO Abort

:Abort
echo Sorry ... cannot find php cli!
echo Must abort these process!
pause
GOTO END

:Normal
set PHP_BIN=php\php.exe
set CONFIG_PHP=install\install.php
%PHP_BIN% -n -d output_buffering=0 -q %CONFIG_PHP% usb
GOTO END

:END
pause

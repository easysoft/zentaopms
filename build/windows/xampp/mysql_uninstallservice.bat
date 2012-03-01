@echo off

if "%OS%" == "Windows_NT" goto WinNT

:Win9X
echo Don't be stupid! Win9x don't know Services
echo Please use mysql_stop.bat instead
goto exit

:WinNT
echo now stopping MySQL when it runs
net stop mysql
echo Uninstalling MySql-Service
bin\mysqld.exe --remove mysql
if not exist %windir%\my.ini GOTO exit
echo Remove %windir%\my.ini
del %windir%\my.ini

:exit
pause

@echo off

if "%OS%" == "Windows_NT" goto WinNT

:Win9X
echo Don't be stupid! Win9x don't know Services
echo Please use apache_stop.bat instead
goto exit

:WinNT
echo Are you sure you wan't this?
echo now stopping mysql when it runs
net stop mysqlzt
mysql\bin\mysqld.exe --remove mysqlzt

:exit
pause

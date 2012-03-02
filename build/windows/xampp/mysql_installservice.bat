@echo off

if "%OS%" == "Windows_NT" goto WinNT

:Win9X
echo Don't be stupid! Win9x don't know Services
echo Please use apache_start.bat instead
goto exit

:WinNT
echo Installing mysql as an Service
mysql\bin\mysqld.exe --install mysqlzt
echo Now we Start mysql :)
net start mysqlzt

:exit
pause

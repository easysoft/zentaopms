@echo off

if "%OS%" == "Windows_NT" goto WinNT

:Win9X
echo Don't be stupid! Win9x don't know Services
echo Please use apache_start.bat instead
goto exit

:WinNT
echo Installing Apache2.2 as an Service
apache\bin\httpd -k install -n apachezt
echo Now we Start Apache
net start apachezt

:exit
pause

@echo off

echo Installing apache 2.4 as service...
..\apache\bin\httpd -k install -n apachezt
echo starting apache
net start apachezt

echo Installing mysql as service...
..\mysql\bin\mysqld.exe --install mysqlzt
echo starting mysql
net start mysqlzt

pause

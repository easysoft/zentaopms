@echo off

echo stopping apache 2.4
net stop apachezt
echo removing apache 2.4
..\apache\bin\httpd -k uninstall -n apachezt

echo 
echo stopping mysql
net stop mysqlzt
echo removing mysql
..\mysql\bin\mysqld.exe --remove mysqlzt

pause

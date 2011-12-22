tar zxvf $1
cd lampp

# rm useless files.
rm -fr RELEASENOTES
rm -fr  error
rm -fr icons
rm -fr logs/*
rm -fr htdocs/*
rm -fr licenses
rm -fr phpmyadmin
rm -fr cgi-bin
rm -fr libexec
rm -fr tmp/*
chmod -R 777 tmp

# rm useless settings.
rm -fr etc/proftpd.conf
rm -fr etc/webalizer.conf*
rm -fr etc/openldap
rm -fr etc/original
rm -fr etc/httpd.conf.bak
rm -fr etc/lampp/startftp

# rm useless binaries.
mv bin bin.bak
mkdir bin
cd bin.bak 
cp apachectl apxs my_print_defaults mysql mysql.server mysqld_safe mysqldump php php-config phpize httpd ../bin/
cd ../
rm -fr bin.bak

# fix bug of the mysqld_safe
sed -e 's/\/opt\/lampp\/\/opt\/lampp\/sbin/\/opt\/lampp\/sbin/g' bin/mysqld_safe > bin/mysqld_safe.new
mv bin/mysqld_safe.new bin/mysqld_safe
chmod a+rx bin/mysqld_safe

# rm useless binaries at sbin directory, keep mysqld.
mv sbin sbin.bak
mkdir sbin
mv sbin.bak/mysqld sbin/
rm -fr sbin.bak

# process share directory. keep english and lampp directory.
mv share share.bak
mkdir share
mv share.bak/english share/
mv share.bak/lampp share
rm -fr share.bak

# process lib directory.
mv lib/php/extensions lib/phpextensions
rm -fr lib/php
mkdir lib/php
mv lib/phpextensions lib/php/extensions
rm -fr lib/perl5
rm -fr lib/proftpd
rm -fr lib/fonts

# process var directory.
rm -fr var/perl
rm -fr var/proftpd*
rm -fr var/mysql/cdcol
rm -fr var/mysql/*.err
rm -fr var/mysql/ib*
rm -fr var/mysql/phpmyadmin
rm -fr var/mysql/test
chmod -R 777 var/mysql

# process modules directory.
rm -fr modules/mod_perl.so

# copy customized xmapp config to etc/extra
cp ../zentao.conf etc/extra/httpd-xampp.conf

# copy the zentao code.
mv ../../../zentaopms ./zentao

# copy the makefile
cp ../Makefile .

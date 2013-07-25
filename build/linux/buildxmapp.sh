tar zxvf $1/xampp.tar.gz
cd lampp

# rm useless files.
rm -fr RELEASENOTES
rm -fr error
rm -fr icons
rm -fr licenses
rm -fr logs/*
rm -fr htdocs/*
rm -fr cgi-bin
rm -fr libexec
rm -fr phpmyadmin
rm -fr php
rm -fr temp/*
rm -fr apache2
rm -fr ctlscript.sh
rm -fr manager-linux.run
rm -fr pear
rm -fr proftpd
rm -fr properties.ini
rm -fr uninstall*
rm -fr mysql
rm -fr img
chmod -R 777 temp

# rm useless settings.
rm -fr etc/proftpd.conf
rm -fr etc/webalizer.conf*
rm -fr etc/freetds.conf
rm -fr etc/openssl.cnf
rm -fr etc/php.ini-pre1.7.2
rm -fr etc/pear.conf
rm -fr etc/pool.conf
rm -fr etc/openldap
rm -fr etc/original
rm -fr etc/httpd.conf.bak
rm -fr etc/lampp/startftp
rm -fr etc/ssl*
rm -fr etc/extra
rm -fr etc/magic
rm -fr etc/locales.conf

# process httpd conf
cp ../httpd.conf etc/httpd.conf

# process my.cnf
cp ../my.cnf etc/my.cnf

# process php.ini
cp ../php.ini etc/php.ini

# rm useless binaries.
mv bin bin.bak
mkdir bin
cd bin.bak 
cp htpasswd apachectl my_print_defaults mysql mysql.server mysqld_safe mysqldump php php-config phpize httpd gettext ../bin/
cd ../
rm -fr bin.bak

# fix bug of the mysqld_safe
#sed -e 's/\/opt\/lampp\/\/opt\/lampp\/sbin/\/opt\/lampp\/sbin/g' bin/mysqld_safe > bin/mysqld_safe.new
#mv bin/mysqld_safe.new bin/mysqld_safe
#chmod a+rx bin/mysqld_safe

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
mv share.bak/xampp share
rm -fr share.bak

# process lib directory.
mkdir libnew
cp  lib/VERSION libnew/
cp  lib/libapr-1.so.0 libnew/
cp  lib/libaprutil-1.so.0 libnew/
cp  lib/libcrypto.so.1.0.0 libnew/
cp  lib/libcurl.so.4 libnew/
cp  lib/libexslt.so.0 libnew/
cp  lib/libfreetype.so.6 libnew/
cp  lib/libgcc_s.so.1 libnew/
cp  lib/libiconv.so.2 libnew/
cp  lib/liblber-2.4.so.2.5.4 libnew/
cp  lib/libmcrypt.so.4 libnew/
cp  lib/libncurses.so.5 libnew/
cp  lib/libpcre.so.0 libnew/
cp  lib/libsqlite3.so.0 libnew/
cp  lib/libssl.so.1.0.0 libnew/
cp  lib/libstdc++.so.5 libnew/
cp  lib/libsybdb.so.5 libnew/
cp  lib/libxml2.so.2 libnew/
cp  lib/libxslt.so.1 libnew/
cp  lib/libz.so.1 libnew/
cp  lib/libct.so.4 libnew/
cp  lib/libpng15.so.15 libnew/
cp  lib/libjpeg.so.8 libnew/
cp  lib/libbz2.so  libnew/
cp  lib/libicui18n.so.48  libnew/
cp  lib/libicuuc.so.48 libnew/
cp  lib/libicudata.so.48  libnew/
cp  lib/libicuio.so.48 libnew/
cp  lib/libbz2.so libnew/

rm -fr lib
mv libnew lib

# process var directory.
rm -fr var/perl
rm -fr var/proftpd*
rm -fr var/mysql/cdcol
rm -fr var/mysql/*.err
rm -fr var/mysql/*.pid
rm -fr var/mysql/ib*
rm -fr var/mysql/test
rm -fr var/mysql/phpmyadmin
rm -fr var/mysql/mysql_upgrade_info
chmod -R 777 var/mysql

# process modules directory.
rm -fr modules/httpd.exp
rm -fr modules/mod_perl.so
rm -fr modules/mod_actions.so
rm -fr modules/mod_allowmethods.so
rm -fr modules/mod_asis.so
rm -fr modules/mod_authn_dbm
rm -fr modules/mod_authn_anon
rm -fr modules/mod_authn_dbd
rm -fr modules/mod_authn_default
rm -fr modules/mod_authz_dbm.so
rm -fr modules/mod_authz_default.so
rm -fr modules/mod_authz_groupfile.so
rm -fr modules/mod_authz_owner.so
rm -fr modules/mod_auth_digest.so
rm -fr modules/mod_auth_form.so
rm -fr modules/mod_authn_anon.so
rm -fr modules/mod_authn_dbd.so
rm -fr modules/mod_authn_dbm.so
rm -fr modules/mod_authn_socache.so
rm -fr modules/mod_authnz_ldap.so
rm -fr modules/mod_authz_dbd.so
rm -fr modules/mod_bucketeer.so
rm -fr modules/mod_buffer.so
rm -fr modules/mod_cache*
rm -fr modules/mod_case**
rm -fr modules/mod_cern_meta.so
rm -fr modules/mod_cgi*
rm -fr modules/mod_charset_lite.so
rm -fr modules/mod_echo.so
rm -fr modules/mod_dav*.so
rm -fr modules/mod_dbd.so
rm -fr modules/mod_disk_cache.so
rm -fr modules/mod_dumpio.so
rm -fr modules/mod_ext_filter.so
rm -fr modules/mod_file_cache.so
rm -fr modules/mod_headers.so
rm -fr modules/mod_ident.so
rm -fr modules/mod_imagemap.so
rm -fr modules/mod_include.so
rm -fr modules/mod_info.so
rm -fr modules/mod_ldap.so
rm -fr modules/mod_log_debug.so
rm -fr modules/mod_logio.so
rm -fr modules/mod_lbmethod*
rm -fr modules/mod_mem_cache.so
rm -fr modules/mod_mime_magic.so
rm -fr modules/mod_negotiation.so
rm -fr modules/mod_proxy*
rm -fr modules/mod_reqtimeout.so
rm -fr modules/mod_ratelimit.so
rm -fr modules/mod_remoteip.so
rm -fr modules/mod_request.so
rm -fr modules/mod_sed.so
rm -fr modules/mod_sess*.so
rm -fr modules/mod_slotmem_shm.so
rm -fr modules/mod_socache*.so
rm -fr modules/mod_speling.so
rm -fr modules/mod_ssl.so
rm -fr modules/mod_status.so
rm -fr modules/mod_substitute.so
rm -fr modules/mod_suexec.so
rm -fr modules/mod_unique_id.so
rm -fr modules/mod_userdir.so
rm -fr modules/mod_usertrack.so
rm -fr modules/mod_version.so
rm -fr modules/mod_vhost_alias.so

# copy the zentao code.
mv ../../../zentaopms ./zentao

# copy needed files.
cp ../Makefile .
cp ../start .
cp ../start88 .
cp ../stop .
#cp ../lamppctl ./lampp
cp ../../windows/index.php htdocs/

# make the auth file
mkdir auth
touch auth/users
cp ../adduser.sh auth/

# process the phpmyadmin
tar xvf $1/phpmyadmin.tar.gz
mv phpmyadmin/locale phpmyadmin/locale.bak
mkdir phpmyadmin/locale
mv phpmyadmin/locale.bak/en_GB phpmyadmin/locale
mv phpmyadmin/locale.bak/zh_* phpmyadmin/locale
rm -fr phpmyadmin/locale.bak
cp ../../windows/phpmyadmin.php phpmyadmin/config.inc.php
rm -fr phpmyadmin/examples
rm -fr phpmyadmin/js/openlayers
rm -fr phpmyadmin/libraries/tcpdf
rm -fr phpmyadmin/Documentation*
rm -fr phpmyadmin/themes/original
rm -fr phpmyadmin/doc

# copy the ioncube loader.
cp ../*.so lib/

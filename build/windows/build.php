<?php
$buildDir = dirname(__FILE__);
chdir($buildDir);

/* include the file class. */
include '../../lib/zfile/zfile.class.php';
$file = new zfile();

/* set xampp package and 7-zip command. */
if(count($argv) != 4) die("php build.php xampp.7z 7zip output.\n");
$xampp      = $argv[1] . '\xampp.7z';
$phpmyadmin = $argv[1] . '\phpmyadmin.7z';
$sevenz     = $argv[2];
$output     = $argv[3];

chdir($output);

/* extract the xampp package. */
echo "extracting xampp package ...";
echo `$sevenz x -y $xampp`;
echo `$sevenz x -y $phpmyadmin`;

/* rm useless files. */
error_reporting(E_ALL);
$file->removeDir('./xampp/cgi-bin');
$file->removeDir('./xampp/contrib');
$file->removeDir('./xampp/install');
$file->removeDir('./xampp/licenses');
$file->removeDir('./xampp/locale');
$file->removeDir('./xampp/mailoutput');
$file->removeDir('./xampp/mailtodisk');
$file->removeDir('./xampp/perl');
$file->removeDir('./xampp/sendmail');
$file->removeDir('./xampp/security');
$file->batchRemoveFile('./xampp/tmp/*');
$file->removeDir('./xampp/webdav');
$file->batchRemoveFile('./xampp/*.txt');
$file->batchRemoveFile('./xampp/*.bat');
$file->batchRemoveFile('./xampp/*.exe');
$file->batchRemoveFile('./xampp/*.ini');

/* Process apache module. */
$file->batchRemoveFile('./xampp/apache/*.txt');
$file->batchRemoveFile('./xampp/apache/*.bat');
$file->rename('./xampp/apache/modules', './xampp/apache/modulesold');
$file->mkdir('./xampp/apache/modules');

$file->copyFile('./xampp/apache/modulesold/mod_access_compat.so', './xampp/apache/modules/mod_access_compat.so');
$file->copyFile('./xampp/apache/modulesold/mod_auth_basic.so',    './xampp/apache/modules/mod_auth_basic.so');
$file->copyFile('./xampp/apache/modulesold/mod_auth_basic.so',    './xampp/apache/modules/mod_auth_basic.so');
$file->copyFile('./xampp/apache/modulesold/mod_authz_core.so',    './xampp/apache/modules/mod_authz_core.so');
$file->copyFile('./xampp/apache/modulesold/mod_mime.so',          './xampp/apache/modules/mod_mime.so');
$file->copyFile('./xampp/apache/modulesold/mod_expires.so',       './xampp/apache/modules/mod_expires.so');
$file->copyFile('./xampp/apache/modulesold/mod_env.so',           './xampp/apache/modules/mod_env.so');
$file->copyFile('./xampp/apache/modulesold/mod_rewrite.so',       './xampp/apache/modules/mod_rewrite.so');
$file->copyFile('./xampp/apache/modulesold/mod_setenvif.so',      './xampp/apache/modules/mod_setenvif.so');
$file->copyFile('./xampp/apache/modulesold/mod_autoindex.so',     './xampp/apache/modules/mod_autoindex.so');
$file->copyFile('./xampp/apache/modulesold/mod_authz_user.so',    './xampp/apache/modules/mod_authz_user.so');
$file->copyFile('./xampp/apache/modulesold/mod_authz_host.so',    './xampp/apache/modules/mod_authz_host.so');
$file->copyFile('./xampp/apache/modulesold/mod_alias.so',         './xampp/apache/modules/mod_alias.so');
$file->copyFile('./xampp/apache/modulesold/mod_dir.so',           './xampp/apache/modules/mod_dir.so');
$file->copyFile('./xampp/apache/modulesold/mod_deflate.so',       './xampp/apache/modules/mod_deflate.so');
$file->copyFile('./xampp/apache/modulesold/mod_filter.so',        './xampp/apache/modules/mod_filter.so');
$file->copyFile('./xampp/apache/modulesold/mod_log_config.so',    './xampp/apache/modules/mod_log_config.so');
$file->removeDir('./xampp/apache/modulesold');

/* Remove apache's error, icons, include, lib, logs directory. */
$file->removeDir('./xampp/apache/cgi-bin');
$file->removeDir('./xampp/apache/error');
$file->removeDir('./xampp/apache/icons');
$file->removeDir('./xampp/apache/include');
$file->removeDir('./xampp/apache/lib');
$file->removeDir('./xampp/apache/conf/extra');
$file->batchRemoveFile('./xampp/apache/logs/*.log');
$file->batchRemoveFile('./xampp/apache/*.pl');
$file->removeDir('./xampp/apache/manual');

$file->rename('./xampp/apache/bin', './xampp/apache/binold');
$file->mkdir('./xampp/apache/bin');

$file->copyFile('./xampp/apache/binold/htpasswd.exe',      './xampp/apache/bin/htpasswd.exe');
$file->copyFile('./xampp/apache/binold/httpd.exe',         './xampp/apache/bin/httpd.exe');
$file->copyFile('./xampp/apache/binold/libapr-1.dll',      './xampp/apache/bin/libapr-1.dll');
$file->copyFile('./xampp/apache/binold/libapriconv-1.dll', './xampp/apache/bin/libapriconv-1.dll');
$file->copyFile('./xampp/apache/binold/libaprutil-1.dll',  './xampp/apache/bin/libaprutil-1.dll');
$file->copyFile('./xampp/apache/binold/libhttpd.dll',      './xampp/apache/bin/libhttpd.dll');
$file->copyFile('./xampp/apache/binold/zlib1.dll',         './xampp/apache/bin/zlib1.dll');
$file->copyFile('./xampp/apache/binold/pv.exe',            './xampp/apache/bin/pv.exe');
$file->copyFile('./xampp/apache/binold/libeay32.dll',      './xampp/apache/bin/libeay32.dll');
$file->copyFile('./xampp/apache/binold/ssleay32.dll',      './xampp/apache/bin/ssleay32.dll');
$file->copyFile('./xampp/apache/binold/pcre.dll',          './xampp/apache/bin/pcre.dll');
$file->copyFile('./xampp/apache/binold/msvcr100.dll',      './xampp/apache/bin/msvcr100.dll');
$file->removeDir('./xampp/apache/binold');

/* Process the apache's config file. */
$file->copyFile($buildDir . '/httpd.conf', './xampp/apache/conf/httpd.conf');

/* Remove useless config files. */
$file->removeDir('./xampp/apache/conf/ssl.crl');
$file->removeDir('./xampp/apache/conf/ssl.crt');
$file->removeDir('./xampp/apache/conf/ssl.csr');
$file->removeDir('./xampp/apache/conf/ssl.key');

/* Empty the htdocs directory. */
$file->removeDir('./xampp/htdocs');
$file->mkdir('./xampp/htdocs');

/* Process mysql. */
$file->removeDir('./xampp/mysql/backup');
$file->removeDir('./xampp/mysql/include');
$file->removeDir('./xampp/mysql/lib');
$file->removeDir('./xampp/mysql/scripts');
$file->removeDir('./xampp/mysql/sql-bench');

/* Process mysql's bin directory. */
$file->rename('./xampp/mysql/bin', './xampp/mysql/binold');
$file->mkdir('./xampp/mysql/bin');

$file->copyFile('./xampp/mysql/binold/mysql.exe',      './xampp/mysql/bin/mysql.exe');
$file->copyFile('./xampp/mysql/binold/mysqld.exe',     './xampp/mysql/bin/mysqld.exe');
$file->copyFile('./xampp/mysql/binold/mysqldump.exe',  './xampp/mysql/bin/mysqldump.exe');
$file->copyFile('./xampp/mysql/binold/myisamchk.exe',  './xampp/mysql/bin/myisamchk.exe');
$file->removeFile('./xampp/mysql/binold/my.ini');

$file->removeDir('./xampp/mysql/binold');

/* Process mysql's share diectory. */
$file->rename('./xampp/mysql/share', './xampp/mysql/shareold');
$file->mkdir('./xampp/mysql/share');
$file->mkdir('./xampp/mysql/share/english');
$file->copyFile('./xampp/mysql/shareold/english/errmsg.sys', './xampp/mysql/share/english/errmsg.sys');
$file->removeDir('../xampp/mysql/shareold');

/* Process mysql's data directory. */
$file->removeDir('./xampp/mysql/data/test');
$file->removeDir('./xampp/mysql/data/webauth');
$file->removeDir('./xampp/mysql/data/cdcol');
$file->removeDir('./xampp/mysql/data/phpmyadmin');
$file->batchRemoveFile('./xampp/mysql/data/ib*');
$file->batchRemoveFile('./xampp/mysql/data/mysql*');

/* Remove mysql's useless config files. */
$file->batchRemoveFile('./xampp/mysql/my-*');
$file->removeFile('./xampp/mysql/README');
$file->removeFile('./xampp/mysql/COPYING');

/* Process mysql's conf file. */
$file->copyFile($buildDir . '/my.ini', './xampp/mysql/my.ini');

/* Processing php. */
$file->removeDir('./xampp/php/cfg');
$file->removeDir('./xampp/php/data');
$file->removeDir('./xampp/php/DB');
$file->removeDir('./xampp/php/dev');
$file->removeDir('./xampp/php/docs');
$file->removeDir('./xampp/php/PEAR');
$file->removeDir('./xampp/php/tests');
$file->removeDir('./xampp/php/Text');
$file->removeDir('./xampp/php/tmp');
$file->removeDir('./xampp/php/www');
$file->removeDir('./xampp/php/scripts');
$file->batchRemoveFile('./xampp/php/dbunit*');
$file->batchRemoveFile('./xampp/php/*.bat');
$file->batchRemoveFile('./xampp/php/*.txt');
$file->batchRemoveFile('./xampp/php/php.ini-*');
$file->batchRemoveFile('./xampp/php/*.reg');
$file->batchRemoveFile('./xampp/php/pci*');
$file->batchRemoveFile('./xampp/php/*.phar');
$file->batchRemoveFile('./xampp/php/php-*.exe');
$file->batchRemoveFile('./xampp/php/phpcov');
$file->batchRemoveFile('./xampp/php/phptok');
$file->batchRemoveFile('./xampp/php/phpunit');
$file->batchRemoveFile('./xampp/php/*.php');
$file->batchRemoveFile('./xampp/php/phpcs');
$file->batchRemoveFile('./xampp/php/phpdoc');
$file->batchRemoveFile('./xampp/php/phpuml');
$file->batchRemoveFile('./xampp/php/*.sh');
$file->batchRemoveFile('./xampp/php/logs/*');

$file->removeDir('./xampp/php/extras/fonts');
$file->removeDir('./xampp/php/extras/mibs');
$file->removeDir('./xampp/php/extras/openssl');
$file->removeFile('./xampp/php/extras/openssl.cnf');

$file->rename('./xampp/php/php5apache2_4.dll', './xampp/php/php5apache2_4.bak');
$file->rename('./xampp/php/php5ts.dll',   './xampp/php/php5ts.bak');
$file->rename('./xampp/php/ssleay32.dll', './xampp/php/ssleay32.dll.bak');
$file->rename('./xampp/php/libeay32.dll', './xampp/php/libeay32.dll.bak');
$file->rename('./xampp/php/libsasl.dll',  './xampp/php/libsasl.dll.bak');
$file->batchRemoveFile('./xampp/php/*.dll');
$file->rename('./xampp/php/php5apache2_4.bak', './xampp/php/php5apache2_4.dll');
$file->rename('./xampp/php/php5ts.bak', './xampp/php/php5ts.dll');
$file->rename('./xampp/php/ssleay32.dll.bak', './xampp/php/ssleay32.dll');
$file->rename('./xampp/php/libeay32.dll.bak', './xampp/php/libeay32.dll');
$file->copyFile('./xampp/php/libsasl.dll.bak', './xampp/apache/bin/libsasl.dll');
$file->rename('./xampp/php/libsasl.dll.bak',  './xampp/php/libsasl.dll');

/* Process php ini file. */
$file->copyFile($buildDir . '/php.ini', './xampp/php/php.ini');

/* Process php's ext directory. */
$file->rename('./xampp/php/ext', './xampp/php/extold');
$file->mkdir('./xampp/php/ext');
$file->copyFile('./xampp/php/extold/php_bz2.dll',       './xampp/php/ext/php_bz2.dll');
$file->copyFile('./xampp/php/extold/php_gd2.dll',       './xampp/php/ext/php_gd2.dll');
$file->copyFile('./xampp/php/extold/php_imap.dll',      './xampp/php/ext/php_imap.dll');
$file->copyFile('./xampp/php/extold/php_mbstring.dll',  './xampp/php/ext/php_mbstring.dll');
$file->copyFile('./xampp/php/extold/php_mysql.dll',     './xampp/php/ext/php_mysql.dll');
$file->copyFile('./xampp/php/extold/php_mysqli.dll',    './xampp/php/ext/php_mysqli.dll');
$file->copyFile('./xampp/php/extold/php_pdo_mysql.dll', './xampp/php/ext/php_pdo_mysql.dll');
$file->copyFile('./xampp/php/extold/php_sockets.dll',   './xampp/php/ext/php_sockets.dll');
$file->copyFile('./xampp/php/extold/php_openssl.dll',   './xampp/php/ext/php_openssl.dll');
$file->copyFile('./xampp/php/extold/php_ldap.dll',      './xampp/php/ext/php_ldap.dll');
$file->copyFile('./xampp/php/extold/php_curl.dll',      './xampp/php/ext/php_curl.dll');
$file->removeDir('./xampp/php/extold');

/* Process phpmyadmin. */
$file->removeDir('./xampp/phpMyAdmin');
$file->copyDir('./phpMyAdmin-3.5.5-all-languages', './xampp/phpmyadmin/');
$file->mkdir('./xampp/phpmyadmin/locale.new');
$file->copyDir('./xampp/phpmyadmin/locale/zh_CN', './xampp/phpmyadmin/locale.new/zh_CN');
$file->copyDir('./xampp/phpmyadmin/locale/zh_TW', './xampp/phpmyadmin/locale.new/zh_TW');
$file->copyDir('./xampp/phpmyadmin/locale/en_GB', './xampp/phpmyadmin/locale.new/en_GB');
$file->removeDir('./xampp/phpmyadmin/locale');
$file->rename('./xampp/phpmyadmin/locale.new', './xampp/phpmyadmin/locale');

/* Process the svn. */
$file->copyDir($buildDir . '/svn/silksvn/', './xampp/silksvn');
$file->mkdir('./xampp/zentao/module/svn/ext/config');
$file->copyFile($buildDir . '/svn/svn.php', './xampp/zentao/module/svn/ext/config/svn.php');

/* Copy index.php. */
$file->copyFile($buildDir . '/index.php', './xampp/htdocs/index.php');

/* Copy ioncube loader. */
$file->copyFile($buildDir . '/ioncube_loader_win_5.4.dll', './xampp/php/ext/ioncube_loader_win_5.4.dll');

/* Copy serive bat file. */
$file->copyDir($buildDir . '/services', './xampp/services');

/* Copy the readme.txt. */
$file->copyFile($buildDir . '/readme.txt', './xampp/readme.txt');

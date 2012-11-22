<?php
$buildDir = dirname(__FILE__);
chdir($buildDir);

/* include the file class. */
include '../../../lib/file/file.class.php';
$file = new file();

/* set xampp package and 7-zip command. */
if(count($argv) != 3) die("please specify the package directory and 7z command.\n");
$xampp    = $argv[1] . '/xampp.7z';
$sqlbuddy = $argv[1] . '/sqlbuddy.zip';
$sevenz   = $argv[2];

chdir('c:/');

/* extract the xampp package. */
echo "extracting xampp package ...";
echo `$sevenz x -y $xampp`;

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
$file->copyFile('./xampp/apache/modulesold/mod_mime_magic.so',    './xampp/apache/modules/mod_mime_magic.so');
$file->copyFile('./xampp/apache/modulesold/mod_mime.so',          './xampp/apache/modules/mod_mime.so');
$file->copyFile('./xampp/apache/modulesold/mod_expires.so',       './xampp/apache/modules/mod_expires.so');
$file->copyFile('./xampp/apache/modulesold/mod_env.so',           './xampp/apache/modules/mod_env.so');
$file->copyFile('./xampp/apache/modulesold/mod_rewrite.so',       './xampp/apache/modules/mod_rewrite.so');
$file->copyFile('./xampp/apache/modulesold/mod_vhost_alias.so',   './xampp/apache/modules/mod_vhost_alias.so');
$file->copyFile('./xampp/apache/modulesold/mod_setenvif.so',      './xampp/apache/modules/mod_setenvif.so');
$file->copyFile('./xampp/apache/modulesold/mod_autoindex.so',     './xampp/apache/modules/mod_autoindex.so');
$file->copyFile('./xampp/apache/modulesold/mod_authz_user.so',    './xampp/apache/modules/mod_authz_user.so');
$file->copyFile('./xampp/apache/modulesold/mod_authz_host.so',    './xampp/apache/modules/mod_authz_host.so');
$file->copyFile('./xampp/apache/modulesold/mod_alias.so',         './xampp/apache/modules/mod_alias.so');
$file->copyFile('./xampp/apache/modulesold/mod_dir.so',           './xampp/apache/modules/mod_dir.so');
$file->copyFile('./xampp/apache/modulesold/mod_deflate.so',       './xampp/apache/modules/mod_deflate.so');
$file->copyFile('./xampp/apache/modulesold/mod_filter.so',        './xampp/apache/modules/mod_filter.so');
$file->removeDir('./xampp/apache/modulesold');

/* Remove apache's error, icons, include, lib, logs directory. */
$file->removeDir('./xampp/apache/cgi-bin');
$file->removeDir('./xampp/apache/error');
$file->removeDir('./xampp/apache/icons');
$file->removeDir('./xampp/apache/include');
$file->removeDir('./xampp/apache/lib');
$file->batchRemoveFile('./xampp/apache/logs/*.log');
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
$file->removeDir('./xampp/apache/binold');

/* Process the apache's config file. */
$httpdConf = file_get_contents('./xampp/apache/conf/httpd.conf');
$httpdConf = str_replace('LoadModule actions_module modules/mod_actions.so',                '#LoadModule actions_module modules/mod_actions.so', $httpdConf);
$httpdConf = str_replace('LoadModule allowmethods_module modules/mod_allowmethods.so',      '#LoadModule allowmethods_module modules/mod_allowmethods.so', $httpdConf);
$httpdConf = str_replace('LoadModule asis_module modules/mod_asis.so',                      '#LoadModule asis_module modules/mod_asis.so', $httpdConf);
$httpdConf = str_replace('LoadModule authn_core_module modules/mod_authn_core.so',          '#LoadModule authn_default_module modules/mod_authn_core.so', $httpdConf);
$httpdConf = str_replace('LoadModule authn_file_module modules/mod_authn_file.so',          '#LoadModule authn_file_module modules/mod_authn_file.so', $httpdConf);
$httpdConf = str_replace('LoadModule authz_groupfile_module modules/mod_authz_groupfile.so','#LoadModule authz_groupfile_module modules/mod_authz_groupfile.so', $httpdConf);
$httpdConf = str_replace('LoadModule cgi_module modules/mod_cgi.so',                        '#LoadModule cgi_module modules/mod_cgi.so', $httpdConf);
$httpdConf = str_replace('LoadModule dav_lock_module modules/mod_dav_lock.so',              '#LoadModule dav_lock_module modules/mod_dav_lock.so', $httpdConf);
$httpdConf = str_replace('LoadModule headers_module modules/mod_headers.so',                '#LoadModule headers_module modules/mod_headers.so', $httpdConf);
$httpdConf = str_replace('LoadModule include_module modules/mod_include.so',                '#LoadModule include_module modules/mod_include.so', $httpdConf);
$httpdConf = str_replace('LoadModule info_module modules/mod_info.so',                      '#LoadModule info_module modules/mod_info.so', $httpdConf);
$httpdConf = str_replace('LoadModule isapi_module modules/mod_isapi.so',                    '#LoadModule isapi_module modules/mod_isapi.so', $httpdConf);
$httpdConf = str_replace('LoadModule log_config_module modules/mod_log_config.so',          '#LoadModule log_config_module modules/mod_log_config.so', $httpdConf);
$httpdConf = str_replace('LoadModule cache_disk_module modules/mod_cache_disk.so',          '#LoadModule log_config_module modules/mod_cache_disk.so', $httpdConf);
$httpdConf = str_replace('LoadModule negotiation_module modules/mod_negotiation.so',        '#LoadModule negotiation_module modules/mod_negotiation.so', $httpdConf);
$httpdConf = str_replace('LoadModule proxy_module modules/mod_proxy.so',                    '#LoadModule proxy_module modules/mod_proxy.so', $httpdConf);
$httpdConf = str_replace('LoadModule proxy_ajp_module modules/mod_proxy_ajp.so',            '#LoadModule proxy_ajp_module modules/mod_proxy_ajp.so', $httpdConf);
$httpdConf = str_replace('LoadModule ssl_module modules/mod_ssl.so',                        '#LoadModule ssl_module modules/mod_ssl.so', $httpdConf);
$httpdConf = str_replace('LoadModule status_module modules/mod_status.so',                  '#LoadModule status_module modules/mod_status.so', $httpdConf);
$httpdConf = str_replace('#LoadModule deflate_module modules/mod_deflate.so',               'LoadModule deflate_module modules/mod_deflate.so', $httpdConf);
$httpdConf = str_replace('#LoadModule expires_module modules/mod_expires.so',               'LoadModule expires_module modules/mod_expires.so', $httpdConf);
$httpdConf = str_replace('#LoadModule filter_module modules/mod_filter.so',                 'LoadModule filter_module modules/mod_filter.so', $httpdConf);

$httpdConf = str_replace('Include "conf/extra/httpd-multilang-errordoc.conf"', '#Include "conf/extra/httpd-multilang-errordoc.conf"', $httpdConf);
$httpdConf = str_replace('Include "conf/extra/httpd-userdir.conf"',            '#Include "conf/extra/httpd-userdir.conf"', $httpdConf);
$httpdConf = str_replace('Include "conf/extra/httpd-info.conf"',               '#Include "conf/extra/httpd-info.conf"', $httpdConf);
$httpdConf = str_replace('Include "conf/extra/httpd-proxy.conf"',              '#Include "conf/extra/httpd-proxy.conf"', $httpdConf);
$httpdConf = str_replace('Include "conf/extra/httpd-ssl.conf"',                '#Include "conf/extra/httpd-ssl.conf"', $httpdConf);

$httpdConf = explode("\n", $httpdConf);
foreach($httpdConf as $key => $line)
{
    $line = trim($line);
    if(empty($line) or substr($line, 0, 1) == '#') unset($httpdConf[$key]);
}
$httpdConf = join("\n", $httpdConf);
$httpdConf = str_replace('Options Indexes', 'Options', $httpdConf);    // Turn off the directory index feature.
$httpdConf = str_replace('ht*', 'zt*', $httpdConf);                    // Deny the access of .ztaccess.
file_put_contents('./xampp/apache/conf/httpd.conf', $httpdConf);

/* Move .htacces to .ztaccess. */
$httpdDefaultConfig = './xampp/apache/conf/extra/httpd-default.conf';
file_put_contents($httpdDefaultConfig, str_replace('.htaccess', '.ztaccess', file_get_contents($httpdDefaultConfig)));

/* Remove useless config files. */
$file->removeDir('./xampp/apache/conf/ssl.crl');
$file->removeDir('./xampp/apache/conf/ssl.crt');
$file->removeDir('./xampp/apache/conf/ssl.csr');
$file->removeDir('./xampp/apache/conf/ssl.key');
$file->removeFile('./xampp/apache/conf/extra/httpd-ajp.conf');
$file->removeFile('./xampp/apache/conf/extra/httpd-proxy.conf');
$file->removeFile('./xampp/apache/conf/extra/httpd-perl.conf');
$file->removeFile('./xampp/apache/conf/extra/httpd-dav.conf');
$file->removeFile('./xampp/apache/conf/extra/httpd-info.conf');
$file->removeFile('./xampp/apache/conf/extra/httpd-multilang-errordoc.conf');
$file->removeFile('./xampp/apache/conf/extra/httpd-ssl.conf');
$file->removeFile('./xampp/apache/conf/extra/httpd-userdir.conf');

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
$file->rename('./xampp/mysql/binold/my.ini',         './xampp/mysql/my.ini');

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
$file->batchRemoveFile('./xampp/mysql/data/ib*');
$file->batchRemoveFile('./xampp/mysql/data/mysql*');

/* Remove mysql's useless config files. */
$file->batchRemoveFile('./xampp/mysql/my-*');
$file->removeFile('./xampp/mysql/README');
$file->removeFile('./xampp/mysql/COPYING');

/* Process mysql's conf file. */
$myConf = file_get_contents('./xampp/mysql/my.ini');
$myConf = str_replace('#bind-address="127.0.0.1"', 'bind-address="127.0.0.1"', $myConf);
$myConf = str_replace('#skip-innodb', "default-storage-engine=MyISAM\nskip-innodb\n", $myConf);

$myConf = explode("\n", $myConf);
foreach($myConf as $key => $line)
{
    $line = trim($line);
    if(empty($line) or substr($line, 0, 1) == '#') unset($myConf[$key]);
    if(stripos($line, 'innodb') === 0)             unset($myConf[$key]);
}
$myConf = join("\n", $myConf);
$myConf = str_replace('', '', $myConf);
file_put_contents('./xampp/mysql/my.ini', $myConf);

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
$phpConfig = file_get_contents('./xampp/php/php.ini');
$phpConfig = str_replace('extension=php_exif.dll',';extension=php_exif.dll', $phpConfig);
$phpConfig = str_replace('extension=php_gettext.dll',';extension=php_gettext.dll', $phpConfig);
$phpConfig = str_replace('extension=php_pdo_odbc.dll',';extension=php_pdo_odbc.dll', $phpConfig);
$phpConfig = str_replace('extension=php_pdo_sqlite.dll',';extension=php_pdo_sqlite.dll', $phpConfig);
$phpConfig = str_replace('extension=php_soap.dll',';extension=php_soap.dll', $phpConfig);
$phpConfig = str_replace('extension=php_sqlite.dll',';extension=php_sqlite.dll', $phpConfig);
$phpConfig = str_replace('extension=php_xsl.dll',';extension=php_xsl.dll', $phpConfig);
$phpConfig = str_replace('extension=php_sqlite3.dll','extension=php_ldap.dll',   $phpConfig);    // load ldap extension.
$phpConfig = str_replace('extension=php_xmlrpc.dll','extension=php_openssl.dll', $phpConfig);    // load openssl extension.

/* Remove empty and comment lines. */
$phpConfig = explode("\n", $phpConfig);
foreach($phpConfig as $key => $line)
{
    $line = trim($line);
    if(empty($line))                             unset($phpConfig[$key]);
    if(substr($line, 0, 1)            == ';')    unset($phpConfig[$key]);
    if(stripos($line, 'odbc')         !== false) unset($phpConfig[$key]);
    if(stripos($line, 'interbase')    !== false) unset($phpConfig[$key]);
    if(stripos($line, 'ibase')        !== false) unset($phpConfig[$key]);
    if(stripos($line, 'oci8')         !== false) unset($phpConfig[$key]);
    if(stripos($line, 'postgresql')   !== false) unset($phpConfig[$key]);
    if(stripos($line, 'pgsql')        !== false) unset($phpConfig[$key]);
    if(stripos($line, 'sybase')       !== false) unset($phpConfig[$key]);
    if(stripos($line, 'sybct')        !== false) unset($phpConfig[$key]);
    if(stripos($line, 'mssql')        !== false) unset($phpConfig[$key]);
    if(stripos($line, 'soap')         !== false) unset($phpConfig[$key]);
    if(stripos($line, 'eaccelerator') !== false) unset($phpConfig[$key]);
    if(stripos($line, 'xdebug')       !== false) unset($phpConfig[$key]);
}
$phpConfig = join("\n", $phpConfig);
$phpConfig = 'zend_extension = "\xampp\php\ext\ioncube_loader_win_5.4.dll"' . "\n" . $phpConfig;

file_put_contents('./xampp/php/php.ini', str_replace('', '', $phpConfig));

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

/* Process control panel. */

/* Process phpmyadmin. */
$file->mkdir('./xampp/admin');
$file->rename('./xampp/phpMyAdmin', './xampp/admin/phpmyadmin');
$file->rename('./xampp/admin/phpmyadmin/locale', './xampp/admin/phpmyadmin/locale.old');
$file->mkdir('./xampp/admin/phpmyadmin/locale');
$file->rename('./xampp/admin/phpmyadmin/locale.old/en_GB', './xampp/admin/phpmyadmin/locale/en_GB');
$file->rename('./xampp/admin/phpmyadmin/locale.old/zh_CN', './xampp/admin/phpmyadmin/locale/zh_CN');
$file->rename('./xampp/admin/phpmyadmin/locale.old/zh_TW', './xampp/admin/phpmyadmin/locale/zh_TW');
$file->removeDir('./xampp/admin/phpmyadmin/locale.old');

/* Copy index.php. */
$file->copyFile($buildDir . '/index.php', './xampp/htdocs/index.php');

/* Copy zentao.conf. */
$file->copyFile($buildDir . '/zentao.conf', './xampp/apache/conf/extra/httpd-xampp.conf');

/* Copy ioncube loader. */
$file->copyFile($buildDir . '/ioncube_loader_win_5.4.dll', './xampp/php/ext/ioncube_loader_win_5.4.dll');

/* Copy serive bat file. */
$file->copyFile($buildDir . '/apache_installservice.bat',   './xampp/apache_installservice.bat');
$file->copyFile($buildDir . '/apache_uninstallservice.bat', './xampp/apache_uninstallservice.bat');
$file->copyFile($buildDir . '/mysql_installservice.bat',    './xampp/mysql_installservice.bat');
$file->copyFile($buildDir . '/mysql_uninstallservice.bat',  './xampp/mysql_uninstallservice.bat');

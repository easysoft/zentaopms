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
$file->removeDir('./xampp/anonymous');
$file->removeDir('./xampp/cgi-bin');
$file->removeDir('./xampp/contrib');
$file->removeDir('./xampp/contrib');
$file->removeDir('./xampp/install');
$file->removeDir('./xampp/nsi');
$file->removeDir('./xampp/perl');
$file->removeDir('./xampp/phpmyadmin');
$file->removeDir('./xampp/sendmail');
$file->removeDir('./xampp/security');
$file->removeDir('./xampp/src');
$file->batchRemoveFile('./xampp/tmp/*');
$file->removeDir('./xampp/webdav');
$file->removeDir('./xampp/setup_xampp.bat');
$file->batchRemoveFile('./xampp/*.txt');

/* Process apache module. */
$file->batchRemoveFile('./xampp/apache/*.txt');
$file->batchRemoveFile('./xampp/apache/*.bat');
$file->rename('./xampp/apache/modules', './xampp/apache/modulesold');
$file->mkdir('./xampp/apache/modules');

$file->copyFile('./xampp/apache/modulesold/mod_auth_basic.so', './xampp/apache/modules/mod_auth_basic.so');
$file->copyFile('./xampp/apache/modulesold/mod_mime_magic.so', './xampp/apache/modules/mod_mime_magic.so');
$file->copyFile('./xampp/apache/modulesold/mod_mime.so',       './xampp/apache/modules/mod_mime.so');
$file->copyFile('./xampp/apache/modulesold/mod_expires.so',    './xampp/apache/modules/mod_expires.so');
$file->copyFile('./xampp/apache/modulesold/mod_env.so',        './xampp/apache/modules/mod_env.so');
$file->copyFile('./xampp/apache/modulesold/mod_rewrite.so',    './xampp/apache/modules/mod_rewrite.so');
$file->copyFile('./xampp/apache/modulesold/mod_vhost_alias.so','./xampp/apache/modules/mod_vhost_alias.so');
$file->copyFile('./xampp/apache/modulesold/mod_setenvif.so',   './xampp/apache/modules/mod_setenvif.so');
$file->copyFile('./xampp/apache/modulesold/mod_autoindex.so',  './xampp/apache/modules/mod_autoindex.so');
$file->copyFile('./xampp/apache/modulesold/mod_authz_user.so', './xampp/apache/modules/mod_authz_user.so');
$file->copyFile('./xampp/apache/modulesold/mod_authz_host.so', './xampp/apache/modules/mod_authz_host.so');
$file->copyFile('./xampp/apache/modulesold/mod_alias.so',      './xampp/apache/modules/mod_alias.so');
$file->copyFile('./xampp/apache/modulesold/mod_dir.so',        './xampp/apache/modules/mod_dir.so');
$file->copyFile('./xampp/apache/modulesold/mod_deflate.so',    './xampp/apache/modules/mod_deflate.so');
$file->removeDir('./xampp/apache/modulesold');

/* Remove apache's error, icons, include, lib, logs directory. */
$file->removeDir('./xampp/apache/error');
$file->removeDir('./xampp/apache/icons');
$file->removeDir('./xampp/apache/include');
$file->removeDir('./xampp/apache/lib');
$file->batchRemoveFile('./xampp/apache/logs/*.log');

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
$file->removeDir('./xampp/apache/binold');

/* Process the apache's config file. */
$httpdConf = file_get_contents('./xampp/apache/conf/httpd.conf');
$httpdConf = str_replace('LoadModule actions_module modules/mod_actions.so', '#LoadModule actions_module modules/mod_actions.so', $httpdConf);
$httpdConf = str_replace('LoadModule actions_module modules/mod_actions.so', '#LoadModule actions_module modules/mod_actions.so', $httpdConf);
$httpdConf = str_replace('LoadModule actions_module modules/mod_actions.so', '#LoadModule actions_module modules/mod_actions.so', $httpdConf);
$httpdConf = str_replace('LoadModule asis_module modules/mod_asis.so',       '#LoadModule asis_module modules/mod_asis.so', $httpdConf);
$httpdConf = str_replace('LoadModule auth_digest_module modules/mod_auth_digest.so',        '#LoadModule auth_digest_module modules/mod_auth_digest.so', $httpdConf);
$httpdConf = str_replace('LoadModule authn_default_module modules/mod_authn_default.so',    '#LoadModule authn_default_module modules/mod_authn_default.so', $httpdConf);
$httpdConf = str_replace('LoadModule authn_file_module modules/mod_authn_file.so',          '#LoadModule authn_file_module modules/mod_authn_file.so', $httpdConf);
$httpdConf = str_replace('LoadModule authz_default_module modules/mod_authz_default.so',    '#LoadModule authz_default_module modules/mod_authz_default.so', $httpdConf);

$httpdConf = file_get_contents('./xampp/apache/conf/httpd.conf');
$httpdConf = str_replace('LoadModule actions_module modules/mod_actions.so', '#LoadModule actions_module modules/mod_actions.so', $httpdConf);
$httpdConf = str_replace('LoadModule actions_module modules/mod_actions.so', '#LoadModule actions_module modules/mod_actions.so', $httpdConf);
$httpdConf = str_replace('LoadModule actions_module modules/mod_actions.so', '#LoadModule actions_module modules/mod_actions.so', $httpdConf);
$httpdConf = str_replace('LoadModule asis_module modules/mod_asis.so',       '#LoadModule asis_module modules/mod_asis.so', $httpdConf);
$httpdConf = str_replace('LoadModule auth_digest_module modules/mod_auth_digest.so',        '#LoadModule auth_digest_module modules/mod_auth_digest.so', $httpdConf);
$httpdConf = str_replace('LoadModule authn_default_module modules/mod_authn_default.so',    '#LoadModule authn_default_module modules/mod_authn_default.so', $httpdConf);
$httpdConf = str_replace('LoadModule authn_file_module modules/mod_authn_file.so',          '#LoadModule authn_file_module modules/mod_authn_file.so', $httpdConf);
$httpdConf = str_replace('LoadModule authz_default_module modules/mod_authz_default.so',    '#LoadModule authz_default_module modules/mod_authz_default.so', $httpdConf);
$httpdConf = str_replace('LoadModule authz_groupfile_module modules/mod_authz_groupfile.so','#LoadModule authz_groupfile_module modules/mod_authz_groupfile.so', $httpdConf);
$httpdConf = str_replace('LoadModule cgi_module modules/mod_cgi.so',                        '#LoadModule cgi_module modules/mod_cgi.so', $httpdConf);
$httpdConf = str_replace('LoadModule dav_lock_module modules/mod_dav_lock.so',              '#LoadModule dav_lock_module modules/mod_dav_lock.so', $httpdConf);
$httpdConf = str_replace('LoadModule headers_module modules/mod_headers.so',                '#LoadModule headers_module modules/mod_headers.so', $httpdConf);
$httpdConf = str_replace('LoadModule include_module modules/mod_include.so',                '#LoadModule include_module modules/mod_include.so', $httpdConf);
$httpdConf = str_replace('LoadModule info_module modules/mod_info.so',                      '#LoadModule info_module modules/mod_info.so', $httpdConf);
$httpdConf = str_replace('LoadModule isapi_module modules/mod_isapi.so',                    '#LoadModule isapi_module modules/mod_isapi.so', $httpdConf);
$httpdConf = str_replace('LoadModule log_config_module modules/mod_log_config.so',          '#LoadModule log_config_module modules/mod_log_config.so', $httpdConf);
$httpdConf = str_replace('LoadModule negotiation_module modules/mod_negotiation.so',        '#LoadModule negotiation_module modules/mod_negotiation.so', $httpdConf);
$httpdConf = str_replace('LoadModule proxy_module modules/mod_proxy.so',                    '#LoadModule proxy_module modules/mod_proxy.so', $httpdConf);
$httpdConf = str_replace('LoadModule proxy_ajp_module modules/mod_proxy_ajp.so',            '#LoadModule proxy_ajp_module modules/mod_proxy_ajp.so', $httpdConf);
$httpdConf = str_replace('LoadModule ssl_module modules/mod_ssl.so',                        '#LoadModule ssl_module modules/mod_ssl.so', $httpdConf);
$httpdConf = str_replace('LoadModule status_module modules/mod_status.so',                  '#LoadModule status_module modules/mod_status.so', $httpdConf);
$httpdConf = str_replace('#LoadModule deflate_module modules/mod_deflate.so',               'LoadModule deflate_module modules/mod_deflate.so', $httpdConf);

$httpdConf = str_replace('Include "conf/extra/httpd-perl.conf"',               '#Include "conf/extra/httpd-perl.conf"', $httpdConf);
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
file_put_contents('./xampp/apache/conf/httpd.conf',   str_replace('88', '80', $httpdConf));
file_put_contents('./xampp/apache/conf/httpd80.conf', str_replace('88', '80', $httpdConf));
file_put_contents('./xampp/apache/conf/httpd88.conf', str_replace('80', '88', $httpdConf));

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
$file->copyFile('./xampp/mysql/binold/my.ini',         './xampp/mysql/bin/my.ini');

$file->removeDir('./xampp/mysql/binold');

/* Process mysql's share diectory. */
$file->rename('./xampp/mysql/share', './xampp/mysql/shareold');
$file->mkdir('./xampp/mysql/share');
$file->mkdir('./xampp/mysql/share/english');
$file->copyFile('./xampp/mysql/shareold/english/errmsg.sys', './xampp/mysql/share/english/errmsg.sys');
$file->removeDir('../xampp/mysql/shareold');

/* Process mysql's data directory. */
$file->removeDir('./xampp/mysql/data/phpmyadmin');
$file->removeDir('./xampp/mysql/data/test');
$file->removeDir('./xampp/mysql/data/webauth');
$file->removeDir('./xampp/mysql/data/cdcol');
$file->batchRemoveFile('./xampp/mysql/data/ib*');
$file->batchRemoveFile('./xampp/mysql/data/mysql*');

/* Remove mysql's useless config files. */
$file->batchRemoveFile('./xampp/mysql/*.ini');
$file->removeFile('./xampp/mysql/README');
$file->removeFile('./xampp/mysql/COPYING');

/* Process mysql's conf file. */
$myConf = file_get_contents('./xampp/mysql/bin/my.ini');
$myConf = str_replace('#bind-address="127.0.0.1"', 'bind-address="127.0.0.1"', $myConf);
$myConf = explode("\n", $myConf);
foreach($myConf as $key => $line)
{
    $line = trim($line);
    if(empty($line) or substr($line, 0, 1) == '#') unset($myConf[$key]);
}
$myConf = join("\n", $myConf);
file_put_contents('./xampp/mysql/bin/my.ini',     str_replace('3308', '3306', $myConf));
file_put_contents('./xampp/mysql/bin/my3306.ini', str_replace('3308', '3306', $myConf));
file_put_contents('./xampp/mysql/bin/my3308.ini', str_replace('3306', '3308', $myConf));

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

$file->rename('./xampp/php/php5apache2_2.dll', './xampp/php/php5apache2_2.bak');
$file->rename('./xampp/php/php5ts.dll', './xampp/php/php5ts.bak');
$file->batchRemoveFile('./xampp/php/*.dll');
$file->rename('./xampp/php/php5apache2_2.bak', './xampp/php/php5apache2_2.dll');
$file->rename('./xampp/php/php5ts.bak', './xampp/php/php5ts.dll');

/* Process php ini file. */
$phpConfig = file_get_contents('./xampp/php/php.ini');
$phpConfig = str_replace('extension=php_curl.dll',';extension=php_curl.dll', $phpConfig);
$phpConfig = str_replace('extension=php_exif.dll',';extension=php_exif.dll', $phpConfig);
$phpConfig = str_replace('extension=php_gettext.dll',';extension=php_gettext.dll', $phpConfig);
$phpConfig = str_replace('extension=php_pdo_odbc.dll',';extension=php_pdo_odbc.dll', $phpConfig);
$phpConfig = str_replace('extension=php_pdo_sqlite.dll',';extension=php_pdo_sqlite.dll', $phpConfig);
$phpConfig = str_replace('extension=php_soap.dll',';extension=php_soap.dll', $phpConfig);
$phpConfig = str_replace('extension=php_sqlite.dll',';extension=php_sqlite.dll', $phpConfig);
$phpConfig = str_replace('extension=php_sqlite3.dll',';extension=php_sqlite3.dll', $phpConfig);
$phpConfig = str_replace('extension=php_xmlrpc.dll',';extension=php_xmlrpc.dll', $phpConfig);

/* Remove empty and comment lines. */
$phpConfig = explode("\n", $phpConfig);
foreach($phpConfig as $key => $line)
{
    $line = trim($line);
    if(empty($line)) unset($phpConfig[$key]);
    if(substr($line, 0, 1) == ';') unset($phpConfig[$key]);
}
$phpConfig = join("\n", $phpConfig);
$phpConfig = 'zend_extension = "\xampp\php\ext\ioncube_loader_win_5.3.dll"' . "\n" . $phpConfig;

file_put_contents('./xampp/php/php.ini', $phpConfig);

/* Process php's ext directory. */
$file->rename('./xampp/php/ext', './xampp/php/extold');
$file->mkdir('./xampp/php/ext');
$file->copyFile('./xampp/php/extold/php_bz2.dll',             './xampp/php/ext/php_bz2.dll');
$file->copyFile('./xampp/php/extold/php_eaccelerator_ts.dll', './xampp/php/ext/php_eaccelerator_ts.dll');
$file->copyFile('./xampp/php/extold/php_gd2.dll',             './xampp/php/ext/php_gd2.dll');
$file->copyFile('./xampp/php/extold/php_imap.dll',            './xampp/php/ext/php_imap.dll');
$file->copyFile('./xampp/php/extold/php_mbstring.dll',        './xampp/php/ext/php_mbstring.dll');
$file->copyFile('./xampp/php/extold/php_mysql.dll',           './xampp/php/ext/php_mysql.dll');
$file->copyFile('./xampp/php/extold/php_mysqli.dll',          './xampp/php/ext/php_mysqli.dll');
$file->copyFile('./xampp/php/extold/php_pdo_mysql.dll',       './xampp/php/ext/php_pdo_mysql.dll');
$file->copyFile('./xampp/php/extold/php_sockets.dll',          './xampp/php/ext/php_sockets.dll');
$file->removeDir('./xampp/php/extold');

/* Process sqlbuddy. */
if(!is_dir('./xampp/admin/')) $file->mkdir('./xampp/admin/');
if(!is_dir('./xampp/admin/sqlbuddy'))
{
    echo `$sevenz x -y $sqlbuddy`;
    $file->rename('./sqlbuddy', './xampp/admin/sqlbuddy');
}

/* Process control panel. */
if(file_exists('./xampp/xampp-control.exe')) $file->rename('./xampp/xampp-control.exe', './xampp/zentaoamp-control-en.exe');
$file->copyFile($buildDir . '/zentaoamp.exe', './xampp/zentaoamp-control-cn.exe');
$file->batchRemoveFile('./xampp/xampp_s*');

/* Copy index.php. */
$file->copyFile($buildDir . '/index.php', './xampp/htdocs/index.php');

/* Copy zentao.conf. */
$file->copyFile($buildDir . '/zentao.conf', './xampp/apache/conf/extra/httpd-xampp.conf');

/* Copy ioncube loader. */
$file->copyFile($buildDir . '/ioncube_loader_win_5.3.dll', './xampp/php/ext/ioncube_loader_win_5.3.dll');

/* Copy serive bat file. */
$file->copyFile($buildDir . '/apache_installservice.bat',   './xampp/apache_installservice.bat');
$file->copyFile($buildDir . '/apache_uninstallservice.bat', './xampp/apache_uninstallservice.bat');
$file->copyFile($buildDir . '/mysql_installservice.bat',    './xampp/mysql_installservice.bat');
$file->copyFile($buildDir . '/mysql_uninstallserivice.bat', './xampp/mysql_installservice.bat');

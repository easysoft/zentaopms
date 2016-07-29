<?php
/**
 * encodings: 提交日志的编码，比如GBK，可以用逗号连接起来的多个。
 * client: svn客户端执行文件的路径，windows下面考虑安装Slik-Subversion，然后找到svn.exe的路径。linux下面比如/usr/bin/svn
 * repos可以是多个，需要设定某一个库的访问路径，以及用户名和密码。
 *
 * encodeings: the encoding of the comment，can be a list.
 * client: the svn client binary path. You can install Slik-Subversion unser windows, then find the path of svn.exe. under linux, try /usr/bin/svn
 * Can set multi repos, ervery one should set the path, username and password.
 *
 * 例子：
 * $config->svn->client = '/usr/bin/svn'; // c:\svn\svn.exe
 * $config->svn->repos['pms']['path']     = 'http://svn.zentao.net/zentao/trunk/';
 * $config->svn->repos['pms']['username'] = 'user';
 * $config->svn->repos['pms']['password'] = 'pass';
 *
 */
$config->svn = new stdClass();
$config->svn->encodings = 'utf-8';
$config->svn->client    = '';

$i = 1;
$config->svn->repos[$i]['path']     = '';
$config->svn->repos[$i]['username'] = '';
$config->svn->repos[$i]['password'] = '';

/*
$i ++;
$config->svn->repos[$i]['path']     = '';
$config->svn->repos[$i]['username'] = '';
$config->svn->repos[$i]['password'] = '';
*/

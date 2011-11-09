<?php
/**
 * 1. client设置: svn客户端执行文件的路径，windows下面考虑安装Slik-Subversion，然后找到svn.exe的路径。linux下面比如/usr/bin/svn
 * 2. svn repos设置 可以是多个，需要设定某一个库的访问路径，以及用户名和密码。
 * 3. zentao访问信息设置，需要设置zentao的访问路径以及拥有subversion模块的 接口：同步svn日志权限的用户和密码。
 *
 * 1. client: the svn client binary path. You can install Slik-Subversion unser windows, then find the path of svn.exe. under linux, try /usr/bin/svn
 * 2. svn repos: Can set multi repos, ervery one should set the path, username and password.
 * 3. zentao setting: must set the zentao url and the account with his password who can access subversion modules' api:sync svn log page.
 *
 * example:
 * $config->svn->client = '/usr/bin/svn'; // c:\svn\svn.exe
 * $config->svn->repos['pms']['path']     = 'http://svn.zentao.net/zentao/trunk/';
 * $config->svn->repos['pms']['username'] = 'user';
 * $config->svn->repos['pms']['password'] = 'pass';
 *
 * $config->zentao->path     = "http://pms.zentao.net/";
 * $config->zentao->user     = 'demo';
 * $config->zentao->password = '123456';
 *
 */
$config->svn->client = '/usr/bin/svn';

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

$config->zentao->path = '';
$config->zentao->user = '';
$config->zentao->password = '';

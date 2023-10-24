#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/gogs.class.php';
su('admin');

/**

title=测试gogsModel->create();
cid=1
pid=1

Gogs名称为空    >> name
服务器地址为空  >> url
正确Gogs数据    >> Gogs,http://10.0.7.242:9021,9ff43f9d1a369465bcf0781a3785f46bcef782d1

*/

$gogs = new gogsTest();

$_POST = array();
$_POST['name']  = '';
$_POST['url']   = 'http://10.0.7.242:9021';
$_POST['token'] = '9ff43f9d1a369465bcf0781a3785f46bcef782d1';

r($gogs->create()) && p() && e('name');    // Gogs名称为空

$_POST['name'] = 'Gogs';
$_POST['url']  = '';
r($gogs->create()) && p() && e('url');    // 服务器地址为空

$_POST['url'] = 'http://10.0.7.242:9021';
r($gogs->create()) && p('name,url,token') && e('Gogs,http://10.0.7.242:9021,9ff43f9d1a369465bcf0781a3785f46bcef782d1');    // 正确Gogs数据


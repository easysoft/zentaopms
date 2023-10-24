#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/gogs.class.php';
su('admin');

/**

title=测试gogsModel->update();
cid=1
pid=1

Gogs名称为空    >> name
服务器地址为空  >> url
正确Gogs数据    >> Changed Gogs

*/

$gogs = new gogsTest();

$gogsID = 3;

$_POST = array();
$_POST['name']  = '';
$_POST['url']   = 'http://10.0.7.242:9021';
$_POST['token'] = '9ff43f9d1a369465bcf0781a3785f46bcef782d1';

r($gogs->update($gogsID)) && p() && e('name');    // Gogs名称为空

$_POST['name'] = 'Changed Gogs';
$_POST['url']  = '';
r($gogs->update($gogsID)) && p() && e('url');    // 服务器地址为空

$_POST['url'] = 'http://10.0.7.242:9021';
r($gogs->update($gogsID)) && p('name') && e('Changed Gogs');    // 正确Gogs数据


#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=ssoModel->getBindUsers();
cid=1
pid=1

获取第一条记录账号 >> admin

*/

$sso = $tester->loadModel('sso');

r($sso->getBindUsers()) && p('admin') && e('admin');    // 获取第一条记录账号
#!/usr/bin/env php
<?php

/**

title=测试 userTao->deleteImUserDevice();
timeout=0
cid=0

- 执行$oldTokens @2
- 执行$oldTokens[1]
 - 属性user @1
 - 属性device @zentaoweb
 - 属性token @1
- 执行$oldTokens[2]
 - 属性user @1
 - 属性device @desktop
 - 属性token @2
- 执行$newTokens @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester;
$tester->dao->exec("CREATE TABLE IF NOT EXISTS " . TABLE_IM_USERDEVICE . " (
    `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
    `user` mediumint unsigned NOT NULL DEFAULT '0',
    `device` varchar(30) NOT NULL DEFAULT '',
    `token` varchar(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$tester->dao->delete()->from(TABLE_IM_USERDEVICE)->exec();

zenData('user')->gen(3);
$userdevice = zenData('im_userdevice');
$userdevice->user->range('1-3{2}');
$userdevice->device->range('zentaoweb,desktop');
$userdevice->token->range('1-10');
$userdevice->gen(4);

su('admin');

global $app;
$userModel = $tester->loadModel('user');

$oldTokens = $userModel->dao->select('*')->from(TABLE_IM_USERDEVICE)->where('user')->eq($app->user->id)->orderBy('id')->fetchAll('id');
$userModel->deleteImUserDevice($app->user->id);
$newTokens = $userModel->dao->select('*')->from(TABLE_IM_USERDEVICE)->where('user')->eq($app->user->id)->fetchAll('id');

r(count($oldTokens)) && p() && e(2);
r($oldTokens[1]) && p('user,device,token') && e('1,zentaoweb,1');
r($oldTokens[2]) && p('user,device,token') && e('1,desktop,2');
r(count($newTokens)) && p() && e(0);

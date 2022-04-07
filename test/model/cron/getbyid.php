#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/cron.class.php';
su('admin');

/**

title=测试 cronModel->getById();
cid=1
pid=1

获取ID为2的定时的命令，备注，分，时 >> moduleName=execution&methodName=computeburn,更新燃尽图,30,23,normal
获取ID为16的定时的命令，备注，分，时 >> moduleName=effort&methodName=remindNotRecord,提醒录入日志,30,7,stop

*/

$cron      = new cronTest();
$cronInfo1 = $cron->getByIdTest(2);
$cronInfo2 = $cron->getByIdTest(16);

r($cronInfo1) && p('command,remark,m,h,status') && e('moduleName=execution&methodName=computeburn,更新燃尽图,30,23,normal'); //获取ID为2的定时的命令，备注，分，时
r($cronInfo2) && p('command,remark,m,h,status') && e('moduleName=effort&methodName=remindNotRecord,提醒录入日志,30,7,stop'); //获取ID为16的定时的命令，备注，分，时
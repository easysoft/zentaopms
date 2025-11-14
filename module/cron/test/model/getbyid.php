#!/usr/bin/env php
<?php

/**

title=测试 cronModel->getById();
timeout=0
cid=15881

- 获取ID为2的定时的命令，备注，分，时
 - 属性command @moduleName=execution&methodName=computeburn
 - 属性remark @更新燃尽图
 - 属性m @30
 - 属性h @23
 - 属性status @normal
- 获取ID为11的定时的命令，备注，分，时
 - 属性command @moduleName=todo&methodName=createCycle
 - 属性remark @生成周期性待办
 - 属性m @1
 - 属性h @1
 - 属性status @normal

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cron.unittest.class.php';
su('admin');

$cron = new cronTest();
$cron->init();

$cronInfo1 = $cron->getByIdTest(2);
$cronInfo2 = $cron->getByIdTest(11);

r($cronInfo1) && p('command,remark,m,h,status') && e('moduleName=execution&methodName=computeburn,更新燃尽图,30,23,normal'); //获取ID为2的定时的命令，备注，分，时
r($cronInfo2) && p('command,remark,m,h,status') && e('moduleName=todo&methodName=createCycle,生成周期性待办,1,1,normal');    //获取ID为11的定时的命令，备注，分，时

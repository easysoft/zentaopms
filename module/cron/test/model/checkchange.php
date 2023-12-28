#!/usr/bin/env php
<?php

/**

title=测试 cronModel->checkChange();
timeout=0
cid=1

- 判断是否存在时间是0000-00-00 00:00:00状态不是stop的定时任务存在 @0
- 修改定时任务后，判断是否存在时间是0000-00-00 00:00:00状态不是stop的定时任务存在 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cron.class.php';
su('admin');

$cron = new cronTest();
$cron->init();

r($cron->checkChangeTest()) && p() && e('0'); //判断是否存在时间是0000-00-00 00:00:00状态不是stop的定时任务存在

$cron1          = new stdClass();
$cron1->m       = '55';
$cron1->h       = '23';
$cron1->dom     = '30';
$cron1->mon     = '12';
$cron1->dow     = '6';
$cron1->remark  = '';
$cron1->type    = 'zentao' ;
$cron1->command = 'moduleName=test&methodName=test';
$cron->updateTest(1, $cron1);

r($cron->checkChangeTest()) && p() && e('1'); //修改定时任务后，判断是否存在时间是0000-00-00 00:00:00状态不是stop的定时任务存在
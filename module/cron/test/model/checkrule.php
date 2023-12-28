#!/usr/bin/env php
<?php

/**

title=测试 cronModel->checkRule();
timeout=0
cid=1

- 定时任务'分'填写不正确情况属性m @"分" 填写的不是合法的值
- 定时任务'小时'填写不正确情况属性h @"小时" 填写的不是合法的值
- 定时任务'天'填写不正确情况属性dom @"天" 填写的不是合法的值
- 定时任务'月'填写不正确情况属性mon @"月" 填写的不是合法的值
- 定时任务'周'填写不正确情况属性dow @"周" 填写的不是合法的值
- 定时任务'命令'填写不正确情况属性command @『命令』不能为空。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cron.class.php';
su('admin');

$cron     = new cronTest();
$cron1    = new stdClass();
$cron1->m = '55x';
$res1     = $cron->checkRuleTest($cron1);

$cron1->m = '55';
$cron1->h = '23x';
$res2     = $cron->checkRuleTest($cron1);

$cron1->h   = '23';
$cron1->dom = '30x';
$res3       = $cron->checkRuleTest($cron1);

$cron1->dom = '30';
$cron1->mon = '12x';
$res4       = $cron->checkRuleTest($cron1);

$cron1->mon = '12';
$cron1->dow = '6x';
$res5       = $cron->checkRuleTest($cron1);

$cron1->dow     = '6';
$cron1->command = '';
$res6           = $cron->checkRuleTest($cron1);

r($res1) && p('m')       && e('"分" 填写的不是合法的值');   // 定时任务'分'填写不正确情况
r($res2) && p('h')       && e('"小时" 填写的不是合法的值'); // 定时任务'小时'填写不正确情况
r($res3) && p('dom')     && e('"天" 填写的不是合法的值');   // 定时任务'天'填写不正确情况
r($res4) && p('mon')     && e('"月" 填写的不是合法的值');   // 定时任务'月'填写不正确情况
r($res5) && p('dow')     && e('"周" 填写的不是合法的值');   // 定时任务'周'填写不正确情况
r($res6) && p('command') && e('『命令』不能为空。');        // 定时任务'命令'填写不正确情况
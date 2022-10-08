#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/cron.class.php';
su('admin');

/**

title=测试 cronModel->checkRule();
cid=1
pid=1

定时任务'分'填写不正确情况 >> "分" 填写的不是合法的值
定时任务'小时'填写不正确情况 >> "小时" 填写的不是合法的值
定时任务'天'填写不正确情况 >> "天" 填写的不是合法的值
定时任务'月'填写不正确情况 >> "月" 填写的不是合法的值
定时任务'周'填写不正确情况 >> "周" 填写的不是合法的值
定时任务'命令'填写不正确情况 >> 『命令』不能为空。

*/

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

r($res1) && p() && e('"分" 填写的不是合法的值');   // 定时任务'分'填写不正确情况
r($res2) && p() && e('"小时" 填写的不是合法的值'); // 定时任务'小时'填写不正确情况
r($res3) && p() && e('"天" 填写的不是合法的值');   // 定时任务'天'填写不正确情况
r($res4) && p() && e('"月" 填写的不是合法的值');   // 定时任务'月'填写不正确情况
r($res5) && p() && e('"周" 填写的不是合法的值');   // 定时任务'周'填写不正确情况
r($res6) && p() && e('『命令』不能为空。');        // 定时任务'命令'填写不正确情况
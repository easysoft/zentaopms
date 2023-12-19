#!/usr/bin/env php
<?php
/**

title=测试 installModel->updateLang();
timeout=0
cid=1

- 检查stage表的语言项是否变更成对应的中文语言项。
 - 属性id @1
 - 属性name @需求
 - 属性type @request
- 检查lang表的语言项是否变更成对应的中文语言项。
 - 属性id @1
 - 属性value @支持过程
- 检查cron表的语言项是否变更成对应的中文语言项。
 - 属性id @1
 - 属性remark @更新燃尽图
- 检查stage表的语言项是否变更成对应的英文语言项。
 - 属性id @1
 - 属性name @Story
 - 属性type @request
- 检查lang表的语言项是否变更成对应的英文语言项。
 - 属性id @1
 - 属性value @Support Process
- 检查cron表的语言项是否变更成对应的英文语言项。
 - 属性id @1
 - 属性remark @Update Burndown Chart

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('stage')->gen(6);
$cron = zdTable('cron');
$cron->command->range('moduleName=execution&methodName=computeBurn,moduleName=report&methodName=remind');
$cron->gen(6);

$lang = zdTable('lang');
$lang->key->range('support,engineering,project');
$lang->gen(6);

global $tester, $app;
$tester->loadModel('install');

$app->clientLang = 'zh-cn';
$app->loadLang('install');
$tester->install->updateLang();
r($tester->install->fetchByID(1, 'stage')) && p('id,name,type') && e('1,需求,request'); // 检查stage表的语言项是否变更成对应的中文语言项。
r($tester->install->fetchByID(1, 'lang'))  && p('id,value') && e('1,支持过程');         // 检查lang表的语言项是否变更成对应的中文语言项。
r($tester->install->fetchByID(1, 'cron'))  && p('id,remark') && e('1,更新燃尽图');      // 检查cron表的语言项是否变更成对应的中文语言项。

$app->clientLang = 'en';
$app->loadLang('install');
$tester->install->updateLang();
r($tester->install->fetchByID(1, 'stage')) && p('id,name,type') && e('1,Story,request');       // 检查stage表的语言项是否变更成对应的英文语言项。
r($tester->install->fetchByID(1, 'lang'))  && p('id,value') && e('1,Support Process');         // 检查lang表的语言项是否变更成对应的英文语言项。
r($tester->install->fetchByID(1, 'cron'))  && p('id,remark') && e('1,Update Burndown Chart');  // 检查cron表的语言项是否变更成对应的英文语言项。

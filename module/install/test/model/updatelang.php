#!/usr/bin/env php
<?php
/**

title=测试 installModel->updateLang();
timeout=0
cid=16776

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

zenData('stage')->gen(6);
$cron = zenData('cron');
$cron->command->range('moduleName=execution&methodName=computeBurn,moduleName=report&methodName=remind');
$cron->gen(6);

$lang = zenData('lang');
$lang->key->range('support,engineering,project');
$lang->gen(6);

global $tester, $app, $lang;
$app->clientLang = 'zh-cn';
$lang->productCommon = '产品';
$lang->projectCommon = '项目';

$tester->loadModel('install');
$tester->install->updateLang();
r($tester->install->fetchByID(1, 'stage')) && p('id,name,type') && e('1,需求,request'); // 检查stage表的语言项是否变更成对应的中文语言项。
r($tester->install->fetchByID(1, 'lang'))  && p('id,value') && e('1,支持过程');         // 检查lang表的语言项是否变更成对应的中文语言项。
r($tester->install->fetchByID(1, 'cron'))  && p('id,remark') && e('1,更新燃尽图');      // 检查cron表的语言项是否变更成对应的中文语言项。

$app::$loadedLangs = array();
$app->clientLang   = 'en';
$app->loadLang('install');
$tester->install->updateLang();
r($tester->install->fetchByID(1, 'stage')) && p('id,name,type') && e('1,Story,request');       // 检查stage表的语言项是否变更成对应的英文语言项。
r($tester->install->fetchByID(1, 'lang'))  && p('id,value') && e('1,Support Process');         // 检查lang表的语言项是否变更成对应的英文语言项。
r($tester->install->fetchByID(1, 'cron'))  && p('id,remark') && e('1,Update Burndown Chart');  // 检查cron表的语言项是否变更成对应的英文语言项。

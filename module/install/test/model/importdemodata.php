#!/usr/bin/env php
<?php

/**

title=测试 installModel->importDemoData();
cid=16775

- 检查user表的演示数据导出是否正确。
 - 属性id @9
 - 属性account @testManager
 - 属性realname @测试经理
- 检查product表的演示数据导出是否正确。
 - 属性id @2
 - 属性name @企业内部工时管理系统
- 检查project表的演示数据导出是否正确。
 - 属性id @3
 - 属性name @企业网站第一期

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('user')->gen(0);
zenData('acl')->gen(0);
zenData('action')->gen(0);
zenData('actionrecent')->gen(0);
zenData('bug')->gen(0);
zenData('build')->gen(0);
zenData('burn')->gen(0);
zenData('case')->gen(0);
zenData('casestep')->gen(0);
zenData('dept')->gen(0);
zenData('doclib')->gen(0);
zenData('history')->gen(0);
zenData('module')->gen(0);
zenData('product')->gen(0);
zenData('productplan')->gen(0);
zenData('project')->gen(0);
zenData('projectcase')->gen(0);
zenData('projectproduct')->gen(0);
zenData('projectstory')->gen(0);
zenData('searchdict')->gen(0);
zenData('searchindex')->gen(0);
zenData('story')->gen(0);
zenData('storyspec')->gen(0);
zenData('task')->gen(0);
zenData('taskestimate')->gen(0);
zenData('taskspec')->gen(0);
zenData('team')->gen(0);
zenData('testresult')->gen(0);
zenData('testrun')->gen(0);
zenData('testtask')->gen(0);
zenData('usergroup')->gen(0);
zenData('effort')->gen(0);
zenData('kanbancell')->gen(0);
zenData('kanbancolumn')->gen(0);
zenData('kanbanlane')->gen(0);
zenData('projectadmin')->gen(0);
zenData('storyreview')->gen(0);

global $tester, $app;
$tester->loadModel('install');

$app->clientLang = 'en';
$app->loadLang('install');
$tester->install->importDemoData();
r($tester->install->fetchByID(9, 'user'))    && p('id,account,realname') && e('9,testManager,测试经理'); // 检查user表的演示数据导出是否正确。
r($tester->install->fetchByID(2, 'product')) && p('id,name')             && e('2,企业内部工时管理系统'); // 检查product表的演示数据导出是否正确。
r($tester->install->fetchByID(3, 'project')) && p('id,name')             && e('3,企业网站第一期');       // 检查project表的演示数据导出是否正确。

#!/usr/bin/env php
<?php
/**

title=测试 installModel->importDemoData();
timeout=0
cid=1

- 检查user表的演示数据导出是否正确。
 - 属性id @9
 - 属性account @testManager
 - 属性realname @测试经理
- 检查product表的演示数据导出是否正确。
 - 属性id @2
 - 属性name @企业内部工时管理系统
 - 属性code @workhourManage
- 检查project表的演示数据导出是否正确。
 - 属性id @7
 - 属性name @企业管理系统

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('user')->gen(0);
zdTable('acl')->gen(0);
zdTable('action')->gen(0);
zdTable('bug')->gen(0);
zdTable('build')->gen(0);
zdTable('burn')->gen(0);
zdTable('case')->gen(0);
zdTable('casestep')->gen(0);
zdTable('dept')->gen(0);
zdTable('doclib')->gen(0);
zdTable('history')->gen(0);
zdTable('module')->gen(0);
zdTable('product')->gen(0);
zdTable('productplan')->gen(0);
zdTable('project')->gen(0);
zdTable('projectcase')->gen(0);
zdTable('projectproduct')->gen(0);
zdTable('projectstory')->gen(0);
zdTable('searchdict')->gen(0);
zdTable('searchindex')->gen(0);
zdTable('story')->gen(0);
zdTable('storyspec')->gen(0);
zdTable('task')->gen(0);
zdTable('taskestimate')->gen(0);
zdTable('taskspec')->gen(0);
zdTable('team')->gen(0);
zdTable('testresult')->gen(0);
zdTable('testrun')->gen(0);
zdTable('testtask')->gen(0);
zdTable('usergroup')->gen(0);

global $tester, $app;
$tester->loadModel('install');

$app->clientLang = 'en';
$app->loadLang('install');
$tester->install->importDemoData();
r($tester->install->fetchByID(9, 'user'))    && p('id,account,realname') && e('9,testManager,测试经理');                // 检查user表的演示数据导出是否正确。
r($tester->install->fetchByID(2, 'product')) && p('id,name,code')        && e('2,企业内部工时管理系统,workhourManage'); // 检查product表的演示数据导出是否正确。
r($tester->install->fetchByID(7, 'project')) && p('id,name')             && e('7,企业管理系统');                        // 检查project表的演示数据导出是否正确。

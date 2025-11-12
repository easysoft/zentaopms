#!/usr/bin/env php
<?php

/**

title=测试 bugZen::buildBugForResolve();
timeout=0
cid=0

- 步骤1:正常解决bug
 - 属性id @1
 - 属性execution @1
 - 属性status @resolved
 - 属性confirmed @1
- 步骤2:duplicate类型
 - 属性id @2
 - 属性execution @2
 - 属性status @resolved
- 步骤3:trunk构建
 - 属性id @3
 - 属性execution @3
 - 属性status @resolved
- 步骤4:非trunk构建
 - 属性id @4
 - 属性execution @4
 - 属性status @resolved
- 步骤5:postponed类型
 - 属性id @1
 - 属性status @resolved

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$bug = zenData('bug');
$bug->id->range('1-10');
$bug->openedBy->range('admin,user1,user2');
$bug->execution->range('1-5');
$bug->status->range('active');
$bug->gen(10);

$testtask = zenData('testtask');
$testtask->id->range('1-5');
$testtask->build->range('1,2,3,4,5');
$testtask->gen(5);

su('admin');

$bugTest = new bugZenTest();

// 准备测试数据
$oldBug1 = new stdclass();
$oldBug1->id = 1;
$oldBug1->openedBy = 'admin';
$oldBug1->execution = 1;
$oldBug1->status = 'active';

$oldBug2 = new stdclass();
$oldBug2->id = 2;
$oldBug2->openedBy = 'user1';
$oldBug2->execution = 2;
$oldBug2->status = 'active';

$oldBug3 = new stdclass();
$oldBug3->id = 3;
$oldBug3->openedBy = 'user2';
$oldBug3->execution = 3;
$oldBug3->status = 'active';
$oldBug3->testtask = 0;

$oldBug4 = new stdclass();
$oldBug4->id = 4;
$oldBug4->openedBy = 'admin';
$oldBug4->execution = 4;
$oldBug4->status = 'active';
$oldBug4->testtask = 0;

// 模拟POST数据 - 正常解决
$_POST['resolution'] = 'fixed';
$_POST['resolvedBuild'] = 'trunk';
$_POST['resolvedBy'] = 'admin';
$_POST['assignedTo'] = 'admin';
$_POST['uid'] = uniqid();
r($bugTest->buildBugForResolveTest($oldBug1)) && p('id,execution,status,confirmed') && e('1,1,resolved,1'); // 步骤1:正常解决bug

// 模拟POST数据 - duplicate类型
$_POST['resolution'] = 'duplicate';
$_POST['duplicateBug'] = 5;
$_POST['resolvedBuild'] = 'trunk';
$_POST['resolvedBy'] = 'user1';
$_POST['assignedTo'] = 'user1';
$_POST['uid'] = uniqid();
r($bugTest->buildBugForResolveTest($oldBug2)) && p('id,execution,status') && e('2,2,resolved'); // 步骤2:duplicate类型

// 模拟POST数据 - trunk构建
$_POST['resolution'] = 'fixed';
$_POST['resolvedBuild'] = 'trunk';
$_POST['resolvedBy'] = 'user2';
$_POST['assignedTo'] = 'user2';
$_POST['uid'] = uniqid();
r($bugTest->buildBugForResolveTest($oldBug3)) && p('id,execution,status') && e('3,3,resolved'); // 步骤3:trunk构建

// 模拟POST数据 - 非trunk构建
$_POST['resolution'] = 'fixed';
$_POST['resolvedBuild'] = '2';
$_POST['resolvedBy'] = 'admin';
$_POST['assignedTo'] = 'admin';
$_POST['uid'] = uniqid();
r($bugTest->buildBugForResolveTest($oldBug4)) && p('id,execution,status') && e('4,4,resolved'); // 步骤4:非trunk构建

// 模拟POST数据 - 不同resolution类型
$_POST['resolution'] = 'postponed';
$_POST['resolvedBuild'] = 'trunk';
$_POST['resolvedBy'] = 'admin';
$_POST['assignedTo'] = 'admin';
$_POST['uid'] = uniqid();
r($bugTest->buildBugForResolveTest($oldBug1)) && p('id,status') && e('1,resolved'); // 步骤5:postponed类型
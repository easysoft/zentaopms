#!/usr/bin/env php
<?php

/**

title=测试 bugZen::buildBugForResolve();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性status @resolved
 - 属性confirmed @1
- 步骤2：非重复Bug属性noDuplicateBug @1
- 步骤3：重复Bug属性duplicateBug @3
- 步骤4：trunk构建属性testtask @~~
- 步骤5：指定构建属性testtask @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('bug');
$table->id->range('1-10');
$table->product->range('1');
$table->execution->range('101');
$table->openedBy->range('admin');
$table->assignedTo->range('user1');
$table->status->range('active');
$table->title->range('测试Bug{1-10}');
$table->gen(10);

$testtask = zenData('testtask');
$testtask->id->range('1-5');
$testtask->build->range('build1,build2,build3,build4,build5');
$testtask->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建测试用的oldBug对象
$oldBug1 = new stdclass();
$oldBug1->id = 1;
$oldBug1->openedBy = 'admin';
$oldBug1->execution = 101;
$oldBug1->testtask = '';

$oldBug2 = new stdclass();
$oldBug2->id = 2;
$oldBug2->openedBy = 'user1';
$oldBug2->execution = 102;
$oldBug2->testtask = '';

$oldBug3 = new stdclass();
$oldBug3->id = 3;
$oldBug3->openedBy = 'admin';
$oldBug3->execution = 103;
$oldBug3->testtask = '';

$oldBug4 = new stdclass();
$oldBug4->id = 4;
$oldBug4->openedBy = 'user1';
$oldBug4->execution = 104;
$oldBug4->testtask = '';

$oldBug5 = new stdclass();
$oldBug5->id = 5;
$oldBug5->openedBy = 'admin';
$oldBug5->execution = 105;
$oldBug5->testtask = '';

// 设置POST数据模拟表单提交
$_POST['resolution'] = 'fixed';
$_POST['uid'] = '';

r($bugTest->buildBugForResolveTest($oldBug1)) && p('status,confirmed') && e('resolved,1'); // 步骤1：正常情况
$_POST['resolution'] = 'fixed';
r($bugTest->buildBugForResolveTest($oldBug2)) && p('noDuplicateBug') && e('1'); // 步骤2：非重复Bug
$_POST['resolution'] = 'duplicate';
$_POST['duplicateBug'] = '3';
r($bugTest->buildBugForResolveTest($oldBug3)) && p('duplicateBug') && e('3'); // 步骤3：重复Bug
$_POST['resolution'] = 'fixed';
$_POST['resolvedBuild'] = 'trunk';
r($bugTest->buildBugForResolveTest($oldBug4)) && p('testtask') && e('~~'); // 步骤4：trunk构建
$_POST['resolvedBuild'] = 'build1';
r($bugTest->buildBugForResolveTest($oldBug5)) && p('testtask') && e('1'); // 步骤5：指定构建
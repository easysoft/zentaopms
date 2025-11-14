#!/usr/bin/env php
<?php

/**

title=测试 bugZen::checkBugsForBatchUpdate();
timeout=0
cid=15442

- 执行$result @1
- 执行$result @0
- 执行$result @0
- 执行$result @0
- 执行$result @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'batchedit';

$zen = initReference('bug');
$func = $zen->getMethod('checkBugsForBatchUpdate');

// 测试步骤1：有效的bug数据批量更新验证
dao::$errors = array();
$validBugs = array();
$validBug1 = new stdClass();
$validBug1->id = 1;
$validBug1->title = 'Valid Bug Title 1';
$validBug1->openedBuild = 'trunk';
$validBug1->severity = 3;
$validBug1->pri = 3;
$validBugs[] = $validBug1;

$validBug2 = new stdClass();
$validBug2->id = 2;
$validBug2->title = 'Valid Bug Title 2';
$validBug2->openedBuild = 'trunk';
$validBug2->severity = 2;
$validBug2->pri = 2;
$validBugs[] = $validBug2;

$result = $func->invokeArgs($zen->newInstance(), [$validBugs]);
r($result) && p() && e('1');

// 测试步骤2：缺少必填字段title的bug数据验证
dao::$errors = array();
$invalidBugsNoTitle = array();
$invalidBug1 = new stdClass();
$invalidBug1->id = 3;
$invalidBug1->title = '';
$invalidBug1->openedBuild = 'trunk';
$invalidBugsNoTitle[] = $invalidBug1;

$result = $func->invokeArgs($zen->newInstance(), [$invalidBugsNoTitle]);
r($result) && p() && e('0');

// 测试步骤3：缺少必填字段openedBuild的bug数据验证  
dao::$errors = array();
$invalidBugsNoBuild = array();
$invalidBug2 = new stdClass();
$invalidBug2->id = 4;
$invalidBug2->title = 'Bug without build';
$invalidBug2->openedBuild = '';
$invalidBugsNoBuild[] = $invalidBug2;

$result = $func->invokeArgs($zen->newInstance(), [$invalidBugsNoBuild]);
r($result) && p() && e('0');

// 测试步骤4：resolvedBy有值但resolution为空的验证
dao::$errors = array();
$invalidBugsResolvedBy = array();
$invalidBug3 = new stdClass();
$invalidBug3->id = 5;
$invalidBug3->title = 'Bug with resolvedBy but no resolution';
$invalidBug3->openedBuild = 'trunk';
$invalidBug3->resolvedBy = 'admin';
$invalidBug3->resolution = '';
$invalidBugsResolvedBy[] = $invalidBug3;

$result = $func->invokeArgs($zen->newInstance(), [$invalidBugsResolvedBy]);
r($result) && p() && e('0');

// 测试步骤5：resolution为duplicate但duplicateBug为空的验证
dao::$errors = array();
$invalidBugsDuplicate = array();
$invalidBug4 = new stdClass();
$invalidBug4->id = 6;
$invalidBug4->title = 'Duplicate bug without duplicateBug ID';
$invalidBug4->openedBuild = 'trunk';
$invalidBug4->resolution = 'duplicate';
$invalidBug4->duplicateBug = '';
$invalidBugsDuplicate[] = $invalidBug4;

$result = $func->invokeArgs($zen->newInstance(), [$invalidBugsDuplicate]);
r($result) && p() && e('0');
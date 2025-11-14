#!/usr/bin/env php
<?php

/**

title=测试 commonTao::queryListForPreAndNext();
timeout=0
cid=15727

- 执行$list['objectList'] @1,2,3,4,5

- 执行$emptyList['objectList'] @5
- 执行$newList['objectList'] @1,2,3

- 执行$testcaseList['idkey'] @case
- 执行$boundaryList['objectList'] @9,10

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. zendata数据准备
$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->title->range('Story %d');
$storyTable->status->range('active{6},draft{2},closed{2}');
$storyTable->type->range('story{8},requirement{2}');
$storyTable->gen(10);

$testcaseTable = zenData('case');
$testcaseTable->id->range('1-5');
$testcaseTable->title->range('Test case %d');
$testcaseTable->status->range('normal{3},blocked{2}');
$testcaseTable->type->range('feature{3},performance{2}');
$testcaseTable->gen(5);

// 3. 用户登录
su('admin');

// 4. 加载common模块
global $tester;
$tester->loadModel('common');

// 5. 测试步骤（必须包含至少5个测试步骤）

// 步骤1：正常查询story对象列表
$_SESSION['storyQueryCondition'] = 'id <= 5';
$_SESSION['storyOnlyCondition'] = true;
$sql = 'SELECT * FROM `zt_story` WHERE id <= 5 ORDER BY id';
$list = $tester->common->queryListForPreAndNext('story', $sql);
r(implode(',', $list['objectList'])) && p() && e('1,2,3,4,5');

// 步骤2：测试空SQL查询情况
$emptyList = $tester->common->queryListForPreAndNext('story', '');
r(count($emptyList['objectList'])) && p() && e('5');

// 步骤3：测试会话缓存命中情况（SQL不同时重新查询）
$_SESSION['storyQueryCondition'] = 'id <= 3';
$differentSql = 'SELECT * FROM `zt_story` WHERE id <= 3 ORDER BY id';
$newList = $tester->common->queryListForPreAndNext('story', $differentSql);
r(implode(',', $newList['objectList'])) && p() && e('1,2,3');

// 步骤4：测试testcase类型的特殊键处理
$_SESSION['testcaseOnlyCondition'] = false;
$testcaseSQL = 'SELECT *, id as `case` FROM `zt_case` WHERE id <= 3';
$testcaseList = $tester->common->queryListForPreAndNext('testcase', $testcaseSQL);
r($testcaseList['idkey']) && p() && e('case');

// 步骤5：测试复杂查询条件的边界值
$_SESSION['storyQueryCondition'] = 'id > 8';
$_SESSION['storyOnlyCondition'] = true;
$boundarySql = 'SELECT * FROM `zt_story` WHERE id > 8 ORDER BY id';
$boundaryList = $tester->common->queryListForPreAndNext('story', $boundarySql);
r(implode(',', $boundaryList['objectList'])) && p() && e('9,10');
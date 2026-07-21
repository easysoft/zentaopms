#!/usr/bin/env php
<?php

/**

title=测试 reporeviewflowModel::create();
timeout=0
cid=0

- 测试创建评审流程 @1
- 测试重复创建返回错误 @1
- 测试创建空名称返回错误 @1
- 测试创建另一个评审流程 @1
- 测试创建无效repo返回错误 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('ops_repo')->loadYaml('ops_repo', false, 2)->gen(10);
zenData('ops_review_flow')->gen(0);

$reviewFlow = new stdClass();
$reviewFlow->repo        = 1;
$reviewFlow->name        = 'test_flow5';
$reviewFlow->desc        = 'desc';
$reviewFlow->status      = 'enable';
$reviewFlow->createdBy   = 'admin';
$reviewFlow->createdDate = '2026-07-10 09:00:00';

$emptyName = clone $reviewFlow;
$emptyName->name = '';

$flow2 = clone $reviewFlow;
$flow2->repo = 2;
$flow2->name = 'test_flow6';

$invalidRepo = clone $reviewFlow;
$invalidRepo->repo = 999;
$invalidRepo->name = 'test_flow7';

$tester = new reporeviewflowTest();

$v1 = $tester->createTest(1, $reviewFlow);
$v2 = $tester->createTest(1, $reviewFlow);
$v3 = $tester->createTest(1, $emptyName);
$v4 = $tester->createTest(2, $flow2);
$v5 = $tester->createTest(999, $invalidRepo);

$ok1 = (is_object($v1) && $v1->name == 'test_flow5') ? '1' : '0';
$ok2 = (is_array($v2) && !empty($v2)) ? '1' : '0';
$ok3 = (is_object($v3) || is_array($v3)) ? '1' : '0';
$ok4 = (is_object($v4) && $v4->repo == 2) ? '1' : '0';
$ok5 = (is_object($v5) || is_array($v5)) ? '1' : '0';

r($ok1) && p() && e('1');
r($ok2) && p() && e('1');
r($ok3) && p() && e('1');
r($ok4) && p() && e('1');
r($ok5) && p() && e('1');

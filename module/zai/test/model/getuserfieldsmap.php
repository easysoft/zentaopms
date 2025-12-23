#!/usr/bin/env php
<?php

/**
title=测试 zaiModel::getUserFieldsMap();
timeout=0
cid=0

- story 类型包含 openedBy 字段 @1
- bug 类型包含 resolvedBy 字段 @1
- case 类型包含 lastRunner 字段 @1
- plan 类型包含 owner 字段 @1
- 未知类型返回空数组 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

su('admin');

global $tester;
$zai = new zaiTest();

/* story 类型包含 openedBy 字段 */
$storyMap = $zai->getUserFieldsMapTest('story');
$hasOpenedBy = in_array('openedBy', $storyMap, true) ? '1' : '0';
r($hasOpenedBy) && p() && e('1'); // story 类型包含 openedBy 字段

/* bug 类型包含 resolvedBy 字段 */
$bugMap = $zai->getUserFieldsMapTest('bug');
$hasResolvedBy = in_array('resolvedBy', $bugMap, true) ? '1' : '0';
r($hasResolvedBy) && p() && e('1'); // bug 类型包含 resolvedBy 字段

/* case 类型包含 lastRunner 字段 */
$caseMap = $zai->getUserFieldsMapTest('case');
$hasLastRunner = in_array('lastRunner', $caseMap, true) ? '1' : '0';
r($hasLastRunner) && p() && e('1'); // case 类型包含 lastRunner 字段

/* plan 类型包含 owner 字段 */
$planMap = $zai->getUserFieldsMapTest('plan');
$hasOwner = in_array('owner', $planMap, true) ? '1' : '0';
r($hasOwner) && p() && e('1'); // plan 类型包含 owner 字段

/* 未知类型返回空数组 */
$unknownMap = $zai->getUserFieldsMapTest('unknown');
$isEmpty = empty($unknownMap) ? '1' : '0';
r($isEmpty) && p() && e('1'); // 未知类型返回空数组

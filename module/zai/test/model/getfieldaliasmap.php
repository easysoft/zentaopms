#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::getFieldAliasMap();
timeout=0
cid=0

- issue 类型包含 createdBy 别名 @1
- issue 类型 deadline 别名包含 deadLine @1
- plan 类型 begin 别名包含 beginDate @1
- release 类型 project 别名包含 projectName @1
- 未知类型返回空数组 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

su('admin');

global $tester;
$zai = new zaiTest();

/* issue 类型包含 createdBy 别名 */
$issueMap      = $zai->getFieldAliasMapTest('issue');
$hasCreatedBy  = (isset($issueMap['assetCreatedBy']) && in_array('createdBy', $issueMap['assetCreatedBy'], true)) ? '1' : '0';
r($hasCreatedBy) && p() && e('1'); // issue 类型包含 createdBy 别名

/* issue 类型 deadline 别名包含 deadLine */
$hasDeadlineAlias = (isset($issueMap['deadline']) && in_array('deadLine', $issueMap['deadline'], true)) ? '1' : '0';
r($hasDeadlineAlias) && p() && e('1'); // issue 类型 deadline 别名包含 deadLine

/* plan 类型 begin 别名包含 beginDate */
$planMap     = $zai->getFieldAliasMapTest('plan');
$hasBegin    = (isset($planMap['begin']) && in_array('beginDate', $planMap['begin'], true)) ? '1' : '0';
r($hasBegin) && p() && e('1'); // plan 类型 begin 别名包含 beginDate

/* release 类型 project 别名包含 projectName */
$releaseMap  = $zai->getFieldAliasMapTest('release');
$hasProject  = (isset($releaseMap['project']) && in_array('projectName', $releaseMap['project'], true)) ? '1' : '0';
r($hasProject) && p() && e('1'); // release 类型 project 别名包含 projectName

/* 未知类型返回空数组 */
$unknownMap = $zai->getFieldAliasMapTest('unknown');
$isEmpty    = empty($unknownMap) ? '1' : '0';
r($isEmpty) && p() && e('1'); // 未知类型返回空数组

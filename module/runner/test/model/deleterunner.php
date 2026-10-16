#!/usr/bin/env php
<?php

/**

title=测试 runnerModel::deleteRunner()
timeout=0
cid=0

- 正常输入：传入存在的runnerID时返回TypeError @error:TypeError
- 边界值输入：runnerID为0时返回TypeError @error:TypeError
- 无效输入：runnerID为负数时返回TypeError @error:TypeError
- 大值输入：runnerID超出范围时返回TypeError @error:TypeError
- 缺少参数：只传gitfoxID时返回ArgumentCountError @error:ArgumentCountError
- 业务规则：deleteRunner接收两个int类型参数 @int,int
- 业务规则：deleteRunner返回bool值 @bool

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(1);

/* 独立准备GitFox服务数据。 */
$entry = zenData('entry');
$entry->id->range('1');
$entry->name->range('GitFox');
$entry->code->range('gitfox');
$entry->key->range('unittest97989fcb1fdb0d82471c3238');
$entry->freePasswd->range('1');
$entry->ip->range('*');
$entry->createdBy->range('admin');
$entry->calledTime->range('0');
$entry->deleted->range('0');
$entry->gen(1);

su('admin');

$testObj = new runnerModelTest();
$method  = new ReflectionMethod('runnerModel', 'deleteRunner');

r($testObj->deleteRunnerTest(1, 1)) && p() && e('error:TypeError'); // 正常输入：传入存在的runnerID时返回TypeError
r($testObj->deleteRunnerTest(0, 0)) && p() && e('error:TypeError'); // 边界值输入：runnerID为0时返回TypeError
r($testObj->deleteRunnerTest(-1, -1)) && p() && e('error:TypeError'); // 无效输入：runnerID为负数时返回TypeError
r($testObj->deleteRunnerTest(999999, 999999)) && p() && e('error:TypeError'); // 大值输入：runnerID超出范围时返回TypeError
r($testObj->deleteRunnerTest(1)) && p() && e('error:ArgumentCountError'); // 缺少参数：只传gitfoxID时返回ArgumentCountError

$paramTypes = array();
foreach($method->getParameters() as $param) $paramTypes[] = $param->getType()->getName();
r(implode(',', $paramTypes)) && p() && e('int,int'); // 业务规则：deleteRunner接收两个int类型参数
r($method->getReturnType()->getName()) && p() && e('bool'); // 业务规则：deleteRunner返回bool值

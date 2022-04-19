#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->getByStatus();
cid=1
pid=1

测试查询所有产品 所有状态的case >> 400
测试查询产品1 所有状态的case >> 4
测试查询产品2 所有状态的case >> 4
测试查询所有产品 type needconfirm 的case >> 100
测试查询所有产品 状态 wait 的case >> 100
测试查询所有产品 状态 normal 的case >> 100
测试查询所有产品 状态 blocked 的case >> 100
测试查询所有产品 状态 investigate 的case >> 100
测试查询产品1 状态 wait 的case >> 1
测试查询产品1 状态 normal 的case >> 1
测试查询产品1 状态 blocked 的case >> 1
测试查询产品1 状态 investigate 的case >> 1
测试查询产品2 状态 wait 的case >> 1
测试查询产品2 状态 normal 的case >> 1
测试查询产品2 状态 blocked 的case >> 1
测试查询产品2 状态 investigate 的case >> 1

*/
$productIDList = array('0', '1', '2');
$branch        = 0;
$typeList      = array('all', 'needConfirm');
$statusList    = array('wait', 'normal', 'blocked', 'investigate');

$testcase = new testcaseTest();

r($testcase->getByStatusTest())                                                         && p() && e('400'); // 测试查询所有产品 所有状态的case
r($testcase->getByStatusTest($productIDList[1]))                                        && p() && e('4');   // 测试查询产品1 所有状态的case
r($testcase->getByStatusTest($productIDList[2]))                                        && p() && e('4');   // 测试查询产品2 所有状态的case
r($testcase->getByStatusTest($productIDList[0], $branch, $typeList[1], $statusList[0])) && p() && e('100'); // 测试查询所有产品 type needconfirm 的case
r($testcase->getByStatusTest($productIDList[0], $branch, $typeList[0], $statusList[0])) && p() && e('100'); // 测试查询所有产品 状态 wait 的case
r($testcase->getByStatusTest($productIDList[0], $branch, $typeList[0], $statusList[1])) && p() && e('100'); // 测试查询所有产品 状态 normal 的case
r($testcase->getByStatusTest($productIDList[0], $branch, $typeList[0], $statusList[2])) && p() && e('100'); // 测试查询所有产品 状态 blocked 的case
r($testcase->getByStatusTest($productIDList[0], $branch, $typeList[0], $statusList[3])) && p() && e('100'); // 测试查询所有产品 状态 investigate 的case
r($testcase->getByStatusTest($productIDList[1], $branch, $typeList[0], $statusList[0])) && p() && e('1');   // 测试查询产品1 状态 wait 的case
r($testcase->getByStatusTest($productIDList[1], $branch, $typeList[0], $statusList[1])) && p() && e('1');   // 测试查询产品1 状态 normal 的case
r($testcase->getByStatusTest($productIDList[1], $branch, $typeList[0], $statusList[2])) && p() && e('1');   // 测试查询产品1 状态 blocked 的case
r($testcase->getByStatusTest($productIDList[1], $branch, $typeList[0], $statusList[3])) && p() && e('1');   // 测试查询产品1 状态 investigate 的case
r($testcase->getByStatusTest($productIDList[2], $branch, $typeList[0], $statusList[0])) && p() && e('1');   // 测试查询产品2 状态 wait 的case
r($testcase->getByStatusTest($productIDList[2], $branch, $typeList[0], $statusList[1])) && p() && e('1');   // 测试查询产品2 状态 normal 的case
r($testcase->getByStatusTest($productIDList[2], $branch, $typeList[0], $statusList[2])) && p() && e('1');   // 测试查询产品2 状态 blocked 的case
r($testcase->getByStatusTest($productIDList[2], $branch, $typeList[0], $statusList[3])) && p() && e('1');   // 测试查询产品2 状态 investigate 的case
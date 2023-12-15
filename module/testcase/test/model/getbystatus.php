#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

zdTable('product')->gen(10);
zdTable('case')->gen(10);
zdTable('story')->gen(10);

/**

title=测试 testcaseModel->getByStatus();
timeout=0
cid=1

- 测试查询所有产品 所有状态的case @10
- 测试查询产品1 所有状态的case @4
- 测试查询产品2 所有状态的case @4
- 测试查询所有产品 type needconfirm 的case @3
- 测试查询所有产品 状态 wait 的case @3
- 测试查询所有产品 状态 normal 的case @3
- 测试查询所有产品 状态 blocked 的case @2
- 测试查询所有产品 状态 investigate 的case @2
- 测试查询产品1 状态 wait 的case @1
- 测试查询产品1 状态 normal 的case @1
- 测试查询产品1 状态 blocked 的case @1
- 测试查询产品1 状态 investigate 的case @1
- 测试查询产品2 状态 wait 的case @1
- 测试查询产品2 状态 normal 的case @1
- 测试查询产品2 状态 blocked 的case @1
- 测试查询产品2 状态 investigate 的case @1

*/
global $app;

$productIDList = array('0', '1', '2');
$branch        = 0;
$typeList      = array('all', 'needConfirm');
$statusList    = array('wait', 'normal', 'blocked', 'investigate');

$testcase = new testcaseTest();

r($app->user->view->products)                                                           && p() && e('3,4,5,1,2,3,4,5,6,7,8,9,10'); // 测试查询所有产品 所有状态的case
r($testcase->getByStatusTest())                                                         && p() && e('10'); // 测试查询所有产品 所有状态的case
r($testcase->getByStatusTest($productIDList[1]))                                        && p() && e('4');  // 测试查询产品1 所有状态的case
r($testcase->getByStatusTest($productIDList[2]))                                        && p() && e('4');  // 测试查询产品2 所有状态的case
r($testcase->getByStatusTest($productIDList[0], $branch, $typeList[1], $statusList[0])) && p() && e('3');  // 测试查询所有产品 type needconfirm 的case
r($testcase->getByStatusTest($productIDList[0], $branch, $typeList[0], $statusList[0])) && p() && e('3');  // 测试查询所有产品 状态 wait 的case
r($testcase->getByStatusTest($productIDList[0], $branch, $typeList[0], $statusList[1])) && p() && e('3');  // 测试查询所有产品 状态 normal 的case
r($testcase->getByStatusTest($productIDList[0], $branch, $typeList[0], $statusList[2])) && p() && e('2');  // 测试查询所有产品 状态 blocked 的case
r($testcase->getByStatusTest($productIDList[0], $branch, $typeList[0], $statusList[3])) && p() && e('2');  // 测试查询所有产品 状态 investigate 的case
r($testcase->getByStatusTest($productIDList[1], $branch, $typeList[0], $statusList[0])) && p() && e('1');  // 测试查询产品1 状态 wait 的case
r($testcase->getByStatusTest($productIDList[1], $branch, $typeList[0], $statusList[1])) && p() && e('1');  // 测试查询产品1 状态 normal 的case
r($testcase->getByStatusTest($productIDList[1], $branch, $typeList[0], $statusList[2])) && p() && e('1');  // 测试查询产品1 状态 blocked 的case
r($testcase->getByStatusTest($productIDList[1], $branch, $typeList[0], $statusList[3])) && p() && e('1');  // 测试查询产品1 状态 investigate 的case
r($testcase->getByStatusTest($productIDList[2], $branch, $typeList[0], $statusList[0])) && p() && e('1');  // 测试查询产品2 状态 wait 的case
r($testcase->getByStatusTest($productIDList[2], $branch, $typeList[0], $statusList[1])) && p() && e('1');  // 测试查询产品2 状态 normal 的case
r($testcase->getByStatusTest($productIDList[2], $branch, $typeList[0], $statusList[2])) && p() && e('1');  // 测试查询产品2 状态 blocked 的case
r($testcase->getByStatusTest($productIDList[2], $branch, $typeList[0], $statusList[3])) && p() && e('1');  // 测试查询产品2 状态 investigate 的case

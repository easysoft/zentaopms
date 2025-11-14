#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('case')->loadYaml('case')->gen(20);
zenData('testrun')->loadYaml('testrun')->gen(20);
zenData('suitecase')->loadYaml('suitecase')->gen(20);
zenData('build')->loadYaml('build')->gen(3);
zenData('story')->gen(2);

/**

title=测试 testtaskModel->getLinkableCases();
timeout=0
cid=19176

- 产品 0 测试单 4 可关联的用例数为 0。 @0
- 产品 2 测试单 4 可关联的用例数为 0。 @0
- 产品 1 测试单 1 按需求关联可关联的用例数为 0。 @0
- 产品 1 测试单 1 按缺陷关联可关联的用例数为 0。 @0
- 产品 1 测试单 1 按套件关联可关联的用例数为 0。 @0
- 产品 1 测试单 1 按版本关联可关联的用例数为 0。 @0
- 产品 1 测试单 1 按其他关联可关联的用例数为 0。 @0
- 产品 1 测试单 1 按套件 0 关联可关联的用例数为 0。 @0
- 产品 1 测试单 1 按版本 0 关联可关联的用例数为 0。 @0
- 产品 1 测试单 1 按套件 2 关联可关联的用例数为 0。 @0
- 产品 1 测试单 1 按版本 2 关联可关联的用例数为 0。 @0
- 产品 1 测试单 4 可关联的用例数为 6。 @6
- 产品 1 测试单 4 可关联的用例数为 12。 @12
- 产品 1 测试单 4 限制每页查询 5 条后可关联的用例数为 5。 @5
- 产品 1 测试单 4 限制每页查询 5 条后可关联的用例数为 5。 @5
- 产品 1 测试单 4 按需求关联可关联的用例数为 6。 @6
- 产品 1 测试单 4 按需求关联可关联的用例数为 12。 @12
- 产品 1 测试单 4 限制每页查询 5 条后按需求关联可关联的用例数为 5。 @5
- 产品 1 测试单 4 限制每页查询 5 条后按需求关联可关联的用例数为 5。 @5
- 产品 1 测试单 4 按缺陷关联可关联的用例数为 6。 @6
- 产品 1 测试单 4 按缺陷关联可关联的用例数为 12。 @12
- 产品 1 测试单 4 限制每页查询 5 条后按缺陷关联可关联的用例数为 5。 @5
- 产品 1 测试单 4 限制每页查询 5 条后按缺陷关联可关联的用例数为 5。 @5
- 产品 1 测试单 4 按套件 1 关联可关联的用例数为 6。 @6
- 产品 1 测试单 4 按套件 1 关联可关联的用例数为 12。 @12
- 产品 1 测试单 4 限制每页查询 5 条后按套件 1 关联可关联的用例数为 5。 @5
- 产品 1 测试单 4 限制每页查询 5 条后按套件 1 关联可关联的用例数为 5。 @5
- 产品 1 测试单 4 按版本关联可关联的用例数为 15。 @15
- 产品 1 测试单 4 按版本关联可关联的用例数为 15。 @15
- 产品 1 测试单 4 限制每页查询 5 条后按版本关联可关联的用例数为 5。 @5
- 产品 1 测试单 4 限制每页查询 5 条后按版本关联可关联的用例数为 5。 @5

*/

global $tester, $app;

$app->rawModule = 'testtask';
$app->rawMethod = 'linkCase';
$app->loadClass('pager', true);
$pager = new pager(0, 5, 1);

$testtask = $tester->loadModel('testtask');

$task1 = (object)array('id' => 2, 'build' => 0, 'branch' => 0); // 测试单 1 版本 0 分支 0 没有用例。
$task2 = (object)array('id' => 2, 'build' => 1, 'branch' => 0); // 测试单 2 版本 1 分支 0 没有用例。
$task3 = (object)array('id' => 2, 'build' => 3, 'branch' => 0); // 测试单 3 版本 3 分支 0 没有用例。
$task4 = (object)array('id' => 2, 'build' => 2, 'branch' => 0); // 测试单 4 版本 2 分支 0 有用例。
$task5 = (object)array('id' => 2, 'build' => 2, 'branch' => 1); // 测试单 5 版本 2 分支 1 有用例。

r($testtask->getLinkableCases(0, $task4))               && p() && e(0); // 产品 0 测试单 4 可关联的用例数为 0。
r($testtask->getLinkableCases(2, $task4))               && p() && e(0); // 产品 2 测试单 4 可关联的用例数为 0。
r($testtask->getLinkableCases(1, $task1, 'bystory'))    && p() && e(0); // 产品 1 测试单 1 按需求关联可关联的用例数为 0。
r($testtask->getLinkableCases(1, $task1, 'bybug'))      && p() && e(0); // 产品 1 测试单 1 按缺陷关联可关联的用例数为 0。
r($testtask->getLinkableCases(1, $task1, 'bysuite'))    && p() && e(0); // 产品 1 测试单 1 按套件关联可关联的用例数为 0。
r($testtask->getLinkableCases(1, $task1, 'bybuild'))    && p() && e(0); // 产品 1 测试单 1 按版本关联可关联的用例数为 0。
r($testtask->getLinkableCases(1, $task1, 'byother'))    && p() && e(0); // 产品 1 测试单 1 按其他关联可关联的用例数为 0。
r($testtask->getLinkableCases(1, $task4, 'bysuite', 0)) && p() && e(0); // 产品 1 测试单 1 按套件 0 关联可关联的用例数为 0。
r($testtask->getLinkableCases(1, $task4, 'bybuild', 0)) && p() && e(0); // 产品 1 测试单 1 按版本 0 关联可关联的用例数为 0。
r($testtask->getLinkableCases(1, $task4, 'bysuite', 2)) && p() && e(0); // 产品 1 测试单 1 按套件 2 关联可关联的用例数为 0。
r($testtask->getLinkableCases(1, $task4, 'bybuild', 2)) && p() && e(0); // 产品 1 测试单 1 按版本 2 关联可关联的用例数为 0。

r(count($testtask->getLinkableCases(1, $task4))) && p() && e(6);  // 产品 1 测试单 4 可关联的用例数为 6。
r(count($testtask->getLinkableCases(1, $task5))) && p() && e(12); // 产品 1 测试单 4 可关联的用例数为 12。

r(count($testtask->getLinkableCases(1, $task4, 'all', 0, $pager))) && p() && e(5); // 产品 1 测试单 4 限制每页查询 5 条后可关联的用例数为 5。
r(count($testtask->getLinkableCases(1, $task5, 'all', 0, $pager))) && p() && e(5); // 产品 1 测试单 4 限制每页查询 5 条后可关联的用例数为 5。

r(count($testtask->getLinkableCases(1, $task4, 'bystory'))) && p() && e(6);  // 产品 1 测试单 4 按需求关联可关联的用例数为 6。
r(count($testtask->getLinkableCases(1, $task5, 'bystory'))) && p() && e(12); // 产品 1 测试单 4 按需求关联可关联的用例数为 12。

r(count($testtask->getLinkableCases(1, $task4, 'bystory', 0, $pager))) && p() && e(5); // 产品 1 测试单 4 限制每页查询 5 条后按需求关联可关联的用例数为 5。
r(count($testtask->getLinkableCases(1, $task5, 'bystory', 0, $pager))) && p() && e(5); // 产品 1 测试单 4 限制每页查询 5 条后按需求关联可关联的用例数为 5。

r(count($testtask->getLinkableCases(1, $task4, 'bybug'))) && p() && e(6);  // 产品 1 测试单 4 按缺陷关联可关联的用例数为 6。
r(count($testtask->getLinkableCases(1, $task5, 'bybug'))) && p() && e(12); // 产品 1 测试单 4 按缺陷关联可关联的用例数为 12。

r(count($testtask->getLinkableCases(1, $task4, 'bybug', 0, $pager))) && p() && e(5); // 产品 1 测试单 4 限制每页查询 5 条后按缺陷关联可关联的用例数为 5。
r(count($testtask->getLinkableCases(1, $task5, 'bybug', 0, $pager))) && p() && e(5); // 产品 1 测试单 4 限制每页查询 5 条后按缺陷关联可关联的用例数为 5。

r(count($testtask->getLinkableCases(1, $task4, 'bysuite', 1))) && p() && e(6);  // 产品 1 测试单 4 按套件 1 关联可关联的用例数为 6。
r(count($testtask->getLinkableCases(1, $task5, 'bysuite', 1))) && p() && e(12); // 产品 1 测试单 4 按套件 1 关联可关联的用例数为 12。

r(count($testtask->getLinkableCases(1, $task4, 'bysuite', 1, $pager))) && p() && e(5); // 产品 1 测试单 4 限制每页查询 5 条后按套件 1 关联可关联的用例数为 5。
r(count($testtask->getLinkableCases(1, $task5, 'bysuite', 1, $pager))) && p() && e(5); // 产品 1 测试单 4 限制每页查询 5 条后按套件 1 关联可关联的用例数为 5。

r(count($testtask->getLinkableCases(1, $task4, 'bybuild', 1))) && p() && e(15); // 产品 1 测试单 4 按版本关联可关联的用例数为 15。
r(count($testtask->getLinkableCases(1, $task5, 'bybuild', 1))) && p() && e(15); // 产品 1 测试单 4 按版本关联可关联的用例数为 15。

r(count($testtask->getLinkableCases(1, $task4, 'bybuild', 1, $pager))) && p() && e(5); // 产品 1 测试单 4 限制每页查询 5 条后按版本关联可关联的用例数为 5。
r(count($testtask->getLinkableCases(1, $task5, 'bybuild', 1, $pager))) && p() && e(5); // 产品 1 测试单 4 限制每页查询 5 条后按版本关联可关联的用例数为 5。

#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('case')->loadYaml('case')->gen(24);

/**

title=测试 testtaskModel->getAllLinkableCases();
timeout=0
cid=19163

- 获取可关联到测试单 1 的用例，检查数量。 @5
- 获取可关联到测试单 2 的用例，检查数量。 @10
- 获取可关联到测试单 3 的用例，检查数量。 @10
- 获取可关联到测试单 3 的用例，传入sql语句，检查数量。 @9
- 获取可关联到测试单 3 的用例，已关联的用例 ID，检查数量。 @9
- 获取可关联到测试单 3 的用例，传入分页器，检查数量。 @5
- 获取可关联到测试单 3 的用例，传入sql语句和已关联的用例 ID，检查数量。 @8
- 获取可关联到测试单 3 的用例，传入sql语句和分页器，检查数量。 @5
- 获取可关联到测试单 3 的用例，已关联的用例 ID 和分页器，检查数量。 @5
- 获取可关联到测试单 3 的用例，传入sql语句、已关联的用例 ID 和分页器，检查数量。 @5
- 获取可关联到测试单 3 的用例，传入sql语句、已关联的用例 ID 和分页器，检查数量。 @4

*/

global $tester, $app;

$testtask = $tester->loadModel('testtask');

$app->loadClass('pager', true);
$app->rawModule = 'testtask';
$app->rawMethod = 'linkCase';
$pager = new pager(0, 5, 1);

$task1 = (object)array('branch' => 0); // 测试单 1 关联到主干
$task2 = (object)array('branch' => 1); // 测试单 2 关联到分支1
$task3 = (object)array('branch' => 2); // 测试单 3 关联到分支2

$cases1  = $testtask->getAllLinkableCases($task1);
$cases2  = $testtask->getAllLinkableCases($task2);
$cases3  = $testtask->getAllLinkableCases($task3);
$cases4  = $testtask->getAllLinkableCases($task3, 'id < 22');
$cases5  = $testtask->getAllLinkableCases($task3, '', array(21));
$cases6  = $testtask->getAllLinkableCases($task3, '', array(), $pager);
$cases7  = $testtask->getAllLinkableCases($task3, 'id < 22', array(5));
$cases8  = $testtask->getAllLinkableCases($task3, 'id < 22', array(),  $pager);
$cases9  = $testtask->getAllLinkableCases($task3, '', array(5), $pager);
$cases10 = $testtask->getAllLinkableCases($task3, 'id < 22', array(5), $pager);
$cases11 = $testtask->getAllLinkableCases($task3, 'id < 10', array(5), $pager);

r(count($cases1))  && p() && e('5');  // 获取可关联到测试单 1 的用例，检查数量。
r(count($cases2))  && p() && e('10'); // 获取可关联到测试单 2 的用例，检查数量。
r(count($cases3))  && p() && e('10'); // 获取可关联到测试单 3 的用例，检查数量。
r(count($cases4))  && p() && e('9');  // 获取可关联到测试单 3 的用例，传入sql语句，检查数量。
r(count($cases5))  && p() && e('9');  // 获取可关联到测试单 3 的用例，已关联的用例 ID，检查数量。
r(count($cases6))  && p() && e('5');  // 获取可关联到测试单 3 的用例，传入分页器，检查数量。
r(count($cases7))  && p() && e('8');  // 获取可关联到测试单 3 的用例，传入sql语句和已关联的用例 ID，检查数量。
r(count($cases8))  && p() && e('5');  // 获取可关联到测试单 3 的用例，传入sql语句和分页器，检查数量。
r(count($cases9))  && p() && e('5');  // 获取可关联到测试单 3 的用例，已关联的用例 ID 和分页器，检查数量。
r(count($cases10)) && p() && e('5');  // 获取可关联到测试单 3 的用例，传入sql语句、已关联的用例 ID 和分页器，检查数量。
r(count($cases11)) && p() && e('4');  // 获取可关联到测试单 3 的用例，传入sql语句、已关联的用例 ID 和分页器，检查数量。

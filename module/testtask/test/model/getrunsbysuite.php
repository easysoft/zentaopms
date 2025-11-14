#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('story')->gen(2);
zenData('testsuite')->gen(1);
$case = zenData('case')->loadYaml('case');
$case->product->range('1');
$case->gen(10);
$testrun = zenData('testrun')->loadYaml('testrun');
$testrun->task->range('1');
$testrun->gen(10);
$suitecase = zenData('suitecase');
$suitecase->suite->range('1');
$suitecase->product->range('1');
$suitecase->gen(7);

/**

title=测试 testtaskModel->getRunsBySuite();
timeout=0
cid=19192

- 查看测试单 0 套件 0 包含的用例数。 @0
- 查看测试单 2 套件 0 包含的用例数。 @0
- 查看测试单 0 套件 1 包含的用例数。 @0
- 查看测试单 2 套件 1 包含的用例数。 @0
- 按 id 正序排列，查看测试单 1 套件 1 包含的用例数。 @7
- 按 id 正序排列，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @1
 - 第0条的title属性 @这个是测试用例1
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @软件需求2

*/

global $tester, $app;

$app->rawModule = 'testtask';
$app->rawMethod = 'cases';
$app->loadClass('pager', true);
$pager = new pager(0, 5, 1);

$testtask = $tester->loadModel('testtask');

r($testtask->getRunsBySuite(0, 0, 'id_desc', $pager)) && p() && e(0); // 查看测试单 0 套件 0 包含的用例数。
r($testtask->getRunsBySuite(2, 0, 'id_desc', $pager)) && p() && e(0); // 查看测试单 2 套件 0 包含的用例数。
r($testtask->getRunsBySuite(0, 1, 'id_desc', $pager)) && p() && e(0); // 查看测试单 0 套件 1 包含的用例数。
r($testtask->getRunsBySuite(2, 1, 'id_desc', $pager)) && p() && e(0); // 查看测试单 2 套件 1 包含的用例数。

$runs = $testtask->getRunsBySuite(1, 1, 'id_asc', null);
$runs = array_values($runs);
r(count($runs)) && p() && e(7); // 按 id 正序排列，查看测试单 1 套件 1 包含的用例数。
r($runs) && p('0:id,title,version,status,caseStatus,storyTitle') && e('1,这个是测试用例1,1,normal,wait,软件需求2');    // 按 id 正序排列，查看测试单 1 包含的第 1 条用例。
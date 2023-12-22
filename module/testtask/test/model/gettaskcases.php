#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('case')->config('case')->gen(10);
zdTable('testrun')->config('testrun')->gen(10);
zdTable('testsuite')->config('testsuite')->gen(2);
zdTable('suitecase')->config('suitecase')->gen(10);
zdTable('story')->gen(2);
zdTable('module')->gen(2);
zdTable('user')->gen(2);

su('user1');

/**

title=测试 testtaskModel->getTaskCases();
cid=1
pid=1

*/

global $tester, $app;

$app->setModuleName('testtask');
$app->setMethodName('cases');
$app->loadClass('pager', true);

$null     = new stdclass();
$pager    = new pager(0, 5, 1);
$testtask = $tester->loadModel('testtask');

$task1 = (object)array('id' => 0, 'branch' => 0);
$task2 = (object)array('id' => 1, 'branch' => 0);
$task3 = (object)array('id' => 2, 'branch' => 0);

r($testtask->getTaskCases(1, '',      1, 1, 'id_asc', $pager, $task2)) && p() && e(0); // $browseType 参数为空，查看测试单 1 包含的用例数。
r($testtask->getTaskCases(1, 'other', 1, 1, 'id_asc', $pager, $task2)) && p() && e(0); // $browseType 参数为 other，查看测试单 1 包含的用例数。

r($testtask->getTaskCases(1, 'bymodule', 0, 1, 'id_asc', $pager, $task1)) && p() && e(0); // $browseType 参数为 bymodule，查看测试单 0 包含的用例数。
r($testtask->getTaskCases(1, 'bymodule', 0, 1, 'id_asc', $pager, $task3)) && p() && e(0); // $browseType 参数为 bymodule，查看测试单 2 包含的用例数。

$cases = $testtask->getTaskCases(1, 'bymodule', 0, 1, 'id_asc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(4); // $browseType 参数为 bymodule，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('1,测试用例1,1,normal,wait,用户需求1');  // $browseType 参数为 bymodule，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('3,测试用例3,1,done,blocked,用户需求1'); // $browseType 参数为 bymodule，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。

$cases = $testtask->getTaskCases(1, 'bymodule', 0, 1, 'id_desc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(4); // $browseType 参数为 bymodule，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('9,测试用例9,1,done,wait,用户需求1');      // $browseType 参数为 bymodule，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('7,测试用例7,1,normal,blocked,用户需求1'); // $browseType 参数为 bymodule，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。

r($testtask->getTaskCases(1, 'all', 0, 1, 'id_asc', $pager, $task1)) && p() && e(0); // $browseType 参数为 all，查看测试单 0 包含用例数。
r($testtask->getTaskCases(1, 'all', 0, 1, 'id_asc', $pager, $task3)) && p() && e(0); // $browseType 参数为 all，查看测试单 2 包含用例数。

$cases = $testtask->getTaskCases(1, 'all', 0, 1, 'id_asc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(4); // $browseType 参数为 all，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('1,测试用例1,1,normal,wait,用户需求1');  // $browseType 参数为 all，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('3,测试用例3,1,done,blocked,用户需求1'); // $browseType 参数为 all，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。

$cases = $testtask->getTaskCases(1, 'all', 0, 1, 'id_desc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(4); // $browseType 参数为 all，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('9,测试用例9,1,done,wait,用户需求1');      // $browseType 参数为 all，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('7,测试用例7,1,normal,blocked,用户需求1'); // $browseType 参数为 all，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。

r($testtask->getTaskCases(1, 'bysuite', 0, 1, 'id_asc', $pager, $task1)) && p() && e(0); // $browseType 参数为 bysuite，查看测试单 0 包含用例数。
r($testtask->getTaskCases(1, 'bysuite', 0, 1, 'id_asc', $pager, $task3)) && p() && e(0); // $browseType 参数为 bysuite，查看测试单 2 包含用例数。

$cases = $testtask->getTaskCases(1, 'bysuite', 1, 1, 'id_asc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(5); // $browseType 参数为 bysuite，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('1,测试用例1,1,normal,wait,用户需求1');    // $browseType 参数为 bysuite，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('2,测试用例2,1,blocked,normal,软件需求2'); // $browseType 参数为 bysuite，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。

$cases = $testtask->getTaskCases(1, 'bysuite', 1, 1, 'id_desc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(5); // $browseType 参数为 bysuite，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('9,测试用例9,1,done,wait,用户需求1');           // $browseType 参数为 bysuite，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('8,测试用例8,1,blocked,investigate,软件需求2'); // $browseType 参数为 bysuite，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。

r($testtask->getTaskCases(1, 'assignedtome', 0, 1, 'id_asc', $pager, $task1)) && p() && e(0); // $browseType 参数为 assignedtome，查看测试单 0 包含用例数。
r($testtask->getTaskCases(1, 'assignedtome', 0, 1, 'id_asc', $pager, $task3)) && p() && e(0); // $browseType 参数为 assignedtome，查看测试单 2 包含用例数。

$cases = $testtask->getTaskCases(1, 'assignedtome', 0, 1, 'id_asc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(3); // $browseType 参数为 assignedtome，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('1,测试用例1,1,normal,wait,用户需求1');  // $browseType 参数为 assignedtome，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('3,测试用例3,1,done,blocked,用户需求1'); // $browseType 参数为 assignedtome，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。

$cases = $testtask->getTaskCases(1, 'assignedtome', 0, 1, 'id_desc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(3); // $browseType 参数为 assignedtome，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('7,测试用例7,1,normal,blocked,用户需求1'); // $browseType 参数为 assignedtome，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('3,测试用例3,1,done,blocked,用户需求1');   // $browseType 参数为 assignedtome，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。

r($testtask->getTaskCases(1, 'bysearch', 0, 1, 'id_asc', $pager, $task1)) && p() && e(0); // $browseType 参数为 bysearch，查看测试单 0 包含用例数。
r($testtask->getTaskCases(1, 'bysearch', 0, 1, 'id_asc', $pager, $task3)) && p() && e(0); // $browseType 参数为 bysearch，查看测试单 2 包含用例数。

$cases = $testtask->getTaskCases(1, 'bysearch', 0, 1, 'id_asc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(5); // $browseType 参数为 bysearch，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('1,测试用例1,1,normal,wait,用户需求1');    // $browseType 参数为 bysearch，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('2,测试用例2,1,blocked,normal,软件需求2'); // $browseType 参数为 bysearch，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。

$cases = $testtask->getTaskCases(1, 'bysearch', 0, 1, 'id_desc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(5); // $browseType 参数为 bysearch，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('9,测试用例9,1,done,wait,用户需求1');           // $browseType 参数为 bysearch，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('8,测试用例8,1,blocked,investigate,软件需求2'); // $browseType 参数为 bysearch，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。

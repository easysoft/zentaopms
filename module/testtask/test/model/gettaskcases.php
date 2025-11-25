#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('case')->loadYaml('case')->gen(10);
zenData('testrun')->loadYaml('testrun')->gen(10);
zenData('testsuite')->loadYaml('testsuite')->gen(2);
zenData('suitecase')->loadYaml('suitecase')->gen(10);
zenData('story')->gen(2);
zenData('module')->gen(2);
zenData('user')->gen(2);

su('user1');

/**

title=测试 testtaskModel->getTaskCases();
timeout=0
cid=19195

- $browseType 参数为空，查看测试单 1 包含的用例数。 @0
- $browseType 参数为 other，查看测试单 1 包含的用例数。 @0
- $browseType 参数为 bymodule，查看测试单 0 包含的用例数。 @0
- $browseType 参数为 bymodule，查看测试单 2 包含的用例数。 @0
- $browseType 参数为 bymodule，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。 @4
- $browseType 参数为 bymodule，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @1
 - 第0条的title属性 @测试用例1
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- $browseType 参数为 bymodule，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @3
 - 第1条的title属性 @测试用例3
 - 第1条的version属性 @1
 - 第1条的status属性 @done
 - 第1条的caseStatus属性 @blocked
 - 第1条的storyTitle属性 @用户需求1
- $browseType 参数为 bymodule，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。 @4
- $browseType 参数为 bymodule，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @9
 - 第0条的title属性 @测试用例9
 - 第0条的version属性 @1
 - 第0条的status属性 @done
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- $browseType 参数为 bymodule，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @7
 - 第1条的title属性 @测试用例7
 - 第1条的version属性 @1
 - 第1条的status属性 @normal
 - 第1条的caseStatus属性 @blocked
 - 第1条的storyTitle属性 @用户需求1
- $browseType 参数为 all，查看测试单 0 包含用例数。 @0
- $browseType 参数为 all，查看测试单 2 包含用例数。 @0
- $browseType 参数为 all，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。 @4
- $browseType 参数为 all，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @1
 - 第0条的title属性 @测试用例1
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- $browseType 参数为 all，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @3
 - 第1条的title属性 @测试用例3
 - 第1条的version属性 @1
 - 第1条的status属性 @done
 - 第1条的caseStatus属性 @blocked
 - 第1条的storyTitle属性 @用户需求1
- $browseType 参数为 all，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。 @4
- $browseType 参数为 all，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @9
 - 第0条的title属性 @测试用例9
 - 第0条的version属性 @1
 - 第0条的status属性 @done
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- $browseType 参数为 all，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @7
 - 第1条的title属性 @测试用例7
 - 第1条的version属性 @1
 - 第1条的status属性 @normal
 - 第1条的caseStatus属性 @blocked
 - 第1条的storyTitle属性 @用户需求1
- $browseType 参数为 bysuite，查看测试单 0 包含用例数。 @0
- $browseType 参数为 bysuite，查看测试单 2 包含用例数。 @0
- $browseType 参数为 bysuite，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。 @5
- $browseType 参数为 bysuite，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @1
 - 第0条的title属性 @测试用例1
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- $browseType 参数为 bysuite，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @2
 - 第1条的title属性 @测试用例2
 - 第1条的version属性 @1
 - 第1条的status属性 @blocked
 - 第1条的caseStatus属性 @normal
 - 第1条的storyTitle属性 @软件需求2
- $browseType 参数为 bysuite，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。 @5
- $browseType 参数为 bysuite，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @9
 - 第0条的title属性 @测试用例9
 - 第0条的version属性 @1
 - 第0条的status属性 @done
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- $browseType 参数为 bysuite，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @8
 - 第1条的title属性 @测试用例8
 - 第1条的version属性 @1
 - 第1条的status属性 @blocked
 - 第1条的caseStatus属性 @investigate
 - 第1条的storyTitle属性 @软件需求2
- $browseType 参数为 assignedtome，查看测试单 0 包含用例数。 @0
- $browseType 参数为 assignedtome，查看测试单 2 包含用例数。 @0
- $browseType 参数为 assignedtome，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。 @3
- $browseType 参数为 assignedtome，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @1
 - 第0条的title属性 @测试用例1
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- $browseType 参数为 assignedtome，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @3
 - 第1条的title属性 @测试用例3
 - 第1条的version属性 @1
 - 第1条的status属性 @done
 - 第1条的caseStatus属性 @blocked
 - 第1条的storyTitle属性 @用户需求1
- $browseType 参数为 assignedtome，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。 @3
- $browseType 参数为 assignedtome，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @7
 - 第0条的title属性 @测试用例7
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @blocked
 - 第0条的storyTitle属性 @用户需求1
- $browseType 参数为 assignedtome，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @3
 - 第1条的title属性 @测试用例3
 - 第1条的version属性 @1
 - 第1条的status属性 @done
 - 第1条的caseStatus属性 @blocked
 - 第1条的storyTitle属性 @用户需求1
- $browseType 参数为 bysearch，查看测试单 0 包含用例数。 @0
- $browseType 参数为 bysearch，查看测试单 2 包含用例数。 @0
- $browseType 参数为 bysearch，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。 @5
- $browseType 参数为 bysearch，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @1
 - 第0条的title属性 @测试用例1
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- $browseType 参数为 bysearch，按模块 1 过滤并按 id 正序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @2
 - 第1条的title属性 @测试用例2
 - 第1条的version属性 @1
 - 第1条的status属性 @blocked
 - 第1条的caseStatus属性 @normal
 - 第1条的storyTitle属性 @软件需求2
- $browseType 参数为 bysearch，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。 @5
- $browseType 参数为 bysearch，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @9
 - 第0条的title属性 @测试用例9
 - 第0条的version属性 @1
 - 第0条的status属性 @done
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- $browseType 参数为 bysearch，按模块 1 过滤并按 id 倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @8
 - 第1条的title属性 @测试用例8
 - 第1条的version属性 @1
 - 第1条的status属性 @blocked
 - 第1条的caseStatus属性 @investigate
 - 第1条的storyTitle属性 @软件需求2

*/

global $tester, $app;

$app->rawModule = 'testtask';
$app->rawMethod = 'cases';
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

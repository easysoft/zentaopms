#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('case')->loadYaml('case')->gen(10);
zenData('testrun')->loadYaml('testrun')->gen(10);
zenData('story')->gen(2);

/**

title=测试 testtaskModel->getRuns();
timeout=0
cid=19191

- 查看测试单 0 包含的用例数。 @0
- 查看测试单 2 包含的用例数。 @0
- 按 id 正序排列，查看测试单 1 包含的用例数。 @8
- 按 id 正序排列，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @1
 - 第0条的title属性 @测试用例1
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- 按 id 正序排列，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @2
 - 第1条的title属性 @测试用例2
 - 第1条的version属性 @1
 - 第1条的status属性 @blocked
 - 第1条的caseStatus属性 @normal
 - 第1条的storyTitle属性 @软件需求2
- 按 id 倒序排列，查看测试单 1 包含的用例数。 @8
- 按 id 倒序排列，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @9
 - 第0条的title属性 @测试用例9
 - 第0条的version属性 @1
 - 第0条的status属性 @done
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- 按 id 倒序排列，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @8
 - 第1条的title属性 @测试用例8
 - 第1条的version属性 @1
 - 第1条的status属性 @blocked
 - 第1条的caseStatus属性 @investigate
 - 第1条的storyTitle属性 @软件需求2
- 按模块 1 过滤并按 id 正序排列，查看测试单 1 包含的用例数。 @4
- 按模块 1 过滤并按 id 正序排列，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @1
 - 第0条的title属性 @测试用例1
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- 按模块 1 过滤并按 id 正序排列，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @3
 - 第1条的title属性 @测试用例3
 - 第1条的version属性 @1
 - 第1条的status属性 @done
 - 第1条的caseStatus属性 @blocked
 - 第1条的storyTitle属性 @用户需求1
- 按模块 1、2 过滤并按 id 倒序排列，查看测试单 1 包含的用例数。 @8
- 按模块 1、2 过滤并按 id 倒序排列，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @9
 - 第0条的title属性 @测试用例9
 - 第0条的version属性 @1
 - 第0条的status属性 @done
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- 按模块 1、2 过滤并按 id 倒序排列，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @8
 - 第1条的title属性 @测试用例8
 - 第1条的version属性 @1
 - 第1条的status属性 @blocked
 - 第1条的caseStatus属性 @investigate
 - 第1条的storyTitle属性 @软件需求2
- 按模块 1 过滤并按状态正序排列，查看测试单 1 包含的用例数。 @4
- 按模块 1 过滤并按状态正序排列，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @3
 - 第0条的title属性 @测试用例3
 - 第0条的version属性 @1
 - 第0条的status属性 @done
 - 第0条的caseStatus属性 @blocked
 - 第0条的storyTitle属性 @用户需求1
- 按模块 1 过滤并按状态正序排列，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @9
 - 第1条的title属性 @测试用例9
 - 第1条的version属性 @1
 - 第1条的status属性 @done
 - 第1条的caseStatus属性 @wait
 - 第1条的storyTitle属性 @用户需求1
- 按模块 1、2 过滤并按状态倒序排列，查看测试单 1 包含的用例数。 @8
- 按模块 1、2 过滤并按状态倒序排列，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @1
 - 第0条的title属性 @测试用例1
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- 按模块 1、2 过滤并按状态倒序排列，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @7
 - 第1条的title属性 @测试用例7
 - 第1条的version属性 @1
 - 第1条的status属性 @normal
 - 第1条的caseStatus属性 @blocked
 - 第1条的storyTitle属性 @用户需求1
- 按模块 1 过滤并按状态正序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。 @4
- 按模块 1 过滤并按状态正序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @3
 - 第0条的title属性 @测试用例3
 - 第0条的version属性 @1
 - 第0条的status属性 @done
 - 第0条的caseStatus属性 @blocked
 - 第0条的storyTitle属性 @用户需求1
- 按模块 1 过滤并按状态正序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @9
 - 第1条的title属性 @测试用例9
 - 第1条的version属性 @1
 - 第1条的status属性 @done
 - 第1条的caseStatus属性 @wait
 - 第1条的storyTitle属性 @用户需求1
- 按模块 1、2 过滤并按状态倒序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。 @5
- 按模块 1、2 过滤并按状态倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
 - 第0条的id属性 @1
 - 第0条的title属性 @测试用例1
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- 按模块 1、2 过滤并按状态倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。
 - 第1条的id属性 @7
 - 第1条的title属性 @测试用例7
 - 第1条的version属性 @1
 - 第1条的status属性 @normal
 - 第1条的caseStatus属性 @blocked
 - 第1条的storyTitle属性 @用户需求1

*/
su('admin');

global $tester, $app;

$app->rawModule = 'testtask';
$app->rawMethod = 'groupCase';
$app->loadClass('pager', true);
$pager = new pager(0, 5, 1);

$testtask = $tester->loadModel('testtask');

r($testtask->getRuns(0, array(1), 'id_desc', $pager)) && p() && e(0); // 查看测试单 0 包含的用例数。
r($testtask->getRuns(2, array(1), 'id_desc', $pager)) && p() && e(0); // 查看测试单 2 包含的用例数。

$runs = $testtask->getRuns(1, array(), 'id_asc', null);
$runs = array_values($runs);
r(count($runs)) && p() && e(8); // 按 id 正序排列，查看测试单 1 包含的用例数。
r($runs) && p('0:id,title,version,status,caseStatus,storyTitle') && e('1,测试用例1,1,normal,wait,用户需求1');    // 按 id 正序排列，查看测试单 1 包含的第 1 条用例。
r($runs) && p('1:id,title,version,status,caseStatus,storyTitle') && e('2,测试用例2,1,blocked,normal,软件需求2'); // 按 id 正序排列，查看测试单 1 包含的第 2 条用例。

$runs = $testtask->getRuns(1, array(), 'id_desc', null);
$runs = array_values($runs);
r(count($runs)) && p() && e(8); // 按 id 倒序排列，查看测试单 1 包含的用例数。
r($runs) && p('0:id,title,version,status,caseStatus,storyTitle') && e('9,测试用例9,1,done,wait,用户需求1');           // 按 id 倒序排列，查看测试单 1 包含的第 1 条用例。
r($runs) && p('1:id,title,version,status,caseStatus,storyTitle') && e('8,测试用例8,1,blocked,investigate,软件需求2'); // 按 id 倒序排列，查看测试单 1 包含的第 2 条用例。

$runs = $testtask->getRuns(1, array(1), 'id_asc', null);
$runs = array_values($runs);
r(count($runs)) && p() && e(4); // 按模块 1 过滤并按 id 正序排列，查看测试单 1 包含的用例数。
r($runs) && p('0:id,title,version,status,caseStatus,storyTitle') && e('1,测试用例1,1,normal,wait,用户需求1');  // 按模块 1 过滤并按 id 正序排列，查看测试单 1 包含的第 1 条用例。
r($runs) && p('1:id,title,version,status,caseStatus,storyTitle') && e('3,测试用例3,1,done,blocked,用户需求1'); // 按模块 1 过滤并按 id 正序排列，查看测试单 1 包含的第 2 条用例。

$runs = $testtask->getRuns(1, array(1,2), 'id_desc', null);
$runs = array_values($runs);
r(count($runs)) && p() && e(8); // 按模块 1、2 过滤并按 id 倒序排列，查看测试单 1 包含的用例数。
r($runs) && p('0:id,title,version,status,caseStatus,storyTitle') && e('9,测试用例9,1,done,wait,用户需求1');           // 按模块 1、2 过滤并按 id 倒序排列，查看测试单 1 包含的第 1 条用例。
r($runs) && p('1:id,title,version,status,caseStatus,storyTitle') && e('8,测试用例8,1,blocked,investigate,软件需求2'); // 按模块 1、2 过滤并按 id 倒序排列，查看测试单 1 包含的第 2 条用例。

$runs = $testtask->getRuns(1, array(1), 'status_asc', null);
$runs = array_values($runs);
r(count($runs)) && p() && e(4); // 按模块 1 过滤并按状态正序排列，查看测试单 1 包含的用例数。
r($runs) && p('0:id,title,version,status,caseStatus,storyTitle') && e('3,测试用例3,1,done,blocked,用户需求1'); // 按模块 1 过滤并按状态正序排列，查看测试单 1 包含的第 1 条用例。
r($runs) && p('1:id,title,version,status,caseStatus,storyTitle') && e('9,测试用例9,1,done,wait,用户需求1');    // 按模块 1 过滤并按状态正序排列，查看测试单 1 包含的第 2 条用例。

$runs = $testtask->getRuns(1, array(1,2), 'status_desc', null);
$runs = array_values($runs);
r(count($runs)) && p() && e(8); // 按模块 1、2 过滤并按状态倒序排列，查看测试单 1 包含的用例数。
r($runs) && p('0:id,title,version,status,caseStatus,storyTitle') && e('1,测试用例1,1,normal,wait,用户需求1');    // 按模块 1、2 过滤并按状态倒序排列，查看测试单 1 包含的第 1 条用例。
r($runs) && p('1:id,title,version,status,caseStatus,storyTitle') && e('7,测试用例7,1,normal,blocked,用户需求1'); // 按模块 1、2 过滤并按状态倒序排列，查看测试单 1 包含的第 2 条用例。

$runs = $testtask->getRuns(1, array(1), 'status_asc', $pager);
$runs = array_values($runs);
r(count($runs)) && p() && e(4); // 按模块 1 过滤并按状态正序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。
r($runs) && p('0:id,title,version,status,caseStatus,storyTitle') && e('3,测试用例3,1,done,blocked,用户需求1'); // 按模块 1 过滤并按状态正序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
r($runs) && p('1:id,title,version,status,caseStatus,storyTitle') && e('9,测试用例9,1,done,wait,用户需求1');    // 按模块 1 过滤并按状态正序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。

$runs = $testtask->getRuns(1, array(1,2), 'status_desc', $pager);
$runs = array_values($runs);
r(count($runs)) && p() && e(5); // 按模块 1、2 过滤并按状态倒序排列，每页限制查询 5 条，查看测试单 1 包含的用例数。
r($runs) && p('0:id,title,version,status,caseStatus,storyTitle') && e('1,测试用例1,1,normal,wait,用户需求1');    // 按模块 1、2 过滤并按状态倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 1 条用例。
r($runs) && p('1:id,title,version,status,caseStatus,storyTitle') && e('7,测试用例7,1,normal,blocked,用户需求1'); // 按模块 1、2 过滤并按状态倒序排列，每页限制查询 5 条，查看测试单 1 包含的第 2 条用例。

#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('bug')->gen(100);
zenData('product')->gen(100);

/**

title=bugModel->getUserBugPairs();
cid=15399

- 测试获取用户admin 的bug @30
- 测试获取用户admin 追加产品名称 的bug @30
- 测试获取用户admin 限制数量10 的bug @10
- 测试获取用户admin 跳过产品2 4 的bug @24
- 测试获取用户admin 跳过执行 11 13 的bug @30
- 测试获取用户admin 追加bug 2 4 的bug @30
- 测试获取用户admin 追加产品名称 限制数量10 跳过产品2 4 跳过执行 11 13 追加bug 2 4 的bug @10
- 测试获取用户test1的bug @20
- 测试获取用户test2的bug @0
- 测试获取用户dev1的bug @20
- 测试获取用户po1的bug @0

*/

$accountIDList = array('admin', 'test1', 'test2', 'dev1', 'po1');

$appendProduct       = array(true, false);
$limit               = array(0, 10);
$skipProductIdList   = array(array(), array(2, 4));
$skipExecutionIdList = array(array(), array(11, 13));
$appendBugID         = array(array(), array(2, 4));

$bug=new bugModelTest();
r($bug->getUserBugPairsTest($accountIDList[0], $appendProduct[0], $limit[0], $skipProductIdList[0], $skipExecutionIdList[0], $appendBugID[0])) && p() && e('30'); // 测试获取用户admin 的bug
r($bug->getUserBugPairsTest($accountIDList[0], $appendProduct[1], $limit[0], $skipProductIdList[0], $skipExecutionIdList[0], $appendBugID[0])) && p() && e('30'); // 测试获取用户admin 追加产品名称 的bug
r($bug->getUserBugPairsTest($accountIDList[0], $appendProduct[0], $limit[1], $skipProductIdList[0], $skipExecutionIdList[0], $appendBugID[0])) && p() && e('10'); // 测试获取用户admin 限制数量10 的bug
r($bug->getUserBugPairsTest($accountIDList[0], $appendProduct[0], $limit[0], $skipProductIdList[1], $skipExecutionIdList[0], $appendBugID[0])) && p() && e('24'); // 测试获取用户admin 跳过产品2 4 的bug
r($bug->getUserBugPairsTest($accountIDList[0], $appendProduct[0], $limit[0], $skipProductIdList[0], $skipExecutionIdList[1], $appendBugID[0])) && p() && e('30'); // 测试获取用户admin 跳过执行 11 13 的bug
r($bug->getUserBugPairsTest($accountIDList[0], $appendProduct[0], $limit[0], $skipProductIdList[0], $skipExecutionIdList[0], $appendBugID[1])) && p() && e('30'); // 测试获取用户admin 追加bug 2 4 的bug
r($bug->getUserBugPairsTest($accountIDList[0], $appendProduct[1], $limit[1], $skipProductIdList[1], $skipExecutionIdList[1], $appendBugID[1])) && p() && e('10'); // 测试获取用户admin 追加产品名称 限制数量10 跳过产品2 4 跳过执行 11 13 追加bug 2 4 的bug

r($bug->getUserBugPairsTest($accountIDList[1], $appendProduct[0], $limit[0], $skipProductIdList[0], $skipExecutionIdList[0], $appendBugID[0])) && p() && e('20'); // 测试获取用户test1的bug
r($bug->getUserBugPairsTest($accountIDList[2], $appendProduct[0], $limit[0], $skipProductIdList[0], $skipExecutionIdList[0], $appendBugID[0])) && p() && e('0');  // 测试获取用户test2的bug
r($bug->getUserBugPairsTest($accountIDList[3], $appendProduct[0], $limit[0], $skipProductIdList[0], $skipExecutionIdList[0], $appendBugID[0])) && p() && e('20'); // 测试获取用户dev1的bug
r($bug->getUserBugPairsTest($accountIDList[4], $appendProduct[0], $limit[0], $skipProductIdList[0], $skipExecutionIdList[0], $appendBugID[0])) && p() && e('0');  // 测试获取用户po1的bug

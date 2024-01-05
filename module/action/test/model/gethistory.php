#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->gen(10);
zdTable('history')->config('history')->gen(10);

/**

title=测试 actionModel->getHistory();
timeout=0
cid=1

- 查找actionID为1的历史记录
 - 第0条的field属性 @resolution
 - 第0条的old属性 @1
 - 第0条的new属性 @2
- 查找actionID为2的历史记录
 - 第0条的field属性 @resolvedBuild
 - 第0条的old属性 @2
 - 第0条的new属性 @3
- 查找actionID为3的历史记录 @0

*/

$actionIDList = array('1', '2', '10000');

$action = new actionTest();

r($action->getHistoryTest($actionIDList[0])) && p("0:field,old,new") && e('resolution,1,2');                     // 查找actionID为1的历史记录
r($action->getHistoryTest($actionIDList[1])) && p("0:field,old,new") && e('resolvedBuild,2,3');                  // 查找actionID为2的历史记录
r($action->getHistoryTest($actionIDList[2])) && p("") && e('0');                                                 // 查找actionID为3的历史记录
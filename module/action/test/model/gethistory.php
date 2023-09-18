#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->gen(10);
zdTable('history')->config('history')->gen(10);

/**

title=测试 actionModel->getHistory();
cid=1
pid=1

查找actionID为1的历史记录     >> resolution,1,2
查找actionID为2的历史记录     >> resolvedBuild,2,3
查找actionID为10000的历史记录 >> 0

*/

$actionIDList = array('1', '2', '10000');

$action = new actionTest();

r($action->getHistoryTest($actionIDList[0])) && p("0:field,old,new") && e('resolution,1,2');                     // 查找actionID为1的历史记录
r($action->getHistoryTest($actionIDList[1])) && p("0:field,old,new") && e('resolvedBuild,2,3');                  // 查找actionID为2的历史记录
r($action->getHistoryTest($actionIDList[2])) && p("") && e('0');                                                 // 查找actionID为3的历史记录

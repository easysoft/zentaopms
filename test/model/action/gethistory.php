#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->getHistory();
cid=1
pid=1

查找actionID为1的历史记录 >> resolution,1,2
查找actionID为2的历史记录 >> resolvedBuild,2,3
查找actionID为3的历史记录 >> resolvedDate,2020-01-01,2020-01-17
查找actionID为4的历史记录 >> status,doing,done
查找actionID为5的历史记录 >> resolution,1,2

*/
$actionIDList = array('1', '2', '3', '4', '5');

$action = new actionTest();

r($action->getHistoryTest($actionIDList[0])) && p("0:field,old,new") && e('resolution,1,2');                     // 查找actionID为1的历史记录
r($action->getHistoryTest($actionIDList[1])) && p("0:field,old,new") && e('resolvedBuild,2,3');                  // 查找actionID为2的历史记录
r($action->getHistoryTest($actionIDList[2])) && p("0:field,old,new") && e('resolvedDate,2020-01-01,2020-01-17'); // 查找actionID为3的历史记录
r($action->getHistoryTest($actionIDList[3])) && p("0:field,old,new") && e('status,doing,done');                  // 查找actionID为4的历史记录
r($action->getHistoryTest($actionIDList[4])) && p("0:field,old,new") && e('resolution,1,2');                     // 查找actionID为5的历史记录
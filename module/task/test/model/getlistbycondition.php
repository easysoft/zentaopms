#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('task')->config('task')->gen(30);


/**

title=taskModel->getListByCondition();
timeout=0
cid=1

*/

$priList        = array(1, 2, 3);
$assignedToList = array('admin', 'user1');
$statusList     = array('wait', 'doing');
$idList         = array(1, 2, 3);
$taskName       = '任务';

$conditionList[0] = array('priList' => $priList);
$conditionList[1] = array('assignedToList' => $assignedToList);
$conditionList[2] = array('statusList' => $statusList);
$conditionList[3] = array('idList' => $idList);
$conditionList[4] = array('taskName' => $taskName);
$conditionList[5] = array('priList' => $priList, 'assignedToList' => $assignedToList, 'statusList' => $statusList, 'idList' => $idList, 'taskName' => $taskName);

$orderByList = array('id_asc', 'deadline_desc');

$taskTester = new taskTest();
r(count($taskTester->getListByConditionTest($conditionList[0], $orderByList[0]))) && p() && e('19'); // 测试按照优先级查询任务的数量
r(count($taskTester->getListByConditionTest($conditionList[1], $orderByList[0]))) && p() && e('10'); // 测试按照指派给查询任务的数量
r(count($taskTester->getListByConditionTest($conditionList[2], $orderByList[0]))) && p() && e('17'); // 测试按照状态查询任务的数量
r(count($taskTester->getListByConditionTest($conditionList[3], $orderByList[0]))) && p() && e('3');  // 测试按照id查询任务的数量
r(count($taskTester->getListByConditionTest($conditionList[4], $orderByList[0]))) && p() && e('24'); // 测试按照任务名称模糊查询任务的数量
r(count($taskTester->getListByConditionTest($conditionList[5], $orderByList[0]))) && p() && e('1');  // 测试按照组合查询任务的数量

r($taskTester->getListByConditionTest($conditionList[0], $orderByList[1])) && p('30:name,pri')        && e('开发任务40,2');     // 测试按照优先级查询任务列表
r($taskTester->getListByConditionTest($conditionList[1], $orderByList[1])) && p('27:name,assignedTo') && e('开发任务37,admin'); // 测试按照指派给查询任务列表
r($taskTester->getListByConditionTest($conditionList[2], $orderByList[1])) && p('29:name,status')     && e('开发任务39,doing'); // 测试按照状态查询任务列表
r($taskTester->getListByConditionTest($conditionList[3], $orderByList[1])) && p('1:name,id')          && e('开发任务11,1');     // 测试按照状态查询任务列表
r($taskTester->getListByConditionTest($conditionList[4], $orderByList[1])) && p('1:name')             && e('开发任务11');       // 测试按照任务名称模糊查询任务列表
r($taskTester->getListByConditionTest($conditionList[5], $orderByList[1])) && p('2:name')             && e('开发任务12');       // 测试按照组合查询任务列表

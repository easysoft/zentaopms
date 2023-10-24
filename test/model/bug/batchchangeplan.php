#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=测试bugModel->batchChangePlan();
cid=1
pid=1

修改计划为0 未发生变化 >> 0
修改计划为计划1 >> plan,0,1
修改计划为计划2 >> plan,1,2
修改计划为0 未发生变化 >> 0
修改计划为计划1 >> plan,0,1
修改计划为计划2 >> plan,1,2
修改计划为0 未发生变化 >> 0
修改计划为计划1 >> plan,0,1
修改计划为计划2 >> plan,1,2
修改计划为0 未发生变化 >> 0
修改计划为计划1 >> plan,0,1
修改计划为计划2 >> plan,1,2

*/

$bugIDList1 = array('136', '137', '138');
$bugIDList2 = array('133', '134', '135');
$bugIDList3 = array('130', '131', '132');
$bugIDList4 = array('175', '176', '177');

$planList = array('0', '1', '2');

$bug = new bugTest();
r($bug->batchChangePlanTest($bugIDList1, $planList[0], $bugIDList1[0])) && p()                  && e('0');        // 修改计划为0 未发生变化
r($bug->batchChangePlanTest($bugIDList1, $planList[1], $bugIDList1[1])) && p('0:field,old,new') && e('plan,0,1'); // 修改计划为计划1
r($bug->batchChangePlanTest($bugIDList1, $planList[2], $bugIDList1[2])) && p('0:field,old,new') && e('plan,1,2'); // 修改计划为计划2
r($bug->batchChangePlanTest($bugIDList2, $planList[0], $bugIDList2[0])) && p()                  && e('0');        // 修改计划为0 未发生变化
r($bug->batchChangePlanTest($bugIDList2, $planList[1], $bugIDList2[1])) && p('0:field,old,new') && e('plan,0,1'); // 修改计划为计划1
r($bug->batchChangePlanTest($bugIDList2, $planList[2], $bugIDList2[2])) && p('0:field,old,new') && e('plan,1,2'); // 修改计划为计划2
r($bug->batchChangePlanTest($bugIDList3, $planList[0], $bugIDList3[0])) && p()                  && e('0');        // 修改计划为0 未发生变化
r($bug->batchChangePlanTest($bugIDList3, $planList[1], $bugIDList3[1])) && p('0:field,old,new') && e('plan,0,1'); // 修改计划为计划1
r($bug->batchChangePlanTest($bugIDList3, $planList[2], $bugIDList3[2])) && p('0:field,old,new') && e('plan,1,2'); // 修改计划为计划2
r($bug->batchChangePlanTest($bugIDList4, $planList[0], $bugIDList4[0])) && p()                  && e('0');        // 修改计划为0 未发生变化
r($bug->batchChangePlanTest($bugIDList4, $planList[1], $bugIDList4[1])) && p('0:field,old,new') && e('plan,0,1'); // 修改计划为计划1
r($bug->batchChangePlanTest($bugIDList4, $planList[2], $bugIDList4[2])) && p('0:field,old,new') && e('plan,1,2'); // 修改计划为计划2

#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->buildDateGroup();
cid=1
pid=1

测试创建下一个动作日期组 今天 >> 3
测试创建下一个动作日期组 昨天 >> 3
测试创建下一个动作日期组 上周 >> 21
测试创建下一个动作日期组 今天 >> 3
测试创建下一个动作日期组 昨天 >> 3
测试创建下一个动作日期组 上周 >> 21
测试创建下一个动作日期组 今天 >> 3
测试创建下一个动作日期组 昨天 >> 3
测试创建下一个动作日期组 上周 >> 21

*/

$directionList = array('next', 'pre');
$typeList      = array('today', 'yesterday', 'lastweek');
$orderBy       = 'date_asc';

$action = new actionTest();

r($action->buildDateGroupTest($directionList[0], $typeList[0]))           && p() && e('3');  // 测试创建下一个动作日期组 今天
r($action->buildDateGroupTest($directionList[0], $typeList[1]))           && p() && e('3');  // 测试创建下一个动作日期组 昨天
r($action->buildDateGroupTest($directionList[0], $typeList[2]))           && p() && e('21'); // 测试创建下一个动作日期组 上周
r($action->buildDateGroupTest($directionList[0], $typeList[0], $orderBy)) && p() && e('3');  // 测试创建下一个动作日期组 今天
r($action->buildDateGroupTest($directionList[0], $typeList[1], $orderBy)) && p() && e('3');  // 测试创建下一个动作日期组 昨天
r($action->buildDateGroupTest($directionList[0], $typeList[2], $orderBy)) && p() && e('21'); // 测试创建下一个动作日期组 上周
r($action->buildDateGroupTest($directionList[1], $typeList[0]))           && p() && e('3');  // 测试创建下一个动作日期组 今天
r($action->buildDateGroupTest($directionList[1], $typeList[1]))           && p() && e('3');  // 测试创建下一个动作日期组 昨天
r($action->buildDateGroupTest($directionList[1], $typeList[2]))           && p() && e('21'); // 测试创建下一个动作日期组 上周
#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->gen(40);

/**

title=测试 actionModel->buildDateGroup();
cid=1
pid=1

测试创建下一个动作日期组 今天 >> 1;1
测试创建下一个动作日期组 昨天 >> 1;1
测试创建下一个动作日期组 上周 >> 7;1
测试创建下一个动作日期组 今天 >> 1;1
测试创建下一个动作日期组 昨天 >> 1;1
测试创建下一个动作日期组 上周 >> 7;1
测试创建下一个动作日期组 今天 >> 1;1
测试创建下一个动作日期组 昨天 >> 1;1
测试创建下一个动作日期组 上周 >> 7;1

*/

$directionList = array('next', 'pre');
$typeList      = array('today', 'yesterday', 'lastweek');
$orderBy       = 'date_asc';

$action = new actionTest();

r($action->buildDateGroupTest($directionList[0], $typeList[0]))           && p('count;sort') && e('1;1');  // 测试创建下一个动作日期组 今天
r($action->buildDateGroupTest($directionList[0], $typeList[1]))           && p('count;sort') && e('1;1');  // 测试创建下一个动作日期组 昨天
r($action->buildDateGroupTest($directionList[0], $typeList[2]))           && p('count;sort') && e('7;1'); // 测试创建下一个动作日期组 上周
r($action->buildDateGroupTest($directionList[0], $typeList[0], $orderBy)) && p('count;sort') && e('1;1');  // 测试创建下一个动作日期组 今天
r($action->buildDateGroupTest($directionList[0], $typeList[1], $orderBy)) && p('count;sort') && e('1;1');  // 测试创建下一个动作日期组 昨天
r($action->buildDateGroupTest($directionList[0], $typeList[2], $orderBy)) && p('count;sort') && e('7;1'); // 测试创建下一个动作日期组 上周
r($action->buildDateGroupTest($directionList[1], $typeList[0]))           && p('count;sort') && e('1;1');  // 测试创建下一个动作日期组 今天
r($action->buildDateGroupTest($directionList[1], $typeList[1]))           && p('count;sort') && e('1;1');  // 测试创建下一个动作日期组 昨天
r($action->buildDateGroupTest($directionList[1], $typeList[2]))           && p('count;sort') && e('7;1'); // 测试创建下一个动作日期组 上周

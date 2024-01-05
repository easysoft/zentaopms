#!/usr/bin/env php
<?php

use function zin\wg;

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
zdTable('user')->gen(60);
su('admin');

zdTable('action')->config('action')->gen(60, true, false);
zdTable('actionrecent')->config('action')->gen(60, true, false);

/**

title=测试 actionModel->buildDateGroup();
timeout=0
cid=1

- 测试创建下一个动作日期组 今天 日期倒序 前一模块 my
 - 属性dateCount @1
 - 属性dateActions @1
- 测试创建下一个动作日期组 昨天 日期倒序 前一模块 my
 - 属性dateCount @1
 - 属性dateActions @1
- 测试创建下一个动作日期组 今天 日期倒序 前一模块 company
 - 属性dateCount @1
 - 属性dateActions @1
- 测试创建下一个动作日期组 昨天 日期倒序 前一模块 company
 - 属性dateCount @1
 - 属性dateActions @1
- 测试创建下一个动作日期组 今天 日期正序 前一模块 my
 - 属性dateCount @1
 - 属性dateActions @1
- 测试创建下一个动作日期组 昨天 日期正序 前一模块 my
 - 属性dateCount @1
 - 属性dateActions @1
- 测试创建下一个动作日期组 今天 日期正序 前一模块 company
 - 属性dateCount @1
 - 属性dateActions @1
- 测试创建下一个动作日期组 昨天 日期正序 前一模块 company
 - 属性dateCount @1
 - 属性dateActions @1
- 测试创建上一个动作日期组 今天 日期倒序 前一模块 my
 - 属性dateCount @1
 - 属性dateActions @1
- 测试创建上一个动作日期组 昨天 日期倒序 前一模块 my
 - 属性dateCount @1
 - 属性dateActions @1
- 测试创建上一个动作日期组 今天 日期倒序 前一模块 company
 - 属性dateCount @1
 - 属性dateActions @1
- 测试创建上一个动作日期组 昨天 日期倒序 前一模块 company
 - 属性dateCount @1
 - 属性dateActions @1
- 测试创建上一个动作日期组 今天 日期正序 前一模块 my
 - 属性dateCount @1
 - 属性dateActions @1
- 测试创建上一个动作日期组 昨天 日期正序 前一模块 my
 - 属性dateCount @1
 - 属性dateActions @1
- 测试创建上一个动作日期组 今天 日期正序 前一模块 company
 - 属性dateCount @1
 - 属性dateActions @1
- 测试创建上一个动作日期组 昨天 日期正序 前一模块 company
 - 属性dateCount @1
 - 属性dateActions @1

*/

$directionList = array('next', 'pre');
$typeList      = array('today', 'yesterday', 'lastweek');
$orderByList   = array('date_desc', 'date_asc');
$rawMethodList = array('my', 'company');

$action = new actionTest();

r($action->buildDateGroupTest($directionList[0], $typeList[0], $orderByList[0], $rawMethodList[0])) && p('dateCount,dateActions') && e('1,1');  // 测试创建下一个动作日期组 今天 日期倒序 前一模块 my
r($action->buildDateGroupTest($directionList[0], $typeList[1], $orderByList[0], $rawMethodList[0])) && p('dateCount,dateActions') && e('1,1');  // 测试创建下一个动作日期组 昨天 日期倒序 前一模块 my
r($action->buildDateGroupTest($directionList[0], $typeList[0], $orderByList[0], $rawMethodList[1])) && p('dateCount,dateActions') && e('1,1');  // 测试创建下一个动作日期组 今天 日期倒序 前一模块 company
r($action->buildDateGroupTest($directionList[0], $typeList[1], $orderByList[0], $rawMethodList[1])) && p('dateCount,dateActions') && e('1,1');  // 测试创建下一个动作日期组 昨天 日期倒序 前一模块 company
r($action->buildDateGroupTest($directionList[0], $typeList[0], $orderByList[1], $rawMethodList[0])) && p('dateCount,dateActions') && e('1,1');  // 测试创建下一个动作日期组 今天 日期正序 前一模块 my
r($action->buildDateGroupTest($directionList[0], $typeList[1], $orderByList[1], $rawMethodList[0])) && p('dateCount,dateActions') && e('1,1');  // 测试创建下一个动作日期组 昨天 日期正序 前一模块 my
r($action->buildDateGroupTest($directionList[0], $typeList[0], $orderByList[1], $rawMethodList[1])) && p('dateCount,dateActions') && e('1,1');  // 测试创建下一个动作日期组 今天 日期正序 前一模块 company
r($action->buildDateGroupTest($directionList[0], $typeList[1], $orderByList[1], $rawMethodList[1])) && p('dateCount,dateActions') && e('1,1');  // 测试创建下一个动作日期组 昨天 日期正序 前一模块 company
r($action->buildDateGroupTest($directionList[1], $typeList[0], $orderByList[0], $rawMethodList[0])) && p('dateCount,dateActions') && e('1,1');  // 测试创建上一个动作日期组 今天 日期倒序 前一模块 my
r($action->buildDateGroupTest($directionList[1], $typeList[1], $orderByList[0], $rawMethodList[0])) && p('dateCount,dateActions') && e('1,1');  // 测试创建上一个动作日期组 昨天 日期倒序 前一模块 my
r($action->buildDateGroupTest($directionList[1], $typeList[0], $orderByList[0], $rawMethodList[1])) && p('dateCount,dateActions') && e('1,1');  // 测试创建上一个动作日期组 今天 日期倒序 前一模块 company
r($action->buildDateGroupTest($directionList[1], $typeList[1], $orderByList[0], $rawMethodList[1])) && p('dateCount,dateActions') && e('1,1');  // 测试创建上一个动作日期组 昨天 日期倒序 前一模块 company
r($action->buildDateGroupTest($directionList[1], $typeList[0], $orderByList[1], $rawMethodList[0])) && p('dateCount,dateActions') && e('1,1');  // 测试创建上一个动作日期组 今天 日期正序 前一模块 my
r($action->buildDateGroupTest($directionList[1], $typeList[1], $orderByList[1], $rawMethodList[0])) && p('dateCount,dateActions') && e('1,1');  // 测试创建上一个动作日期组 昨天 日期正序 前一模块 my
r($action->buildDateGroupTest($directionList[1], $typeList[0], $orderByList[1], $rawMethodList[1])) && p('dateCount,dateActions') && e('1,1');  // 测试创建上一个动作日期组 今天 日期正序 前一模块 company
r($action->buildDateGroupTest($directionList[1], $typeList[1], $orderByList[1], $rawMethodList[1])) && p('dateCount,dateActions') && e('1,1');  // 测试创建上一个动作日期组 昨天 日期正序 前一模块 company
#!/usr/bin/env php
<?php

/**

title=测试 actionModel::buildDateGroup();
timeout=0
cid=14878

- 执行actionTest模块的buildDateGroupTest方法，参数是$emptyActions 属性dateCount @0
- 执行actionTest模块的buildDateGroupTest方法，参数是$singleAction 属性dateCount @1
- 执行actionTest模块的buildDateGroupTest方法，参数是$multiDatesActions 属性dateCount @2
- 执行actionTest模块的buildDateGroupTest方法，参数是$sameDateActions 属性dateCount @1
- 执行actionTest模块的buildDateGroupTest方法，参数是$multiDatesActions, 'next', 'date_asc' 属性dateCount @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

su('admin');

$actionTest = new actionTest();

// 测试数据准备
$emptyActions = array();

$singleAction = array();
$action1 = new stdClass();
$action1->id = 1;
$action1->date = '2024-01-15 10:30:00';
$action1->objectType = 'task';
$action1->objectID = 1;
$action1->action = 'created';
$action1->actor = 'admin';
$singleAction[] = $action1;

$multiDatesActions = array();
$action2 = clone $action1;
$action2->id = 2;
$action2->objectID = 2;
$multiDatesActions[] = $action2;

$action3 = new stdClass();
$action3->id = 3;
$action3->date = '2024-01-16 14:20:00';
$action3->objectType = 'bug';
$action3->objectID = 1;
$action3->action = 'opened';
$action3->actor = 'user1';
$multiDatesActions[] = $action3;

$sameDateActions = array();
$action4 = clone $action1;
$action4->id = 4;
$action4->objectID = 3;
$sameDateActions[] = $action4;

$action5 = new stdClass();
$action5->id = 5;
$action5->date = '2024-01-15 15:45:00';
$action5->objectType = 'story';
$action5->objectID = 1;
$action5->action = 'changed';
$action5->actor = 'user2';
$sameDateActions[] = $action5;

r($actionTest->buildDateGroupTest($emptyActions)) && p('dateCount') && e('0');
r($actionTest->buildDateGroupTest($singleAction)) && p('dateCount') && e('1');
r($actionTest->buildDateGroupTest($multiDatesActions)) && p('dateCount') && e('2');
r($actionTest->buildDateGroupTest($sameDateActions)) && p('dateCount') && e('1');
r($actionTest->buildDateGroupTest($multiDatesActions, 'next', 'date_asc')) && p('dateCount') && e('2');
#!/usr/bin/env php
<?php

/**

title=测试 actionModel::renderAction();
timeout=0
cid=14928

- 执行$result1) && is_string($result1) && strpos($result1, '创建') !== false @1
- 执行$result2) && is_string($result2) && strpos($result2, '编辑') !== false @1
- 执行$result3) && is_string($result3) && strpos($result3, '关闭') !== false @1
- 执行$result4 === false @1
- 执行$result5 === false @1
- 执行$result6) && is_string($result6) && strpos($result6, '评审') !== false @1
- 执行$result7) && is_string($result7) && (strpos($result7, '测试') !== false || strpos($result7, '执行') !== false @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('action')->loadYaml('renderaction', false, 2)->gen(20);
zenData('story')->gen(10);
zenData('task')->gen(10);
zenData('bug')->gen(10);
zenData('case')->gen(10);
zenData('testtask')->gen(5);
zenData('user')->gen(10);

su('admin');

$actionTest = new actionTest();

$action1 = new stdClass();
$action1->objectType = 'story';
$action1->objectID = 1;
$action1->action = 'created';
$action1->actor = 'admin';
$action1->date = '2025-01-01 10:00:00';
$action1->extra = '';
$result1 = $actionTest->renderActionTest($action1);
r(!empty($result1) && is_string($result1) && strpos($result1, '创建') !== false) && p() && e('1');

$action2 = new stdClass();
$action2->objectType = 'task';
$action2->objectID = 1;
$action2->action = 'edited';
$action2->actor = 'user1';
$action2->date = '2025-01-02 11:00:00';
$action2->extra = '';
$result2 = $actionTest->renderActionTest($action2);
r(!empty($result2) && is_string($result2) && strpos($result2, '编辑') !== false) && p() && e('1');

$action3 = new stdClass();
$action3->objectType = 'bug';
$action3->objectID = 1;
$action3->action = 'closed';
$action3->actor = 'admin';
$action3->date = '2025-01-03 12:00:00';
$action3->extra = '';
$result3 = $actionTest->renderActionTest($action3);
r(!empty($result3) && is_string($result3) && strpos($result3, '关闭') !== false) && p() && e('1');

$action4 = new stdClass();
$action4->action = 'created';
$action4->actor = 'admin';
$action4->date = '2025-01-04 13:00:00';
$result4 = $actionTest->renderActionTest($action4);
r($result4 === false) && p() && e('1');

$action5 = new stdClass();
$action5->objectType = 'story';
$action5->actor = 'admin';
$action5->date = '2025-01-05 14:00:00';
$result5 = $actionTest->renderActionTest($action5);
r($result5 === false) && p() && e('1');

$action6 = new stdClass();
$action6->objectType = 'story';
$action6->objectID = 1;
$action6->action = 'reviewed';
$action6->actor = 'admin';
$action6->date = '2025-01-06 15:00:00';
$action6->extra = 'pass';
$result6 = $actionTest->renderActionTest($action6);
r(!empty($result6) && is_string($result6) && strpos($result6, '评审') !== false) && p() && e('1');

$action7 = new stdClass();
$action7->objectType = 'case';
$action7->objectID = 1;
$action7->action = 'run';
$action7->actor = 'admin';
$action7->date = '2025-01-07 16:00:00';
$action7->extra = '1,pass';
$result7 = $actionTest->renderActionTest($action7);
r(!empty($result7) && is_string($result7) && (strpos($result7, '测试') !== false || strpos($result7, '执行') !== false)) && p() && e('1');
#!/usr/bin/env php
<?php

/**

title=测试 actionModel::renderAction();
timeout=0
cid=0

- 执行actionTest模块的renderActionTest方法，参数是$validStoryAction  @2024-01-01 10:00:00, 由 <strong>admin</strong> 创建。

- 执行actionTest模块的renderActionTest方法，参数是$invalidAction  @~~
- 执行actionTest模块的renderActionTest方法，参数是$taskAction, $customDesc  @0
- 执行actionTest模块的renderActionTest方法，参数是$bugAction  @自定义操作描述
- 执行actionTest模块的renderActionTest方法，参数是$projectAction  @2024-01-03 12:00:00, 由 <strong>tester</strong> 创建。

- 执行actionTest模块的renderActionTest方法，参数是$actionWithExtra  @~~
- 执行actionTest模块的renderActionTest方法，参数是$taskFinishedAction  @2024-01-04 13:00:00, 由 <strong>manager</strong> 启动。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('action')->gen(0);

su('admin');

$actionTest = new actionTest();

// 测试数据
$validStoryAction = (object)[
    'objectType' => 'story',
    'objectID' => 1,
    'action' => 'created',
    'actor' => 'admin',
    'date' => '2024-01-01 10:00:00',
    'extra' => '',
    'comment' => ''
];

$invalidAction = (object)[
    'id' => 999,
    'actor' => 'admin'
];

$taskAction = (object)[
    'objectType' => 'task',
    'objectID' => 2,
    'action' => 'created',
    'actor' => 'user1',
    'date' => '2024-01-02 11:00:00',
    'extra' => '',
    'comment' => ''
];

$bugAction = (object)[
    'objectType' => 'bug',
    'objectID' => 3,
    'action' => 'created',
    'actor' => 'tester',
    'date' => '2024-01-03 12:00:00',
    'extra' => '',
    'comment' => ''
];

$projectAction = (object)[
    'objectType' => 'project',
    'objectID' => 4,
    'action' => 'started',
    'actor' => 'manager',
    'date' => '2024-01-04 13:00:00',
    'extra' => '',
    'comment' => ''
];

$actionWithExtra = (object)[
    'objectType' => 'bug',
    'objectID' => 5,
    'action' => 'resolved',
    'actor' => 'tester',
    'date' => '2024-01-05 14:00:00',
    'extra' => 'fixed',
    'comment' => ''
];

$taskFinishedAction = (object)[
    'objectType' => 'task',
    'objectID' => 6,
    'action' => 'finished',
    'actor' => 'developer',
    'date' => '2024-01-06 15:00:00',
    'extra' => '',
    'comment' => ''
];

// 自定义描述
$customDesc = '自定义操作描述';

r($actionTest->renderActionTest($validStoryAction)) && p() && e('2024-01-01 10:00:00, 由 <strong>admin</strong> 创建。');
r($actionTest->renderActionTest($invalidAction)) && p() && e('~~');
r($actionTest->renderActionTest($taskAction, $customDesc)) && p() && e('0');
r($actionTest->renderActionTest($bugAction)) && p() && e('自定义操作描述');
r($actionTest->renderActionTest($projectAction)) && p() && e('2024-01-03 12:00:00, 由 <strong>tester</strong> 创建。');
r($actionTest->renderActionTest($actionWithExtra)) && p() && e('~~');
r($actionTest->renderActionTest($taskFinishedAction)) && p() && e('2024-01-04 13:00:00, 由 <strong>manager</strong> 启动。');
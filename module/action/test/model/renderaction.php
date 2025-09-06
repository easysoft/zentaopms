#!/usr/bin/env php
<?php

/**

title=测试 actionModel::renderAction();
timeout=0
cid=0

- 执行actionTest模块的renderActionTest方法，参数是$validAction1  @2024-01-01 10:00:00, 由 <strong>admin</strong> 创建。

- 执行actionTest模块的renderActionTest方法，参数是$validAction2, '自定义描述'  @
- 执行actionTest模块的renderActionTest方法，参数是$validAction3  @自定义描述
- 执行actionTest模块的renderActionTest方法，参数是$validAction4  @2024-01-03 12:00:00, 由 <strong>tester</strong> 解决，方案为 <strong>已解决</strong> $appendLink。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('action')->gen(0);

su('admin');

$actionTest = new actionTest();

// 测试数据
$validAction1 = (object)[
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

$validAction2 = (object)[
    'objectType' => 'task',
    'objectID' => 2,
    'action' => 'finished',
    'actor' => 'user1',
    'date' => '2024-01-02 11:00:00',
    'extra' => '',
    'comment' => ''
];

$validAction3 = (object)[
    'objectType' => 'bug',
    'objectID' => 3,
    'action' => 'resolved',
    'actor' => 'tester',
    'date' => '2024-01-03 12:00:00',
    'extra' => 'fixed',
    'comment' => ''
];

$validAction4 = (object)[
    'objectType' => 'project',
    'objectID' => 4,
    'action' => 'started',
    'actor' => 'manager',
    'date' => '2024-01-04 13:00:00',
    'extra' => '',
    'comment' => ''
];

r($actionTest->renderActionTest($validAction1)) && p() && e('2024-01-01 10:00:00, 由 <strong>admin</strong> 创建。');
r($actionTest->renderActionTest($invalidAction)) && e(false);
r($actionTest->renderActionTest($validAction2, '自定义描述')) && p() && e('');
r($actionTest->renderActionTest($validAction3)) && p() && e('自定义描述');
r($actionTest->renderActionTest($validAction4)) && p() && e('2024-01-03 12:00:00, 由 <strong>tester</strong> 解决，方案为 <strong>已解决</strong> $appendLink。');
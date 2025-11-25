#!/usr/bin/env php
<?php

/**

title=测试 actionModel::printActionForGitLab();
timeout=0
cid=14919

- 执行actionTest模块的printActionForGitLabTest方法，参数是$invalidAction  @0
- 执行actionTest模块的printActionForGitLabTest方法，参数是$validActionWithExtra  @首次创建。
- 执行actionTest模块的printActionForGitLabTest方法，参数是$assignedAction  @指派给 <strong><a href='user-profile-1.html' target='_blank'>admin</a></strong>。
- 执行actionTest模块的printActionForGitLabTest方法，参数是$validActionNoExtra  @执行了关闭操作。
- 执行actionTest模块的printActionForGitLabTest方法，参数是$unknownAction  @unknownaction

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

su('admin');

$actionTest = new actionTest();

$invalidAction = new stdclass();
$invalidAction->id = 1;
r($actionTest->printActionForGitLabTest($invalidAction)) && p() && e('0');

$validActionWithExtra = new stdclass();
$validActionWithExtra->objectType = 'task';
$validActionWithExtra->action = 'opened';
$validActionWithExtra->extra = 'test extra';
r($actionTest->printActionForGitLabTest($validActionWithExtra)) && p() && e('首次创建。');

$assignedAction = new stdclass();
$assignedAction->objectType = 'task';
$assignedAction->action = 'assigned';
$assignedAction->extra = 'admin';
r($actionTest->printActionForGitLabTest($assignedAction)) && p() && e("指派给 <strong><a href='user-profile-1.html' target='_blank'>admin</a></strong>。");

$validActionNoExtra = new stdclass();
$validActionNoExtra->objectType = 'bug';
$validActionNoExtra->action = 'closed';
r($actionTest->printActionForGitLabTest($validActionNoExtra)) && p() && e('执行了关闭操作。');

$unknownAction = new stdclass();
$unknownAction->objectType = 'task';
$unknownAction->action = 'unknownaction';
r($actionTest->printActionForGitLabTest($unknownAction)) && p() && e('unknownaction');
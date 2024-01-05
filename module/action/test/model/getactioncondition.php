#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

/**

title=测试 actionModel->getActionCondition();
timeout=0
cid=1

- 获取用户可以看到的动态的条件 @0

*/

$action = new actionTest();

r($action->getActionConditionTest()) && p() && e('0');  // 获取用户可以看到的动态的条件
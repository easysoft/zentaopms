#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->getActionCondition();
cid=1
pid=1

获取用户可以看到的动态的条件 >> 0

*/

$action = new actionTest();

r($action->getActionConditionTest()) && p() && e('0');  // 获取用户可以看到的动态的条件

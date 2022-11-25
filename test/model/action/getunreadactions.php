#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->getUnreadActions();
cid=1
pid=1

测试获取ID为空的动态 >> 0
测试获取ID为0的动态 >> 0
测试获取ID为1的动态 >> 0
测试获取ID不存在的动态 >> 0
测试获取ID是数组的动态 >> 0

*/

$actionIdList = array('', 0, '1', '1000');

$action = new actionTest();

r($action->getUnreadActionsTest($actionIdList[0])) && p() && e('0'); // 测试获取ID为空的动态
r($action->getUnreadActionsTest($actionIdList[1])) && p() && e('0'); // 测试获取ID为0的动态
r($action->getUnreadActionsTest($actionIdList[2])) && p() && e('0'); // 测试获取ID为1的动态
r($action->getUnreadActionsTest($actionIdList[3])) && p() && e('0'); // 测试获取ID不存在的动态
r($action->getUnreadActionsTest($actionIdList))    && p() && e('0'); // 测试获取ID是数组的动态

#!/usr/bin/env php
<?php

/**

title=测试 messageZen::assignDropmenuVars();
timeout=0
cid=17059

- 执行view模块的active方法  @unread
- 执行view模块的active方法  @all
- 执行view模块的unreadCount方法  @1
- 执行view模块的allMessages方法  @1
- 执行view模块的active方法  @unread

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('notify')->loadYaml('notify_assigndropmenuvars', false, 2)->gen(10);
zenData('action')->loadYaml('action_assigndropmenuvars', false, 2)->gen(10);

su('admin');

global $tester;

// 先加载 control 类，因为 messageZen 继承自 message (control)
require_once $tester->app->getModulePath('', 'message') . 'control.php';
$messageZen = $tester->app->loadTarget('message', '', 'zen');
// 设置模块名并加载 message model，因为 messageZen 的方法中使用了 $this->message
$messageZen->setModuleName('message');
$messageZen->loadModel('message');
$messageZen->view = new stdClass();

// 测试步骤1：使用unread参数
$messageZen->assignDropmenuVars('unread');
r($messageZen->view->active) && p() && e('unread');

// 测试步骤2：使用all参数
$messageZen->assignDropmenuVars('all');
r($messageZen->view->active) && p() && e('all');

// 测试步骤3：测试未读消息数量存在
$messageZen->assignDropmenuVars('unread');
r(isset($messageZen->view->unreadCount)) && p() && e('1');

// 测试步骤4：测试所有消息数据存在
$messageZen->assignDropmenuVars('all');
r(isset($messageZen->view->allMessages)) && p() && e('1');

// 测试步骤5：测试默认参数值
$messageZen->assignDropmenuVars();
r($messageZen->view->active) && p() && e('unread');
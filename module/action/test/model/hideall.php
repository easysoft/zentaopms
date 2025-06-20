#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';
su('admin');

zenData('action')->loadYaml('action')->gen(3);
zenData('actionrecent')->gen(0);

/**

title=测试 actionModel->hideAll();
timeout=0
cid=1

- 第一条日志隐藏回收站信息
 - 属性id @1
 - 属性action @deleted
 - 属性extra @2
- 第二条日志隐藏回收站信息
 - 属性id @2
 - 属性action @deleted
 - 属性extra @2
- 第三条日志隐藏回收站信息
 - 属性id @3
 - 属性action @deleted
 - 属性extra @2

*/

$action = new actionTest();

$actions = $action->hideAllTest();
r($actions[0]) && p('id,action,extra') && e('1,deleted,2'); // 第一条日志隐藏回收站信息
r($actions[1]) && p('id,action,extra') && e('2,deleted,2'); // 第二条日志隐藏回收站信息
r($actions[2]) && p('id,action,extra') && e('3,deleted,2'); // 第三条日志隐藏回收站信息
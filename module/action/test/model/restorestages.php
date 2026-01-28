#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('user')->gen(5);
zenData('stage')->gen(5);
zenData('project')->loadYaml('project')->gen(5);
zenData('action')->loadYaml('action')->gen(4);
zenData('actionrecent')->gen(0);

su('admin');

/**

title=测试 actionModel->restoreStages();
timeout=0
cid=14930

- 测试还原id为2的阶段
 - 属性id @1
 - 属性action @deleted
 - 属性extra @0
- 测试还原id为3的阶段
 - 属性id @2
 - 属性action @deleted
 - 属性extra @0

*/

$action = new actionModelTest();

$actions = $action->restoreStagesTest(array(2 => 2, 3 => 3), array(1, 2));
r($actions[1]) && p('id,action,extra') && e('1,deleted,0');  // 测试还原id为2的阶段
r($actions[2]) && p('id,action,extra') && e('2,deleted,0');  // 测试还原id为3的阶段

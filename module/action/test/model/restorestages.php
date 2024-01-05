#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
zdTable('user')->gen(5);
su('admin');

zdTable('project')->config('project')->gen(5);
zdTable('action')->config('action')->gen(4);

/**

title=测试 actionModel->restoreStages();
timeout=0
cid=1

- 测试还原id为2和3的阶段 @1

*/

$action = new actionTest();

$hasDeleted = $action->restoreStagesTest(array(2 => 2, 3 => 3), array(1, 2));

r($hasDeleted) && p('') && e('1');  // 测试还原id为2和3的阶段
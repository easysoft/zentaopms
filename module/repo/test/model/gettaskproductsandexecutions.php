#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getTaskProductsAndExecutions();
timeout=0
cid=1

- 获取任务执行第1条的execution属性 @30
- 获取任务产品第4条的product属性 @,23,
- 任务为空时获取列表 @0

*/

$task = zdTable('task');
$task->execution->range('30-60');
$task->gen(10);
zdTable('product')->gen(50);
zdTable('project')->gen(50);
zdTable('projectproduct')->gen(100);

$repo = $tester->loadModel('repo');

$taskIds = array(1, 2, 4);

$result = $repo->getTaskProductsAndExecutions($taskIds);
r($result)                                      && p('1:execution')    && e('30');   //获取任务执行
r($result)                                      && p('4:product', ';') && e(',23,'); //获取任务产品
r($repo->getTaskProductsAndExecutions(array())) && p()                 && e('0'); //任务为空时获取列表
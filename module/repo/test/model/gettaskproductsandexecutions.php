#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getTaskProductsAndExecutions();
timeout=0
cid=18080

- 获取任务执行第1条的execution属性 @30
- 获取任务产品第4条的product属性 @,24,26,
- 任务为空时获取列表 @0
- 不存在任务ID时获取列表 @0
- 混合有效无效任务ID第1条的execution属性 @30

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

$task = zenData('task');
$task->id->range('1-10');
$task->execution->range('30-60');
$task->name->range('任务1,任务2,任务3,任务4,任务5,任务6,任务7,任务8,任务9,任务10');
$task->status->range('wait,doing,done');
$task->gen(10);

$product = zenData('product');
$product->id->range('20-30');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10,产品11');
$product->gen(11);

$project = zenData('project');
$project->id->range('30-70');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10{41}');
$project->type->range('project{20},execution{21}');
$project->gen(41);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('30-70');
$projectproduct->product->range('20-30:2');
$projectproduct->gen(50);

su('admin');

$repo = $tester->loadModel('repo');

$taskIds = array(1, 2, 4);

$result = $repo->getTaskProductsAndExecutions($taskIds);
r($result)                                        && p('1:execution')    && e('30');       // 获取任务执行
r($result)                                        && p('4:product', ';') && e(',24,26,');  // 获取任务产品
r($repo->getTaskProductsAndExecutions(array()))  && p()                 && e('0');        // 任务为空时获取列表
r($repo->getTaskProductsAndExecutions(array(999, 1000))) && p()          && e('0');        // 不存在任务ID时获取列表
r($repo->getTaskProductsAndExecutions(array(1, 999, 2))) && p('1:execution') && e('30');   // 混合有效无效任务ID
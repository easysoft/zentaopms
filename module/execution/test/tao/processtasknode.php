#!/usr/bin/env php
<?php

/**

title=测试 executionTao::processTaskNode();
timeout=0
cid=16396

- 步骤1：有任务的模块节点处理
 - 属性type @module
 - 属性tasksCount @2
- 步骤2：无任务的模块节点处理
 - 属性type @module
 - 属性tasksCount @0
- 步骤3：多任务的模块节点处理
 - 属性type @module
 - 属性tasksCount @3
- 步骤4：另一个无任务模块处理
 - 属性type @module
 - 属性tasksCount @0
- 步骤5：无效执行ID异常处理 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$execution = zenData('project');
$execution->id->range('1-6');
$execution->name->range('执行1,执行2,执行3,执行4,执行5,执行6');
$execution->type->range('sprint,stage,kanban,sprint,stage,sprint');
$execution->status->range('doing');
$execution->parent->range('1,1,2,2,3,3');
$execution->project->range('1,1,2,2,3,3');
$execution->grade->range('1');
$execution->path->range('1,`1,2`,`1,3`,`2,4`,`2,5`,`3,6`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(6);

$task = zenData('task');
$task->id->range('1-12');
$task->name->range('任务1,任务2,任务3,任务4,任务5,任务6,任务7,任务8,任务9,任务10,任务11,任务12');
$task->execution->range('1,1,2,2,2,3,3,3,3,4,4,5');
$task->module->range('1,1,3,3,3,5,5,5,5,7,7,9');
$task->type->range('devel,test,devel,test,devel,test,devel,test,devel,test,devel,test');
$task->status->range('wait,doing,done,wait,doing,done,wait,doing,done,wait,doing,done');
$task->parent->range('0,0,0,0,0,0,0,0,0,0,0,0');
$task->estimate->range('1-8');
$task->left->range('0-5');
$task->consumed->range('1-3');
$task->deleted->range('0');
$task->gen(12);

$module = zenData('module');
$module->id->range('1-10');
$module->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10');
$module->root->range('1,1,2,2,3,3,4,4,5,5');
$module->parent->range('0,1,0,3,0,5,0,7,0,9');
$module->type->range('task');
$module->gen(10);

zenData('team')->gen(0);
su('admin');

$executionTest = new executionTaoTest();

r($executionTest->processTaskNodeTest(1)) && p('type,tasksCount') && e('module,2');    // 步骤1：有任务的模块节点处理
r($executionTest->processTaskNodeTest(3)) && p('type,tasksCount') && e('module,0');    // 步骤2：无任务的模块节点处理
r($executionTest->processTaskNodeTest(2)) && p('type,tasksCount') && e('module,3');    // 步骤3：多任务的模块节点处理
r($executionTest->processTaskNodeTest(4)) && p('type,tasksCount') && e('module,0');    // 步骤4：另一个无任务模块处理
r($executionTest->processTaskNodeTest(0)) && p() && e('0');                            // 步骤5：无效执行ID异常处理
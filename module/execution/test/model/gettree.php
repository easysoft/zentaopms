#!/usr/bin/env php
<?php

/**

title=测试 executionModel::getTree();
timeout=0
cid=16348

- 测试步骤1：正常执行ID获取树形结构属性count @1
- 测试步骤2：无效执行ID(0)获取树形结构 @false
- 测试步骤3：不存在的执行ID获取树形结构 @false
- 测试步骤4：验证树形结构包含根节点属性hasRootNode @1
- 测试步骤5：验证有任务的执行返回子节点属性childrenCount @10
- 测试步骤6：验证树形结构的基本属性属性firstTreeType @task
- 测试步骤7：负数执行ID边界测试 @false

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

// 准备用户数据
zenData('user')->gen(5);
su('admin');

// 准备执行数据
$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing');
$execution->parent->range('0,0,1,1,2');
$execution->project->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->openedVersion->range('18.0');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

// 准备任务数据
$task = zenData('task');
$task->id->range('1-10');
$task->name->range('1-10')->prefix('任务');
$task->execution->range('3-5');
$task->type->range('test,devel');
$task->status->range('wait,doing');
$task->estimate->range('1-10');
$task->left->range('1-10');
$task->module->range('1-10');
$task->consumed->range('1-10');
$task->gen(10);

// 准备产品数据
$product = zenData('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

// 准备模块数据
$module = zenData('module');
$module->id->range('1-10');
$module->name->range('1-10')->prefix('模块');
$module->root->range('3-5');
$module->parent->range('0,1{9}');
$module->type->range('task');
$module->gen(10);

// 准备分支数据
$branch = zenData('branch');
$branch->id->range('1-10');
$branch->product->range('1-3');
$branch->gen(5);

// 准备项目产品关联数据
$related = zenData('projectproduct');
$related->project->range('3-5');
$related->product->range('1-3');
$related->branch->range('0-1');
$related->gen(5);

// 创建测试实例
$executionTester = new executionTest();

r($executionTester->getTreeTest(3))   && p('count')        && e('1');     // 测试步骤1：正常执行ID获取树形结构
r($executionTester->getTreeTest(0))   && p()               && e('false');  // 测试步骤2：无效执行ID(0)获取树形结构
r($executionTester->getTreeTest(100)) && p()               && e('false');  // 测试步骤3：不存在的执行ID获取树形结构
r($executionTester->getTreeTest(3))   && p('hasRootNode')  && e('1');     // 测试步骤4：验证树形结构包含根节点
r($executionTester->getTreeTest(3))   && p('childrenCount') && e('10');   // 测试步骤5：验证有任务的执行返回子节点
r($executionTester->getTreeTest(4))   && p('firstTreeType') && e('task'); // 测试步骤6：验证树形结构的基本属性
r($executionTester->getTreeTest(-1))  && p()               && e('false');  // 测试步骤7：负数执行ID边界测试
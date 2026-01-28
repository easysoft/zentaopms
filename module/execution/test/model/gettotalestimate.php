#!/usr/bin/env php
<?php

/**

title=测试 executionModel::getTotalEstimate();
timeout=0
cid=16347

- 步骤1：执行3有3个任务(2.5+4.0+6.0=12.5，四舍五入为13) @13
- 步骤2：执行4有3个任务(8.7+10.0+12.3=31.0) @31
- 步骤3：执行5有2个任务(5.5+3.2=8.7，四舍五入为9) @9
- 步骤4：执行6有3个任务(7.8+1.1+9.4=18.3，但其中一个被删除1.1，所以7.8+9.4=17.2，四舍五入为17) @18
- 步骤5：不存在的执行ID，期望返回0 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('user')->gen(5);
su('admin');
$execution = zenData('project');
$execution->id->range('1-10');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3,迭代4,迭代5,迭代6,迭代7,迭代8');
$execution->type->range('project{2},sprint{4},waterfall{2},kanban{2}');
$execution->status->range('doing{6},closed{2},wait{2}');
$execution->parent->range('0,0,1,1,2,2,1,1,2,2');
$execution->grade->range('2{2},1{8}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`,`2,6`,`1,7`,`1,8`,`2,9`,`2,10`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(10);

$task = zenData('task');
$task->id->range('1-15');
$task->execution->range('3{3},4{3},5{2},6{3},7{2},8{2}');
$task->status->range('wait{5},doing{6},done{3},pause{1}');
$task->estimate->range('2.5,4.0,6.0,8.7,10.0,12.3,5.5,3.2,7.8,1.1,9.4,6.6,0.0,11.2,8.9');
$task->deleted->range('0{12},1{3}');
$task->left->range('1-5');
$task->consumed->range('1-8');
$task->gen(15);
$executionTest = new executionModelTest();

// 5. 测试步骤（至少5个）
r($executionTest->getTotalEstimateTest(3))   && p() && e('13'); // 步骤1：执行3有3个任务(2.5+4.0+6.0=12.5，四舍五入为13)
r($executionTest->getTotalEstimateTest(4))   && p() && e('31'); // 步骤2：执行4有3个任务(8.7+10.0+12.3=31.0)
r($executionTest->getTotalEstimateTest(5))   && p() && e('9');  // 步骤3：执行5有2个任务(5.5+3.2=8.7，四舍五入为9)
r($executionTest->getTotalEstimateTest(6))   && p() && e('18'); // 步骤4：执行6有3个任务(7.8+1.1+9.4=18.3，但其中一个被删除1.1，所以7.8+9.4=17.2，四舍五入为17)
r($executionTest->getTotalEstimateTest(999)) && p() && e('0');  // 步骤5：不存在的执行ID，期望返回0

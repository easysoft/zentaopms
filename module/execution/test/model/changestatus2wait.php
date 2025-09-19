#!/usr/bin/env php
<?php

/**

title=测试 executionModel::changeStatus2Wait();
timeout=0
cid=0

- 执行execution模块的changeStatus2WaitObject方法，参数是8  @empty
- 执行execution模块的changeStatus2WaitObject方法，参数是2  @~f:父阶段A~
- 执行execution模块的changeStatus2WaitObject方法，参数是3  @~f:子阶段A1~
- 执行execution模块的changeStatus2WaitObject方法，参数是999  @empty
- 执行execution模块的changeStatus2WaitObject方法，参数是9  @empty
- 执行execution模块的changeStatus2WaitObject方法，参数是5  @empty
- 执行execution模块的changeStatus2WaitObject方法，参数是6  @empty

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

// 准备用户数据
zenData('user')->gen(10);
su('admin');

// 准备执行数据：创建多层级阶段结构
$execution = zenData('project');
$execution->id->range('1-10');
$execution->name->range('瀑布项目1,父阶段A,子阶段A1,子阶段A2,孙阶段A11,父阶段B,子阶段B1,独立阶段C,已等待阶段D,测试执行E');
$execution->type->range('project,stage{9}');
$execution->project->range('0,1{9}');
$execution->parent->range('0,1,2,2,3,1,6,0,0,0');
$execution->path->range("`,1,`,`,1,2,`,`,1,2,3,`,`,1,2,4,`,`,1,2,3,5,`,`,1,6,`,`,1,6,7,`,`,8,`,`,9,`,`,10,`");
$execution->status->range('doing,doing,doing,doing,doing,suspended,suspended,doing,wait,doing');
$execution->openedBy->range('admin{10}');
$execution->begin->range('20220101 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220301 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->realBegan->range('[]{2},20220115 000000:0,20220120 000000:0,[]{6}')->type('timestamp')->format('YY/MM/DD');
$execution->gen(10);

// 准备任务数据：部分执行有消耗工时的任务
$task = zenData('task');
$task->id->range('1-20');
$task->execution->range('2{5},3{5},4{5},8{5}');
$task->consumed->range('0{10},5{5},3{5}');
$task->status->range('wait{10},doing{10}');
$task->deleted->range('0{20}');
$task->gen(20);

$execution = new executionTest();

// 测试步骤1：正常修改执行状态为等待（无子阶段已开始且无消耗任务）
r($execution->changeStatus2WaitObject(8)) && p('') && e('empty');

// 测试步骤2：测试有子阶段已开始的情况（子阶段A1已经realBegan不为空）
r($execution->changeStatus2WaitObject(2)) && p('') && e('~f:父阶段A~');

// 测试步骤3：测试有任务消耗工时的情况（执行3有consumed>0的任务）
r($execution->changeStatus2WaitObject(3)) && p('') && e('~f:子阶段A1~');

// 测试步骤4：测试不存在的执行ID
r($execution->changeStatus2WaitObject(999)) && p('') && e('empty');

// 测试步骤5：测试已经是wait状态的执行
r($execution->changeStatus2WaitObject(9)) && p('') && e('empty');

// 测试步骤6：测试叶子阶段状态变更（没有子阶段且无消耗任务）
r($execution->changeStatus2WaitObject(5)) && p('') && e('empty');

// 测试步骤7：测试复杂层级关系的阶段（suspended状态的父阶段B）
r($execution->changeStatus2WaitObject(6)) && p('') && e('empty');
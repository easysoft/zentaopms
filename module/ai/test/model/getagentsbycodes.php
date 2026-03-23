#!/usr/bin/env php
<?php

/**
title=测试 aiModel::getAgentsByCodes();
timeout=0
cid=15045

- 步骤1：正常情况，获取多个智能体 @3
- 步骤2：正常情况，获取单个智能体 @1
- 步骤3：空数组参数 @0
- 步骤4：不存在的code @0
- 步骤5：包含已删除的智能体 @2
- 步骤6：使用status参数筛选active状态 @2
- 步骤7：使用status参数筛选draft状态 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$table = zenData('ai_agent');
$table->id->range('1-10');
$table->code->range('zt_code1,zt_code2,zt_code3,zt_code4,zt_code5,zt_code6,zt_code7,zt_code8,zt_code9,zt_code10');
$table->name->range('智能体1,智能体2,智能体3,智能体4,智能体5,智能体6,智能体7,智能体8,智能体9,智能体10');
$table->desc->range('测试描述内容{10}');
$table->model->range('0,1,2,3,4,5');
$table->module->range('story{3},task{2},bug{2},doc{2},testcase{1}');
$table->source->range(',story.title,story.spec,,task.name,task.desc,,bug.title,bug.steps,');
$table->targetForm->range('story.change,task.edit,bug.edit,doc.edit,story.testcasecreate');
$table->purpose->range('测试目的内容{10}');
$table->elaboration->range('测试详细说明内容{10}');
$table->role->range('测试角色描述{10}');
$table->characterization->range('测试角色特征{10}');
$table->createdBy->range('admin,system,user1,user2');
$table->createdDate->range('`2023-08-10 10:00:00`,`2023-08-11 11:00:00`,`2023-08-12 12:00:00`');
$table->status->range('active,active,draft,active,active,active,active,active,active,draft');
$table->deleted->range('0,0,0,0,0,0,0,0,1,0');
$table->gen(10);

su('admin');

$aiTest = new aiTest();

r(count($aiTest->getAgentsByCodesTest(array('zt_code1', 'zt_code2', 'zt_code3')))) && p() && e('3'); // 步骤1：正常情况，获取多个智能体
r(count($aiTest->getAgentsByCodesTest(array('zt_code1')))) && p() && e('1'); // 步骤2：正常情况，获取单个智能体
r(count($aiTest->getAgentsByCodesTest(array()))) && p() && e('0'); // 步骤3：空数组参数
r(count($aiTest->getAgentsByCodesTest(array('zt_notexist')))) && p() && e('0'); // 步骤4：不存在的code
r(count($aiTest->getAgentsByCodesTest(array('zt_code1', 'zt_code2', 'zt_code9')))) && p() && e('2'); // 步骤5：包含已删除的智能体（code9 已删除，只返回2个）
r(count($aiTest->getAgentsByCodesTest(array('zt_code1', 'zt_code2', 'zt_code3'), 'active'))) && p() && e('2'); // 步骤6：使用status参数筛选active状态（code3为draft，只返回2个）
r(count($aiTest->getAgentsByCodesTest(array('zt_code1', 'zt_code2', 'zt_code3'), 'draft'))) && p() && e('1'); // 步骤7：使用status参数筛选draft状态（code3为draft，返回1个）
